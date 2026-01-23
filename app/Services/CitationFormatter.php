<?php

namespace App\Services;

use App\Models\Reference;

class CitationFormatter
{
    /**
     * Available citation styles.
     */
    public const STYLES = [
        'apa7' => 'APA 7th Edition',
        'mla9' => 'MLA 9th Edition',
        'chicago' => 'Chicago 17th Edition',
        'ieee' => 'IEEE',
        'harvard' => 'Harvard',
        'thai-cu' => 'Chulalongkorn University',
        'thai-tu' => 'Thammasat University',
        'thai-mu' => 'Mahidol University',
    ];

    public function format(Reference $reference, string $style = 'apa7'): string
    {
        return match ($style) {
            'apa7' => $this->formatApa7($reference),
            'mla9' => $this->formatMla9($reference),
            'chicago' => $this->formatChicago($reference),
            'ieee' => $this->formatIeee($reference),
            'harvard' => $this->formatHarvard($reference),
            'thai-cu', 'thai-tu', 'thai-mu' => $this->formatThaiUniversity($reference, $style),
            default => $this->formatApa7($reference),
        };
    }

    /**
     * Format a reference for in-text citation.
     */
    public function formatInText(Reference $reference, string $style = 'apa7'): string
    {
        return match ($style) {
            'apa7', 'harvard' => $this->formatInTextApa($reference),
            'mla9' => $this->formatInTextMla($reference),
            'chicago' => $this->formatInTextChicago($reference),
            'ieee' => $this->formatInTextIeee($reference),
            'thai-cu', 'thai-tu', 'thai-mu' => $this->formatInTextThai($reference),
            default => $this->formatInTextApa($reference),
        };
    }

    /**
     * Format a reference array (not model) in the specified citation style.
     */
    public function formatArray(array $data, string $style = 'apa7'): string
    {
        $reference = new Reference($data);
        return $this->format($reference, $style);
    }

    /**
     * APA 7th Edition format.
     * Author, A. A., & Author, B. B. (Year). Title of work. Publisher. DOI/URL
     */
    private function formatApa7(Reference $reference): string
    {
        $parts = [];

        // Authors
        $authors = $this->formatAuthorsApa($reference->authors ?? []);
        if ($authors) {
            $parts[] = $authors;
        }

        // Year
        $yearStr = $reference->year ?? 'n.d.';
        if ($this->shouldUseThaiYear($reference)) {
            $yearStr = $this->convertToThaiYear($yearStr);
        }
        if ($reference->year && $reference->year_suffix) {
            $yearStr .= $reference->year_suffix;
        }
        $year = "({$yearStr})";
        $parts[] = $year;

        // Title (italicized for standalone works)
        $title = $reference->title;
        if (in_array($reference->type, ['book', 'thesis', 'report', 'website', 'other'])) {
            $title = "<em>{$title}</em>";
        }
        $parts[] = $title . '.';

        // Journal info for articles
        if ($reference->type === 'journal' && $reference->journal_name) {
            $journal = "<em>{$reference->journal_name}</em>";
            if ($reference->volume) {
                $journal .= ", <em>{$reference->volume}</em>";
                if ($reference->issue) {
                    $journal .= "({$reference->issue})";
                }
            }
            if ($reference->pages) {
                $journal .= ", {$reference->pages}";
            }
            $parts[] = $journal . '.';
        }

        // Publisher
        if ($reference->publisher && in_array($reference->type, ['book', 'report'])) {
            $parts[] = $reference->publisher . '.';
        }

        // DOI/URL
        if ($reference->doi) {
            $parts[] = "https://doi.org/{$reference->doi}";
        } elseif ($reference->url) {
            $parts[] = $reference->url;
        }

        return implode(' ', $parts);
    }

    /**
     * MLA 9th Edition format.
     * Author. "Title." Container, vol., no., Publisher, Year, pp.
     */
    private function formatMla9(Reference $reference): string
    {
        $parts = [];

        // Authors
        $authors = $this->formatAuthorsMla($reference->authors ?? []);
        if ($authors) {
            $parts[] = $authors;
        }

        // Title
        $title = $reference->title;
        if (in_array($reference->type, ['journal', 'conference', 'website'])) {
            $parts[] = "\"{$title}.\"";
        } else {
            $parts[] = "<em>{$title}</em>.";
        }

        // Container (Journal name)
        if ($reference->journal_name) {
            $container = "<em>{$reference->journal_name}</em>,";
            $parts[] = $container;
        }

        // Volume and Issue
        if ($reference->volume) {
            $vol = "vol. {$reference->volume},";
            if ($reference->issue) {
                $vol = "vol. {$reference->volume}, no. {$reference->issue},";
            }
            $parts[] = $vol;
        }

        // Publisher
        if ($reference->publisher) {
            $parts[] = $reference->publisher . ',';
        }

        // Year
        if ($reference->year) {
            $yearStr = $reference->year;
            if ($reference->year_suffix) {
                $yearStr .= $reference->year_suffix;
            }
            $parts[] = $yearStr . ',';
        }

        // Pages
        if ($reference->pages) {
            $parts[] = "pp. {$reference->pages}.";
        }

        $result = implode(' ', $parts);
        // Clean up trailing comma
        return rtrim($result, ', ') . '.';
    }

    /**
     * Chicago 17th Edition format.
     */
    private function formatChicago(Reference $reference): string
    {
        $parts = [];

        // Authors
        $authors = $this->formatAuthorsChicago($reference->authors ?? []);
        if ($authors) {
            $parts[] = $authors;
        }

        // Title
        $title = $reference->title;
        if (in_array($reference->type, ['book', 'thesis', 'report'])) {
            $parts[] = "<em>{$title}</em>.";
        } else {
            $parts[] = "\"{$title}.\"";
        }

        // Journal
        if ($reference->journal_name) {
            $journal = "<em>{$reference->journal_name}</em>";
            if ($reference->volume) {
                $journal .= " {$reference->volume}";
                if ($reference->issue) {
                    $journal .= ", no. {$reference->issue}";
                }
            }
            if ($reference->year) {
                $yearStr = $reference->year;
                if ($this->shouldUseThaiYear($reference)) {
                    $yearStr = $this->convertToThaiYear($yearStr);
                }
                if ($reference->year_suffix) {
                    $yearStr .= $reference->year_suffix;
                }
                $journal .= " ({$yearStr})";
            }
            if ($reference->pages) {
                $journal .= ": {$reference->pages}";
            }
            $parts[] = $journal . '.';
        } else {
            // Publisher and Year for books
            $pubInfo = [];
            if ($reference->publisher) {
                $pubInfo[] = $reference->publisher;
            }
            if ($reference->year) {
                $yearStr = $reference->year;
                if ($reference->year_suffix) {
                    $yearStr .= $reference->year_suffix;
                }
                $pubInfo[] = $yearStr;
            }
            if (!empty($pubInfo)) {
                $parts[] = implode(', ', $pubInfo) . '.';
            }
        }

        return implode(' ', $parts);
    }

    /**
     * IEEE format.
     * [1] A. Author, "Title," Journal, vol. X, no. X, pp. X-X, Year.
     */
    private function formatIeee(Reference $reference): string
    {
        $parts = [];

        // Authors (initials first)
        $authors = $this->formatAuthorsIeee($reference->authors ?? []);
        if ($authors) {
            $parts[] = $authors . ',';
        }

        // Title
        $parts[] = "\"{$reference->title},\"";

        // Journal
        if ($reference->journal_name) {
            $parts[] = "<em>{$reference->journal_name}</em>,";
        }

        // Volume
        if ($reference->volume) {
            $parts[] = "vol. {$reference->volume},";
        }

        // Issue
        if ($reference->issue) {
            $parts[] = "no. {$reference->issue},";
        }

        // Pages
        if ($reference->pages) {
            $parts[] = "pp. {$reference->pages},";
        }

        // Year
        if ($reference->year) {
            $yearStr = $reference->year;
            if ($reference->year_suffix) {
                $yearStr .= $reference->year_suffix;
            }
            $parts[] = $yearStr . '.';
        }

        return implode(' ', $parts);
    }

    /**
     * Harvard format.
     */
    private function formatHarvard(Reference $reference): string
    {
        // Harvard is similar to APA
        return $this->formatApa7($reference);
    }

    /**
     * Thai University format.
     */
    private function formatThaiUniversity(Reference $reference, string $style): string
    {
        $parts = [];

        // Authors (Thai format: Name Surname)
        $authors = $this->formatAuthorsThai($reference->authors ?? []);
        if ($authors) {
            $parts[] = $authors . '.';
        }

        // Year (with พ.ศ. conversion if applicable)
        if ($reference->year) {
            $thaiYear = $this->convertToThaiYear($reference->year);
            if ($reference->year_suffix) {
                $thaiYear .= $reference->year_suffix;
            }
            $parts[] = "({$thaiYear}).";
        }

        // Title (italicized for standalone works)
        $title = $reference->title;
        if (in_array($reference->type, ['book', 'thesis', 'report', 'website', 'other'])) {
            $title = "<em>{$title}</em>";
        } else {
            $title = "{$title}";
        }
        $parts[] = $title . '.';

        // Journal or Publisher
        if ($reference->journal_name) {
            $journal = "<em>{$reference->journal_name}</em>";
            if ($reference->volume) {
                $journal .= ", {$reference->volume}";
                if ($reference->issue) {
                    $journal .= "({$reference->issue})";
                }
            }
            if ($reference->pages) {
                $journal .= ", {$reference->pages}";
            }
            $parts[] = $journal . '.';
        } elseif ($reference->publisher) {
            $parts[] = $reference->publisher . '.';
        }

        return implode(' ', $parts);
    }

    /**
     * Format authors for APA style.
     * Last, F. M., & Last, F. M.
     */
    private function formatAuthorsApa(array $authors): string
    {
        if (empty($authors)) {
            return '';
        }

        $formatted = [];
        foreach ($authors as $author) {
            $formatted[] = $this->formatSingleAuthorApa($author);
        }

        $count = count($formatted);
        if ($count === 1) {
            return $formatted[0];
        } elseif ($count === 2) {
            return $formatted[0] . ' & ' . $formatted[1];
        } elseif ($count <= 20) {
            $last = array_pop($formatted);
            return implode(', ', $formatted) . ', & ' . $last;
        } else {
            // More than 20 authors: first 19, ..., last
            $first19 = array_slice($formatted, 0, 19);
            $last = end($formatted);
            return implode(', ', $first19) . ', ... ' . $last;
        }
    }

    /**
     * Format a single author for APA.
     */
    private function formatSingleAuthorApa(string $author): string
    {
        if ($this->isThai($author)) {
            return $author;
        }

        $parts = preg_split('/\s+/', trim($author));
        if (count($parts) === 1) {
            return $parts[0];
        }
        $lastName = array_pop($parts);
        $initials = array_map(fn($p) => strtoupper(substr($p, 0, 1)) . '.', $parts);
        return $lastName . ', ' . implode(' ', $initials);
    }

    /**
     * Format authors for MLA style.
     * Last, First. Last, First, and Last, First.
     */
    private function formatAuthorsMla(array $authors): string
    {
        if (empty($authors)) {
            return '';
        }

        $count = count($authors);
        if ($count === 1) {
            return $this->formatSingleAuthorMla($authors[0], true);
        } elseif ($count === 2) {
            return $this->formatSingleAuthorMla($authors[0], true) . ', and ' .
                $this->formatSingleAuthorMla($authors[1], false);
        } else {
            return $this->formatSingleAuthorMla($authors[0], true) . ', et al.';
        }
    }

    /**
     * Format a single author for MLA.
     */
    private function formatSingleAuthorMla(string $author, bool $invertFirst): string
    {
        if ($this->isThai($author)) {
            return $author;
        }

        $parts = preg_split('/\s+/', trim($author));
        if (count($parts) === 1) {
            return $parts[0];
        }
        $lastName = array_pop($parts);
        $firstName = implode(' ', $parts);

        if ($invertFirst) {
            return $lastName . ', ' . $firstName . '.';
        }
        return $firstName . ' ' . $lastName;
    }

    /**
     * Format authors for Chicago style.
     */
    private function formatAuthorsChicago(array $authors): string
    {
        return $this->formatAuthorsMla($authors); // Similar format
    }

    /**
     * Format authors for IEEE style.
     * F. M. Last
     */
    private function formatAuthorsIeee(array $authors): string
    {
        if (empty($authors)) {
            return '';
        }

        $formatted = [];
        foreach ($authors as $author) {
            if ($this->isThai($author)) {
                $formatted[] = $author;
                continue;
            }

            $parts = preg_split('/\s+/', trim($author));
            if (count($parts) === 1) {
                $formatted[] = $parts[0];
            } else {
                $lastName = array_pop($parts);
                $initials = array_map(fn($p) => strtoupper(substr($p, 0, 1)) . '.', $parts);
                $formatted[] = implode(' ', $initials) . ' ' . $lastName;
            }
        }

        $count = count($formatted);
        if ($count <= 6) {
            if ($count === 1) {
                return $formatted[0];
            }
            $last = array_pop($formatted);
            return implode(', ', $formatted) . ' and ' . $last;
        } else {
            return $formatted[0] . ' et al.';
        }
    }

    /**
     * Format authors for Thai style.
     */
    private function formatAuthorsThai(array $authors): string
    {
        if (empty($authors)) {
            return '';
        }

        // Thai format typically uses full names
        $count = count($authors);
        if ($count === 1) {
            return $authors[0];
        } elseif ($count === 2) {
            return $authors[0] . ' และ ' . $authors[1];
        } else {
            return $authors[0] . ' และคณะ';
        }
    }

    /**
     * Convert Western year to Thai Buddhist year.
     */
    private function convertToThaiYear(string $year): string
    {
        if (is_numeric($year) && strlen($year) === 4) {
            $westernYear = (int) $year;
            // Only convert if it looks like a Western year
            if ($westernYear >= 1900 && $westernYear <= 2100) {
                return (string) ($westernYear + 543);
            }
        }
        return $year;
    }

    /**
     * Check if a string contains Thai characters.
     */
    private function isThai(string $text): bool
    {
        return preg_match('/[\x{0E00}-\x{0E7F}]/u', $text);
    }

    /**
     * Format in-text citation for APA style.
     * (Last, Year)
     */
    private function formatInTextApa(Reference $reference): string
    {
        $authors = $reference->authors ?? [];
        $yearStr = $reference->year ?? 'n.d.';
        if ($reference->year && $reference->year_suffix) {
            $yearStr .= $reference->year_suffix;
        }

        if (empty($authors)) {
            $title = strlen($reference->title) > 30 ? substr($reference->title, 0, 30) . '...' : $reference->title;
            return "({$title}, {$yearStr})";
        }

        $formattedAuthor = '';
        $count = count($authors);

        if ($count === 1) {
            $formattedAuthor = $this->getInTextName($authors[0]);
        } elseif ($count === 2) {
            $formattedAuthor = $this->getInTextName($authors[0]) . ' & ' . $this->getInTextName($authors[1]);
        } else {
            $formattedAuthor = $this->getInTextName($authors[0]) . ' et al.';
        }

        return "({$formattedAuthor}, {$yearStr})";
    }

    /**
     * Format in-text citation for MLA style.
     * (Last)
     */
    private function formatInTextMla(Reference $reference): string
    {
        $authors = $reference->authors ?? [];
        if (empty($authors)) {
            $title = strlen($reference->title) > 30 ? substr($reference->title, 0, 30) . '...' : $reference->title;
            return "({$title})";
        }

        $count = count($authors);
        if ($count === 1) {
            $name = $this->getInTextName($authors[0]);
        } elseif ($count === 2) {
            $name = $this->getInTextName($authors[0]) . ' and ' . $this->getInTextName($authors[1]);
        } else {
            $name = $this->getInTextName($authors[0]) . ' et al.';
        }

        return "({$name})";
    }

    /**
     * Format in-text citation for Chicago style.
     * (Last Year)
     */
    private function formatInTextChicago(Reference $reference): string
    {
        $authors = $reference->authors ?? [];
        $yearStr = $reference->year ?? 'n.d.';
        if ($reference->year && $reference->year_suffix) {
            $yearStr .= $reference->year_suffix;
        }

        if (empty($authors)) {
            return "({$reference->title} {$yearStr})";
        }

        $count = count($authors);
        if ($count === 1) {
            $name = $this->getInTextName($authors[0]);
        } elseif ($count === 2) {
            $name = $this->getInTextName($authors[0]) . ' and ' . $this->getInTextName($authors[1]);
        } else {
            $name = $this->getInTextName($authors[0]) . ' et al.';
        }

        return "({$name} {$yearStr})";
    }

    /**
     * Format in-text citation for IEEE style.
     * [Number] - Use id as a placeholder for number if not in a sequence
     */
    private function formatInTextIeee(Reference $reference): string
    {
        return "[{$reference->id}]";
    }

    /**
     * Format in-text citation for Thai University style.
     * (Name Surname, Year)
     */
    private function formatInTextThai(Reference $reference): string
    {
        $authors = $reference->authors ?? [];
        $yearStr = $reference->year ? $this->convertToThaiYear($reference->year) : 'ม.ป.ป.';
        if ($reference->year && $reference->year_suffix) {
            $yearStr .= $reference->year_suffix;
        }

        if (empty($authors)) {
            return "({$reference->title}, {$yearStr})";
        }

        $count = count($authors);
        if ($count === 1) {
            $name = $authors[0];
        } elseif ($count === 2) {
            $name = $authors[0] . ' และ ' . $authors[1];
        } else {
            $name = $authors[0] . ' และคณะ';
        }

        return "({$name}, {$yearStr})";
    }

    /**
     * Get the name for in-text citation (Surname for Western, Full Name for Thai).
     */
    private function getInTextName(string $author): string
    {
        if ($this->isThai($author)) {
            return $author;
        }

        $parts = preg_split('/\s+/', trim($author));
        return array_pop($parts);
    }

    /**
     * Check if a reference should use Thai year (พ.ศ.).
     */
    private function shouldUseThaiYear(Reference $reference): bool
    {
        // Check if any field contains Thai characters
        $fields = [
            $reference->title,
            $reference->publisher,
            $reference->journal_name,
            ...(is_array($reference->authors) ? $reference->authors : [])
        ];

        foreach ($fields as $field) {
            if ($field && preg_match('/[\x{0E00}-\x{0E7F}]/u', $field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get available styles.
     */
    public function getStyles(): array
    {
        return self::STYLES;
    }
}
