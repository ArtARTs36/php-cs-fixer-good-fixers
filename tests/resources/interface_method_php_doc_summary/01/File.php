<?php

interface File
{
    public const CONST_ONE = 1;
    public const CONST_TWO = 2;

    public function find(): void;

    /**
     * Show.
     */
    public function show(): void;

    public function delete(): void;

    public function content(): string;

    public function size(): int;

    /**
     * @param string $param
     */
    public function methodHasParamsButWithoutSummary(string $param): void;
}
