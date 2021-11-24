<?php

interface File
{
    public function find(): void;

    /**
     * Show.
     */
    public function show(): void;

    public function delete(): void;

    /**
     * @param string $param
     */
    public function methodHasParamsButWithoutSummary(string $param): void;
}
