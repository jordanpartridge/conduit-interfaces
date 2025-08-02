<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces;

use JordanPartridge\ConduitInterfaces\Interfaces\DisplaysData;
use JordanPartridge\ConduitInterfaces\Traits\FormatsAsJsonTrait;
use JordanPartridge\ConduitInterfaces\Traits\FormatsAsTableTrait;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Input\InputOption;

/**
 * AbstractConduitCommand with Universal Output Format Interfaces
 * 
 * Addresses Issue #85: RFC: Universal output format interfaces for all commands
 * 
 * This abstract class ensures all Conduit commands support consistent output formats:
 * - terminal: Human-readable decorative output (default)
 * - json: Machine-readable JSON output
 * - table: Tabular format for data display
 */
abstract class AbstractConduitCommand extends Command implements DisplaysData
{
    use FormatsAsJsonTrait, FormatsAsTableTrait;

    /**
     * Add universal format options to all extending commands
     */
    protected function getOptions(): array
    {
        $parentOptions = method_exists(parent::class, 'getOptions') ? parent::getOptions() : [];
        
        return array_merge($parentOptions, [
            ['format', null, InputOption::VALUE_OPTIONAL, 'Output format (terminal, json, table)', 'terminal'],
            ['output', null, InputOption::VALUE_OPTIONAL, 'Write output to file instead of stdout'],
        ]);
    }

    /**
     * Enhanced handle method that routes to appropriate output formatter
     */
    public function handle(): int
    {
        $data = $this->getData();
        $format = $this->option('format') ?? 'terminal';
        
        // Auto-detect piped output and default to json for better composability
        if ($this->isPipedOutput() && $format === 'terminal') {
            $format = 'json';
        }

        return match($format) {
            'json' => $this->outputJson($data),
            'table' => $this->outputTable($data),
            default => $this->outputTerminal($data)
        };
    }

    /**
     * Get the data to be formatted - must be implemented by concrete commands
     */
    abstract public function getData(): array;

    /**
     * Format output for terminal (human-readable, decorative)
     * Must be implemented by concrete commands for custom terminal display
     */
    abstract public function outputTerminal(array $data): int;

    /**
     * Get available output formats
     */
    public static function getAvailableFormats(): array
    {
        return [
            'terminal' => 'Human-readable terminal output (default)',
            'json' => 'Machine-readable JSON format',
            'table' => 'Tabular display format',
        ];
    }

    /**
     * Detect if output is being piped
     */
    protected function isPipedOutput(): bool
    {
        return !posix_isatty(STDOUT);
    }
}