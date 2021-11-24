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
     * Method has params but without summary.
     * @param string $param
     */
    public function methodHasParamsButWithoutSummary(string $param): void;
}
