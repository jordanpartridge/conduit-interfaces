<?php

declare(strict_types=1);

use JordanPartridge\ConduitInterfaces\AbstractConduitCommand;
use JordanPartridge\ConduitInterfaces\Commands\ExampleCommand;
use JordanPartridge\ConduitInterfaces\Interfaces\DisplaysData;

it('lists the terminal, json and table formats', function () {
    expect(AbstractConduitCommand::getAvailableFormats())
        ->toHaveKeys(['terminal', 'json', 'table']);
});

it('ships an example command that implements DisplaysData', function () {
    $command = new ExampleCommand;

    expect($command)->toBeInstanceOf(DisplaysData::class)
        ->and($command->getName())->toBe('interfaces:example');
});

it('returns example rows with the expected columns', function () {
    $rows = (new ExampleCommand)->getData();

    expect($rows)->toHaveCount(3);

    foreach ($rows as $row) {
        expect($row)->toHaveKeys(['name', 'status', 'version', 'description']);
    }
});
