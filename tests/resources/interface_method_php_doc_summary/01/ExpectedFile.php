<?php

interface File
{
    public const CONST_ONE = 1;
    public const CONST_TWO = 2;

    /**
     * Find File
     */
    public function find(): void;

    /**
     * Show.
     */
    public function show(): void;

    /**
     * Delete File
     */
    public function delete(): void;

    /**
     * Content File
     */
    public function content(): string;

    /**
     * Size File
     */
    public function size(): int;

    /**
     * Method has params but without summary.
     * @param string $param
     */
    public function methodHasParamsButWithoutSummary(string $param): void;
}
