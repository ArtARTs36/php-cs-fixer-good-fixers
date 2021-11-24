<?php

interface File
{
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
