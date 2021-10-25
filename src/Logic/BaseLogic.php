<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Logic;

use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Qingpizi\HyperfFramework\Kernel\Http\Response;

class BaseLogic
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * 默认显示页数
     */
    const DEFAULT_PER_PAGE = 30;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(Response::class);
        $this->request = $container->get(RequestInterface::class);
    }
}
