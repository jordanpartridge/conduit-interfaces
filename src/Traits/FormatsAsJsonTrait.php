<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Traits;

/**
 * Default implementation for JSON output formatting
 */
trait FormatsAsJsonTrait
{
    /**
     * Format and output data as JSON
     */
    public function outputJson(array $data): int
    {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        
        if ($this->option('output')) {
            file_put_contents($this->option('output'), $json);
            $this->info("JSON output written to: {$this->option('output')}");
        } else {
            $this->line($json);
        }
        
        return 0;
    }
}