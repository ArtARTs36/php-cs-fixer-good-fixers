<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Doc;

use PhpCsFixer\Tokenizer\Token;

class DocBlock
{
    private $content;

    private $lines;

    /** @var string|null */
    private $summary;

    private $withSpaces;

    public function __construct(string $content, bool $withSpaces = true)
    {
        $this->content = $content;
        $this->withSpaces = $withSpaces;
        $this->lines = explode("\n", $this->content);

        $summary = trim($this->lines[1], "/*\n ");

        if (! str_starts_with($summary, '@') && mb_strlen($summary) > 1) {
            $this->summary = $summary;
        }
    }

    public static function fromToken(Token $token): self
    {
        return new self($token->getContent());
    }

    public static function fromTokenForClass(Token $token): self
    {
        return new self($token->getContent(), false);
    }

    public function toToken(): Token
    {
        return new Token([
            T_DOC_COMMENT,
            $this->content(),
        ]);
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

    /**
     * @param array<string>|string $lines
     */
    public static function makeWithoutSpaces($lines = []): self
    {
        $linesString = '';

        foreach ((array) $lines as $line) {
            $linesString .= "\n * " . $line;
        }

        return new self("/**" . $linesString . "\n */", false);
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
        return ($this->withSpaces ? '     ' : ' ') . '* ' . $line;
    }
}
