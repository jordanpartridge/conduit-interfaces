<?php

declare(strict_types=1);

namespace JordanPartridge\ConduitInterfaces\Commands;

use Illuminate\Console\Command;

class InitCommand extends Command
{
    protected $signature = 'init';

    protected $description = 'Sample command for interfaces component';

    public function handle(): int
    {
        $this->info('🚀 interfaces component is working!');
        $this->line('This is a sample command. Implement your logic here.');
        
        return 0;
    }
}