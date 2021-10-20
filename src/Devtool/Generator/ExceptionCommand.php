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
use Hyperf\Devtool\Generator\GeneratorCommand;

/**
 * @Command
 */
#[Command]
class ExceptionCommand extends GeneratorCommand
{
    public function __construct()
    {
        parent::__construct('qpz:exception');
        $this->setDescription('Create a new exception class');
    }

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/exception.stub';
    }

    protected function getDefaultNamespace(): string
    {
        return 'App\\Exception';
    }
}
