<?php

interface File
{
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
