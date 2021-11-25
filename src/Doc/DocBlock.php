<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Doc;

class DocBlock
{
    private $content;

    private $lines;

    /** @var string|null */
    private $summary;

    public function __construct(string $content)
    {
        $this->content = $content;
        $this->lines = explode("\n", $this->content);

        $summary = trim($this->lines[1], "/*\n ");

        if (! str_starts_with($summary, '@') && mb_strlen($summary) > 1) {
            $this->summary = $summary;
        }
    }

    /**
     * @param array<string>|string $lines
     */
    public static function make($lines = []): self
    {
        $linesString = '';

        foreach ((array) $lines as $line) {
            $linesString .= "\n    * " . $line;
        }

        return new self("    /**" . $linesString . "\n     */");
    }

    public function setSummary(string $summary): self
    {
        if ($this->summary === null) {
            $this->insertLine(1, $summary);
        }

        $this->summary = $summary;

        return $this;
    }

    public function hasSummary(): bool
    {
        return $this->summary !== null;
    }

    public function content(): string
    {
        return $this->content;
    }

    private function insertLine(int $index, string $line): void
    {
        $line = $this->wrapLine($line);

        $lines = [];
        $cursor = 0;

        foreach ($this->lines as $key => $suchLine) {
            if ($key === $index) {
                $lines[$cursor] = $line;
                $cursor++;
            }

            $lines[$cursor] = $suchLine;

            $cursor++;
        }

        $this->lines = $lines;
        $this->regenerate();
    }

    private function regenerate(): void
    {
        $this->content = implode("\n", $this->lines);
    }

    private function wrapLine(string $line): string
    {
        return '     ' . '* ' . $line;
    }
}
