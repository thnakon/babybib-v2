<?php

namespace App\Services;

use App\Models\Reference;
use Illuminate\Support\Collection;

class BibtexExporter
{
    /**
     * Export a single reference to BibTeX format.
     */
    public function export(Reference $reference): string
    {
        $type = $this->mapTypeToBibtex($reference->type);
        $key = $this->generateKey($reference);

        $fields = $this->buildFields($reference);
        $fieldsString = $this->formatFields($fields);

        return "@{$type}{{$key},\n{$fieldsString}}\n";
    }

    /**
     * Export multiple references to BibTeX format.
     */
    public function exportCollection(Collection $references): string
    {
        $entries = [];
        foreach ($references as $reference) {
            $entries[] = $this->export($reference);
        }
        return implode("\n", $entries);
    }

    /**
     * Export references as downloadable file content.
     */
    public function exportToFile(Collection $references): array
    {
        $content = $this->exportCollection($references);
        return [
            'content' => $content,
            'filename' => 'references_' . date('Y-m-d') . '.bib',
            'mime' => 'application/x-bibtex',
        ];
    }

    /**
     * Map our reference type to BibTeX entry type.
     */
    private function mapTypeToBibtex(string $type): string
    {
        return match ($type) {
            'book' => 'book',
            'journal' => 'article',
            'conference' => 'inproceedings',
            'thesis' => 'phdthesis',
            'report' => 'techreport',
            'website' => 'misc',
            default => 'misc',
        };
    }

    /**
     * Generate a unique citation key.
     */
    private function generateKey(Reference $reference): string
    {
        $authors = $reference->authors ?? [];
        $firstAuthor = !empty($authors) ? $authors[0] : 'Unknown';

        // Get last name
        $parts = preg_split('/\s+/', trim($firstAuthor));
        $lastName = end($parts);
        $lastName = preg_replace('/[^a-zA-Z]/', '', $lastName);

        $year = $reference->year ?? 'nodate';

        // First word of title
        $titleParts = preg_split('/\s+/', trim($reference->title));
        $titleWord = preg_replace('/[^a-zA-Z]/', '', $titleParts[0] ?? 'untitled');

        return strtolower($lastName . $year . $titleWord);
    }

    /**
     * Build fields array from reference.
     */
    private function buildFields(Reference $reference): array
    {
        $fields = [];

        // Title
        $fields['title'] = $reference->title;

        // Authors
        if (!empty($reference->authors)) {
            $fields['author'] = implode(' and ', $reference->authors);
        }

        // Year
        if ($reference->year) {
            $fields['year'] = $reference->year;
        }

        // Journal/Book specific
        if ($reference->journal_name) {
            $fields['journal'] = $reference->journal_name;
        }

        if ($reference->publisher) {
            $fields['publisher'] = $reference->publisher;
        }

        if ($reference->volume) {
            $fields['volume'] = $reference->volume;
        }

        if ($reference->issue) {
            $fields['number'] = $reference->issue;
        }

        if ($reference->pages) {
            $fields['pages'] = $reference->pages;
        }

        if ($reference->edition) {
            $fields['edition'] = $reference->edition;
        }

        // Identifiers
        if ($reference->doi) {
            $fields['doi'] = $reference->doi;
        }

        if ($reference->isbn) {
            $fields['isbn'] = $reference->isbn;
        }

        if ($reference->url) {
            $fields['url'] = $reference->url;
        }

        // Abstract
        if ($reference->abstract) {
            $fields['abstract'] = $reference->abstract;
        }

        return $fields;
    }

    /**
     * Format fields as BibTeX string.
     */
    private function formatFields(array $fields): string
    {
        $lines = [];
        foreach ($fields as $key => $value) {
            // Escape special characters
            $value = $this->escapeValue($value);
            $lines[] = "  {$key} = {{$value}}";
        }
        return implode(",\n", $lines);
    }

    /**
     * Escape special BibTeX characters.
     */
    private function escapeValue(string $value): string
    {
        // Escape special LaTeX characters
        $replacements = [
            '&' => '\&',
            '%' => '\%',
            '$' => '\$',
            '#' => '\#',
            '_' => '\_',
            '{' => '\{',
            '}' => '\}',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $value);
    }
}
