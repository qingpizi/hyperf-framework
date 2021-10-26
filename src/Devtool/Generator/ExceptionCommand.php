<?php
declare(strict_types=1);
/**
 * Created by PhpStorm
 * User: qingpizi
 * Date: 2021/10/10
 * Time: 下午11:17
 */

namespace Qingpizi\HyperfFramework\Devtool\Generator;

use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    protected function getNamespace($name)
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
