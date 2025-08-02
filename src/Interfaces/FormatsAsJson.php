<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Interfaces;

/**
 * Interface for commands that can format output as JSON
 * 
 * Part of the universal output format system (Issue #85)
 */
interface FormatsAsJson
{
    /**
     * Format and output data as JSON
     */
    public function outputJson(array $data): int;
}