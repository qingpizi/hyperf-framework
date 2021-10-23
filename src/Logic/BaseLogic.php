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

    /**
     * 默认显示页数
     */
    const DEFAULT_PER_PAGE = 30;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
