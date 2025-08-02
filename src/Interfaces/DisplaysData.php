<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Interfaces;

/**
 * Core interface for commands that display data in multiple formats
 * 
 * Addresses Issue #85: Universal output format interfaces for all commands
 */
interface DisplaysData extends FormatsAsJson
{
    /**
     * Get the data to be displayed/formatted
     */
    public function getData(): array;
    
    /**
     * Format output for terminal (human-readable)
     */
    public function outputTerminal(array $data): int;
    
    /**
     * Format output as table
     */
    public function outputTable(array $data): int;
    
    /**
     * Get available output formats
     */
    public static function getAvailableFormats(): array;
}