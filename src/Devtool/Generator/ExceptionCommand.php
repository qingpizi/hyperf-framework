<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Devtool\Generator;

use Hyperf\Command\Annotation\Command;

/**
 * @Command
 */
#[Command]
class ExceptionCommand extends BaseGeneratorCommand
{
    public function __construct()
    {
        parent::__construct('easy:exception');
        $this->setDescription('Create a new exception class');
    }

    protected function getNamespace($name): string
    {
        return 'App\\Exception\\' . ucfirst($name) . 'Exception';
    }

    protected function buildClass($name)
    {
        $stub = file_get_contents(__DIR__ . '/stubs/exception.stub');
        return str_replace(
            [
                '%NAME%',
            ],
            [
                $name,
            ],
            $stub
        );
    }
}
