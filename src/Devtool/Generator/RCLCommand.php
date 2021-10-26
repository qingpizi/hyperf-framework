<?php
declare(strict_types=1);
/**
 * Created by PhpStorm
 * User: qingpizi
 * Date: 2021/10/10
 * Time: 下午3:26
 */

namespace Qingpizi\HyperfFramework\Devtool\Generator;

use Hyperf\Command\Annotation\Command;
use Hyperf\Devtool\Generator\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @Command
 */
#[Command]
class RCLCommand extends BaseRCLGeneratorCommand
{
    
    protected $currentDiv = __DIR__;

    public function __construct()
    {
        parent::__construct('easy:rcl');
        $this->setDescription('Create new request class and new controller class and new logic class');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->executeHandle($input, $output, ['request', 'controller', 'logic']);
    }

    protected function buildClass($modelName, $moduleName, $actionName)
    {
        $stub = file_get_contents($this->currentDiv . '/stubs/rcl/' . $moduleName . '.stub');
        return str_replace(
            [
                '%MODEL_NAME%',
                '%ACTION_NAME%',
            ],
            [
                $modelName,
                $actionName,
            ],
            $stub
        );
    }
}
