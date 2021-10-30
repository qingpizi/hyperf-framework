<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Devtool\Generator\Admin;

use Hyperf\Command\Annotation\Command;
use Hyperf\Devtool\Generator\GeneratorCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Qingpizi\HyperfFramework\Devtool\Generator\BaseRCLGeneratorCommand;

/**
 * @Command
 */
#[Command]
class AdminCommand extends BaseRCLGeneratorCommand
{

    protected string $currentDiv = __DIR__;

    public function __construct()
    {
        parent::__construct('admin:rcl-all');
        $this->setDescription('Create new all class');
    }

    /**
     * Execute the console command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $rcls = [
            [
                'behavior_name' => 'index',
                'module_names' => ['request', 'controller', 'logic']
            ],
            [
                'behavior_name' => 'show',
                'module_names' => ['controller', 'logic']
            ],
            [
                'behavior_name' => 'store',
                'module_names' => ['request', 'controller', 'logic']
            ],
            [
                'behavior_name' => 'update',
                'module_names' => ['request', 'controller', 'logic']
            ],
            [
                'behavior_name' => 'destroy',
                'module_names' => ['controller', 'logic']
            ],
        ];
        foreach ($rcls as $rcl) {
            $this->executeHandle($input, $output, $rcl['module_names'], $rcl['behavior_name']);
        }
        return 0;
    }

}
