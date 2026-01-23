<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetadataFetcher
{
    /**
     * Fetch metadata from DOI using CrossRef API.
     */
    public function fetchFromDoi(string $doi): ?array
    {
        try {
            // Clean the DOI
            $doi = $this->cleanDoi($doi);

            $response = Http::timeout(10)
                ->get("https://api.crossref.org/works/{$doi}");

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json('message');

            return $this->formatCrossRefData($data);
        } catch (\Exception $e) {
            Log::error("DOI fetch error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch metadata from ISBN using OpenLibrary API.
     */
    public function fetchFromIsbn(string $isbn): ?array
    {
        try {
            // Clean the ISBN
            $isbn = $this->cleanIsbn($isbn);

            $response = Http::timeout(10)
                ->get("https://openlibrary.org/api/books", [
                    'bibkeys' => "ISBN:{$isbn}",
                    'format' => 'json',
                    'jscmd' => 'data',
                ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json();
            $key = "ISBN:{$isbn}";

            if (!isset($data[$key])) {
                return null;
            }

            return $this->formatOpenLibraryData($data[$key], $isbn);
        } catch (\Exception $e) {
            Log::error("ISBN fetch error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Fetch metadata from a URL.
     */
    public function fetchFromUrl(string $url): ?array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
                ])
                ->get($url);

            if (!$response->successful()) {
                return null;
            }

            $html = $response->body();

            // Extract title with multiple fallbacks
            $title = 'Untitled Website';
            if (preg_match('/<title>(.*?)<\/title>/is', $html, $matches)) {
                $title = trim($matches[1]);
            }
            if (preg_match('/<meta property="og:title" content="(.*?)"/is', $html, $matches)) {
                $title = trim($matches[1]);
            }
            if (preg_match('/<meta name="twitter:title" content="(.*?)"/is', $html, $matches)) {
                $title = trim($matches[1]);
            }

            // Extract site name / publisher
            $host = parse_url($url, PHP_URL_HOST);
            $siteName = $host;
            if (preg_match('/<meta property="og:site_name" content="(.*?)"/is', $html, $matches)) {
                $siteName = trim($matches[1]);
            }
            if (preg_match('/<meta name="twitter:site" content="(.*?)"/is', $html, $matches)) {
                $siteName = trim($matches[1]);
            }

            // Extract authors
            $authors = [];
            if (preg_match('/<meta name="author" content="(.*?)"/is', $html, $matches)) {
                $authors[] = trim($matches[1]);
            }
            if (empty($authors) && preg_match('/<meta property="article:author" content="(.*?)"/is', $html, $matches)) {
                if (is_string($matches[1])) {
                    $authors[] = trim($matches[1]);
                }
            }

            // Extract year
            $year = date('Y');
            if (
                preg_match('/<meta property="article:published_time" content="(.*?)"/is', $html, $matches) ||
                preg_match('/<meta name="pubdate" content="(.*?)"/is', $html, $matches)
            ) {
                $dateStr = trim($matches[1]);
                $timestamp = strtotime($dateStr);
                if ($timestamp) {
                    $year = date('Y', $timestamp);
                }
            }

            return [
                'title' => html_entity_decode(strip_tags($title)),
                'authors' => $authors,
                'type' => 'website',
                'year' => $year,
                'url' => $url,
                'publisher' => html_entity_decode(strip_tags($siteName)),
            ];
        } catch (\Exception $e) {
            Log::error("URL fetch error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Auto-detect identifier type and fetch metadata.
     */
    public function fetch(string $identifier): array
    {
        $identifier = trim($identifier);

        // Check if it's a URL
        if (filter_var($identifier, FILTER_VALIDATE_URL)) {
            $data = $this->fetchFromUrl($identifier);
            return $data ? [$data] : [];
        }

        // Check if it's a DOI
        if ($this->isDoi($identifier)) {
            $data = $this->fetchFromDoi($identifier);
            return $data ? [$data] : [];
        }

        // Check if it's a PMID (PubMed ID)
        if ($this->isPmid($identifier)) {
            $data = $this->fetchFromPmid($identifier);
            if ($data) return [$data];
        }

        // Check if it's an ISBN
        if ($this->isIsbn($identifier)) {
            $data = $this->fetchFromIsbn($identifier);
            if ($data) return [$data];
        }

        // Try searching for articles first if it looks like a paper title
        $articles = $this->searchArticles($identifier);
        if (!empty($articles)) {
            return $articles;
        }

        // Fallback to searching for books
        return $this->searchBook($identifier);
    }

    /**
     * Search for articles using CrossRef API.
     */
    public function searchArticles(string $query): array
    {
        try {
            $response = Http::timeout(10)
                ->get("https://api.crossref.org/works", [
                    'query' => $query,
                    'filter' => 'type:journal-article',
                    'rows' => 5,
                ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json('message');
            if (!isset($data['items']) || empty($data['items'])) {
                return [];
            }

            return array_map(fn($item) => $this->formatCrossRefData($item), $data['items']);
        } catch (\Exception $e) {
            Log::error("Article search error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch metadata from PMID (PubMed) using NCBI API.
     */
    public function fetchFromPmid(string $pmid): ?array
    {
        try {
            $response = Http::timeout(10)
                ->get("https://eutils.ncbi.nlm.nih.gov/entrez/eutils/esummary.fcgi", [
                    'db' => 'pubmed',
                    'id' => $pmid,
                    'retmode' => 'json',
                ]);

            if (!$response->successful()) {
                return null;
            }

            $data = $response->json('result');
            if (!isset($data[$pmid])) {
                return null;
            }

            return $this->formatPubMedData($data[$pmid]);
        } catch (\Exception $e) {
            Log::error("PMID fetch error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Search for books using Google Books API.
     */
    public function searchBook(string $query): array
    {
        try {
            $params = [
                'q' => $query,
                'maxResults' => 5, // Return more results for broad search
            ];

            if (preg_match('/[\x{0E00}-\x{0E7F}]/u', $query)) {
                $params['langRestrict'] = 'th';
            }

            $response = Http::timeout(10)
                ->get("https://www.googleapis.com/books/v1/volumes", $params);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            if (!isset($data['items']) || empty($data['items'])) {
                return [];
            }

            return array_map(fn($item) => $this->formatGoogleBooksData($item), $data['items']);
        } catch (\Exception $e) {
            Log::error("Book search error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Check if the identifier is a DOI.
     */
    private function isDoi(string $identifier): bool
    {
        return (bool) preg_match('/^10\.\d{4,}\//', $identifier) ||
            str_contains($identifier, 'doi.org/');
    }

    /**
     * Check if the identifier is a PMID.
     */
    private function isPmid(string $identifier): bool
    {
        return (bool) preg_match('/^\d{5,10}$/', $identifier);
    }

    /**
     * Check if the identifier is an ISBN.
     */
    private function isIsbn(string $identifier): bool
    {
        $clean = preg_replace('/[^0-9X]/', '', strtoupper($identifier));
        return strlen($clean) === 10 || strlen($clean) === 13;
    }

    /**
     * Clean DOI by removing URL prefix.
     */
    private function cleanDoi(string $doi): string
    {
        $doi = trim($doi);
        $doi = preg_replace('#^https?://(dx\.)?doi\.org/#', '', $doi);
        return $doi;
    }

    /**
     * Clean ISBN by removing non-numeric characters.
     */
    private function cleanIsbn(string $isbn): string
    {
        return preg_replace('/[^0-9X]/', '', strtoupper($isbn));
    }

    /**
     * Format CrossRef API response to Reference format.
     */
    private function formatCrossRefData(array $data): array
    {
        $authors = [];
        if (isset($data['author'])) {
            foreach ($data['author'] as $author) {
                $name = trim(($author['given'] ?? '') . ' ' . ($author['family'] ?? ''));
                if (!empty($name)) {
                    $authors[] = $name;
                }
            }
        }

        $year = null;
        if (isset($data['published-print']['date-parts'][0][0])) {
            $year = (string) $data['published-print']['date-parts'][0][0];
        } elseif (isset($data['published-online']['date-parts'][0][0])) {
            $year = (string) $data['published-online']['date-parts'][0][0];
        } elseif (isset($data['created']['date-parts'][0][0])) {
            $year = (string) $data['created']['date-parts'][0][0];
        }

        $type = $this->mapCrossRefType($data['type'] ?? 'other');

        return [
            'title' => $data['title'][0] ?? 'Untitled',
            'authors' => $authors,
            'type' => $type,
            'year' => $year,
            'doi' => $data['DOI'] ?? null,
            'url' => $data['URL'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'journal_name' => $data['container-title'][0] ?? null,
            'volume' => $data['volume'] ?? null,
            'issue' => $data['issue'] ?? null,
            'pages' => $data['page'] ?? null,
            'abstract' => isset($data['abstract']) ? strip_tags($data['abstract']) : null,
        ];
    }

    /**
     * Format Google Books API response to Reference format.
     */
    private function formatGoogleBooksData(array $item): array
    {
        $info = $item['volumeInfo'];

        $authors = $info['authors'] ?? [];
        $year = null;
        if (isset($info['publishedDate'])) {
            preg_match('/\d{4}/', $info['publishedDate'], $matches);
            $year = $matches[0] ?? null;
        }

        $isbn = null;
        if (isset($info['industryIdentifiers'])) {
            foreach ($info['industryIdentifiers'] as $id) {
                if ($id['type'] === 'ISBN_13') {
                    $isbn = $id['identifier'];
                    break;
                } elseif ($id['type'] === 'ISBN_10') {
                    $isbn = $id['identifier'];
                }
            }
        }

        return [
            'title' => $info['title'] ?? 'Untitled',
            'authors' => $authors,
            'type' => 'book',
            'year' => $year,
            'isbn' => $isbn,
            'url' => $info['infoLink'] ?? null,
            'publisher' => $info['publisher'] ?? null,
            'pages' => isset($info['pageCount']) ? (string) $info['pageCount'] : null,
            'abstract' => isset($info['description']) ? strip_tags($info['description']) : null,
        ];
    }

    /**
     * Format OpenLibrary API response to Reference format.
     */
    private function formatOpenLibraryData(array $data, string $isbn): array
    {
        $authors = [];
        if (isset($data['authors'])) {
            foreach ($data['authors'] as $author) {
                if (isset($author['name'])) {
                    $authors[] = $author['name'];
                }
            }
        }

        $year = null;
        if (isset($data['publish_date'])) {
            preg_match('/\d{4}/', $data['publish_date'], $matches);
            $year = $matches[0] ?? null;
        }

        return [
            'title' => $data['title'] ?? 'Untitled',
            'authors' => $authors,
            'type' => 'book',
            'year' => $year,
            'isbn' => $isbn,
            'url' => $data['url'] ?? null,
            'publisher' => $data['publishers'][0]['name'] ?? null,
            'pages' => isset($data['number_of_pages']) ? (string) $data['number_of_pages'] : null,
        ];
    }

    /**
     * Format PubMed API response to Reference format.
     */
    private function formatPubMedData(array $data): array
    {
        $authors = [];
        if (isset($data['authors'])) {
            foreach ($data['authors'] as $author) {
                if (isset($author['name'])) {
                    $authors[] = $author['name'];
                }
            }
        }

        $year = null;
        if (isset($data['pubdate'])) {
            preg_match('/\d{4}/', $data['pubdate'], $matches);
            $year = $matches[0] ?? null;
        }

        return [
            'title' => $data['title'] ?? 'Untitled',
            'authors' => $authors,
            'type' => 'journal',
            'year' => $year,
            'url' => "https://pubmed.ncbi.nlm.nih.gov/{$data['uid']}/",
            'publisher' => $data['source'] ?? null,
            'journal_name' => $data['fulljournalname'] ?? $data['source'] ?? null,
            'volume' => $data['volume'] ?? null,
            'issue' => $data['issue'] ?? null,
            'pages' => $data['pages'] ?? null,
        ];
    }

    /**
     * Map CrossRef type to our reference types.
     */
    private function mapCrossRefType(string $type): string
    {
        $map = [
            'journal-article' => 'journal',
            'book' => 'book',
            'book-chapter' => 'book',
            'proceedings-article' => 'conference',
            'dissertation' => 'thesis',
            'report' => 'report',
            'posted-content' => 'other',
            'dataset' => 'other',
        ];

        return $map[$type] ?? 'other';
    }
}
