<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Commands;

use JordanPartridge\ConduitInterfaces\AbstractConduitCommand;

/**
 * Example command demonstrating universal output formats
 * 
 * Usage:
 * conduit interfaces:example
 * conduit interfaces:example --format=json
 * conduit interfaces:example --format=table
 */
class ExampleCommand extends AbstractConduitCommand
{
    protected $signature = 'interfaces:example {--format=terminal} {--output=}';
    protected $description = 'Example command showing universal output formats';

    /**
     * Get sample data for demonstration
     */
    public function getData(): array
    {
        return [
            [
                'name' => 'Conduit Interfaces',
                'status' => 'active',
                'version' => '1.0.0',
                'description' => 'Universal output format foundation'
            ],
            [
                'name' => 'Developer Liberation',
                'status' => 'launching',
                'version' => '∞',
                'description' => 'Eliminating developer workflow pain'
            ],
            [
                'name' => 'Component Ecosystem',
                'status' => 'growing',
                'version' => '2.0.0',
                'description' => 'Modular CLI architecture'
            ]
        ];
    }

    /**
     * Custom terminal output with emojis and formatting
     */
    public function outputTerminal(array $data): int
    {
        $this->info('🎯 Conduit Universal Formats Demo');
        $this->line('');

        foreach ($data as $component) {
            $status = match($component['status']) {
                'active' => '✅',
                'launching' => '🚀',
                'growing' => '🌱',
                default => '❓'
            };
            
            $this->line("  {$status} <comment>{$component['name']}</comment> v{$component['version']}");
            $this->line("     {$component['description']}");
            $this->line('');
        }

        $this->info('💡 Try different formats:');
        $this->line('   --format=json    (for automation)');
        $this->line('   --format=table   (for data display)');
        $this->line('   | jq             (auto-detects piping!)');

        return 0;
    }
}