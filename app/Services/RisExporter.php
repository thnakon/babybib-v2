<?php

namespace App\Services;

use App\Models\Reference;
use Illuminate\Support\Collection;

class RisExporter
{
    /**
     * Export a single reference to RIS format.
     */
    public function export(Reference $reference): string
    {
        $lines = [];

        // Type
        $lines[] = 'TY  - ' . $this->mapTypeToRis($reference->type);

        // Title
        $lines[] = 'TI  - ' . $reference->title;

        // Authors
        if (!empty($reference->authors)) {
            foreach ($reference->authors as $author) {
                $lines[] = 'AU  - ' . $author;
            }
        }

        // Year
        if ($reference->year) {
            $lines[] = 'PY  - ' . $reference->year;
        }

        // Journal
        if ($reference->journal_name) {
            $lines[] = 'JO  - ' . $reference->journal_name;
            $lines[] = 'T2  - ' . $reference->journal_name;
        }

        // Publisher
        if ($reference->publisher) {
            $lines[] = 'PB  - ' . $reference->publisher;
        }

        // Volume
        if ($reference->volume) {
            $lines[] = 'VL  - ' . $reference->volume;
        }

        // Issue
        if ($reference->issue) {
            $lines[] = 'IS  - ' . $reference->issue;
        }

        // Pages
        if ($reference->pages) {
            $pages = $reference->pages;
            if (str_contains($pages, '-')) {
                [$start, $end] = explode('-', $pages, 2);
                $lines[] = 'SP  - ' . trim($start);
                $lines[] = 'EP  - ' . trim($end);
            } else {
                $lines[] = 'SP  - ' . $pages;
            }
        }

        // DOI
        if ($reference->doi) {
            $lines[] = 'DO  - ' . $reference->doi;
        }

        // ISBN
        if ($reference->isbn) {
            $lines[] = 'SN  - ' . $reference->isbn;
        }

        // URL
        if ($reference->url) {
            $lines[] = 'UR  - ' . $reference->url;
        }

        // Abstract
        if ($reference->abstract) {
            $lines[] = 'AB  - ' . $reference->abstract;
        }

        // Notes
        if ($reference->notes) {
            $lines[] = 'N1  - ' . $reference->notes;
        }

        // End of record
        $lines[] = 'ER  - ';

        return implode("\n", $lines) . "\n";
    }

    /**
     * Export multiple references to RIS format.
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
            'filename' => 'references_' . date('Y-m-d') . '.ris',
            'mime' => 'application/x-research-info-systems',
        ];
    }

    /**
     * Map our reference type to RIS type tag.
     */
    private function mapTypeToRis(string $type): string
    {
        return match ($type) {
            'book' => 'BOOK',
            'journal' => 'JOUR',
            'conference' => 'CONF',
            'thesis' => 'THES',
            'report' => 'RPRT',
            'website' => 'ELEC',
            default => 'GEN',
        };
    }
}
