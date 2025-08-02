<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Traits;

use function Laravel\Prompts\table;

/**
 * Default implementation for table output formatting
 */
trait FormatsAsTableTrait
{
    /**
     * Format and output data as a table
     */
    public function outputTable(array $data): int
    {
        if (empty($data)) {
            $this->warn('No data to display');
            return 0;
        }

        if ($this->isPipedOutput()) {
            return $this->outputSimpleTable($data);
        }
        
        return $this->outputInteractiveTable($data);
    }
    
    /**
     * Interactive table display using Laravel Prompts
     */
    protected function outputInteractiveTable(array $data): int
    {
        $headers = array_keys(reset($data));
        $rows = array_map('array_values', $data);
        
        table($headers, $rows);
        
        return 0;
    }
    
    /**
     * Simple table display for piped usage
     */
    protected function outputSimpleTable(array $data): int
    {
        $headers = array_keys(reset($data));
        $this->line(implode("\t", $headers));
        
        foreach ($data as $row) {
            $this->line(implode("\t", array_values($row)));
        }
        
        return 0;
    }
    
    /**
     * Detect if output is being piped
     */
    protected function isPipedOutput(): bool
    {
        return !posix_isatty(STDOUT);
    }
}