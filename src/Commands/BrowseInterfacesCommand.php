<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Commands;

use JordanPartridge\ConduitInterfaces\AbstractConduitCommand;
use function Laravel\Prompts\info;
use function Laravel\Prompts\select;
use function Laravel\Prompts\table;
use function Laravel\Prompts\confirm;

/**
 * Interactive interface browser using Laravel Prompts
 * 
 * Usage:
 * conduit interfaces:browse
 * conduit interfaces:browse --format=json (for automation)
 */
class BrowseInterfacesCommand extends AbstractConduitCommand
{
    protected $signature = 'interfaces:browse {--format=terminal} {--output=}';
    protected $description = 'Browse available Conduit interfaces interactively';

    /**
     * Get interface data for browsing
     */
    public function getData(): array
    {
        return [
            [
                'interface' => 'DisplaysData',
                'type' => 'Contract',
                'purpose' => 'Universal output format contract',
                'methods' => 'getData(), outputTerminal(), outputJson(), outputTable()',
                'file' => 'src/Interfaces/DisplaysData.php'
            ],
            [
                'interface' => 'FormatsAsJson',
                'type' => 'Contract', 
                'purpose' => 'JSON output formatting contract',
                'methods' => 'outputJson()',
                'file' => 'src/Interfaces/FormatsAsJson.php'
            ],
            [
                'interface' => 'FormatsAsJsonTrait',
                'type' => 'Trait',
                'purpose' => 'JSON formatting implementation',
                'methods' => 'outputJson(), filterFields()',
                'file' => 'src/Traits/FormatsAsJsonTrait.php'
            ],
            [
                'interface' => 'FormatsAsTableTrait', 
                'type' => 'Trait',
                'purpose' => 'Table formatting with Laravel Prompts',
                'methods' => 'outputTable(), outputInteractiveTable(), outputSimpleTable()',
                'file' => 'src/Traits/FormatsAsTableTrait.php'
            ],
            [
                'interface' => 'AbstractConduitCommand',
                'type' => 'Abstract Class',
                'purpose' => 'Universal command foundation',
                'methods' => 'handle(), getData(), outputTerminal(), getAvailableFormats()',
                'file' => 'src/AbstractConduitCommand.php'
            ]
        ];
    }

    /**
     * Interactive terminal output with Laravel Prompts
     */
    public function outputTerminal(array $data): int
    {
        info('🎯 Conduit Universal Interface System');
        
        $this->line('');
        $this->line('Available interfaces and their purposes:');
        $this->line('');

        // Show summary table
        table(
            headers: ['Interface', 'Type', 'Purpose'],
            rows: array_map(fn($item) => [
                $item['interface'],
                $item['type'],
                $item['purpose']
            ], $data)
        );

        $this->line('');

        // Interactive browsing
        if ($this->shouldShowInteractiveMode()) {
            return $this->runInteractiveMode($data);
        }

        $this->showQuickHelp();
        return 0;
    }

    /**
     * Run interactive interface browsing mode
     */
    private function runInteractiveMode(array $data): int
    {
        while (true) {
            $choice = select(
                label: 'What would you like to explore?',
                options: [
                    'details' => '🔍 View detailed interface information',
                    'example' => '🚀 See usage examples',
                    'files' => '📁 Browse interface files',
                    'formats' => '🎨 Test output formats',
                    'exit' => '❌ Exit'
                ],
                default: 'details'
            );

            match($choice) {
                'details' => $this->showDetailedView($data),
                'example' => $this->showExamples(),
                'files' => $this->showFiles($data),
                'formats' => $this->demoFormats(),
                'exit' => null
            };

            if ($choice === 'exit') {
                break;
            }

            $this->line('');
        }

        info('👋 Happy coding with Conduit interfaces!');
        return 0;
    }

    /**
     * Show detailed interface information
     */
    private function showDetailedView(array $data): void
    {
        $interfaceChoice = select(
            label: 'Which interface would you like to explore?',
            options: array_combine(
                array_column($data, 'interface'),
                array_map(fn($item) => "{$item['interface']} ({$item['type']})", $data)
            )
        );

        $selected = collect($data)->firstWhere('interface', $interfaceChoice);
        
        info("🔍 {$selected['interface']} Details");
        
        table(
            headers: ['Property', 'Value'],
            rows: [
                ['Type', $selected['type']],
                ['Purpose', $selected['purpose']],
                ['Methods', $selected['methods']],
                ['File Location', $selected['file']]
            ]
        );
    }

    /**
     * Show usage examples
     */
    private function showExamples(): void
    {
        info('🚀 Usage Examples');
        
        $this->line('');
        $this->comment('1. Extending AbstractConduitCommand:');
        $this->line('   class MyCommand extends AbstractConduitCommand');
        $this->line('   {');
        $this->line('       public function getData(): array { return [...]; }');
        $this->line('       public function outputTerminal(array $data): int { ... }');
        $this->line('   }');
        
        $this->line('');
        $this->comment('2. Using traits directly:');
        $this->line('   use FormatsAsJsonTrait, FormatsAsTableTrait;');
        
        $this->line('');
        $this->comment('3. Testing different formats:');
        $this->line('   conduit my:command --format=json');
        $this->line('   conduit my:command --format=table');
        $this->line('   conduit my:command | jq \'.[].name\'');
    }

    /**
     * Show interface file locations
     */
    private function showFiles(array $data): void
    {
        info('📁 Interface Files');
        
        table(
            headers: ['Interface', 'File Path'],
            rows: array_map(fn($item) => [
                $item['interface'],
                $item['file']
            ], $data)
        );
        
        $this->line('');
        $this->comment('💡 Tip: These files are in the conduit-components/interfaces/ directory');
    }

    /**
     * Demo different output formats
     */
    private function demoFormats(): void
    {
        info('🎨 Output Format Demo');
        
        $this->line('');
        $this->comment('Available formats for every Conduit command:');
        
        table(
            headers: ['Format', 'Usage', 'Best For'],
            rows: [
                ['terminal', 'Default interactive display', 'Human reading'],
                ['json', '--format=json or piped output', 'Automation & jq'],
                ['table', '--format=table', 'Data analysis'],
                ['file', '--output=file.json', 'Reports & exports']
            ]
        );
        
        $this->line('');
        
        if (confirm('Would you like to see a live demo?', true)) {
            $this->line('');
            $this->comment('🔴 Running: conduit interfaces:example --format=json');
            $this->call('interfaces:example', ['--format' => 'json']);
            
            $this->line('');
            $this->comment('🔴 Running: conduit interfaces:example --format=table');  
            $this->call('interfaces:example', ['--format' => 'table']);
        }
    }

    /**
     * Check if we should show interactive mode
     */
    private function shouldShowInteractiveMode(): bool
    {
        // Don't show interactive mode if piped or explicitly requested non-interactive
        return !$this->isPipedOutput() && !$this->option('no-interaction');
    }

    /**
     * Show quick help information
     */
    private function showQuickHelp(): void
    {
        $this->info('💡 Quick Help:');
        $this->line('   Run without piping for interactive mode');
        $this->line('   --format=json    Export as JSON');
        $this->line('   --format=table   Show as data table');
        $this->line('   --output=file    Save to file');
    }
}