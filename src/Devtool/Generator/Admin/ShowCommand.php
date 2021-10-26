<?php
declare(strict_types=1);
/**
 * Created by PhpStorm
 * User: qingpizi
 * Date: 2021/10/10
 * Time: 下午3:26
 */

namespace Qingpizi\HyperfFramework\Devtool\Generator\Admin;

use Hyperf\Command\Annotation\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Qingpizi\HyperfFramework\Devtool\Generator\BaseRCLGeneratorCommand;

/**
 * @Command
 */
#[Command]
class ShowCommand extends BaseRCLGeneratorCommand
{

    protected $currentDiv = __DIR__;

    public function __construct()
    {
        parent::__construct('admin:rcl-show');
        $this->setDescription('Create new request&controller&logic show class');
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
        return $this->executeHandle($input, $output, ['controller', 'logic'], 'show');
    }

}
