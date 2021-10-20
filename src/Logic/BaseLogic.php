<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Logic;

use Psr\Container\ContainerInterface;

class BaseLogic
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
