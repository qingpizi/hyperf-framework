<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Devtool\Generator\Admin;

use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Qingpizi\HyperfFramework\Devtool\Generator\BaseRCLGeneratorCommand;

/**
 * @Command
 */
#[Command]
class DestroyCommand extends BaseRCLGeneratorCommand
{
    protected string $currentDiv = __DIR__;

    public function __construct()
    {
        parent::__construct('admin:cl-destroy');
        $this->setDescription('Create new controller&logic destroy class');
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
        return $this->executeHandle($input, $output, ['controller', 'logic'], 'destroy');
    }

}
