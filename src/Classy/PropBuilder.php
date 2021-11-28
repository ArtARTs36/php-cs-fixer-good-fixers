<?php

namespace ArtARTs36\PhpCsFixerGoodFixers\Classy;

use PhpCsFixer\Tokenizer\Token;

class PropBuilder
{
    protected $tokens = [];

    protected $name;

    protected $visible;

    protected $value = [];

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->visible = new Token([T_PUBLIC, 'public']);
    }

    public function setProtected(): self
    {
        $this->visible = new Token([T_PROTECTED, 'protected']);

        return $this;
    }

    public function setValueString(string $value): self
    {
        $this->value = [
            new Token([T_WHITESPACE, ' ']),
            new Token([T_STRING, '=']),
            new Token([T_WHITESPACE, ' ']),
            new Token([T_CONSTANT_ENCAPSED_STRING, "'$value'"]),
        ];

        return $this;
    }

    /**
     * @return array<Token>
     */
    public function getValue(): array
    {
        return $this->value;
    }

    public function buildLine(): array
    {
        $parts = [
            new Token([T_WHITESPACE, '    ']),
            $this->visible,
            new Token([T_WHITESPACE, ' ']),
            new Token([T_VARIABLE, '$' . $this->name]),
        ];

        if (count($this->value) > 0) {
            $parts = array_merge($parts, $this->value);
        }

        $parts[] = new Token(';');

        return array_filter($parts);
    }
}
