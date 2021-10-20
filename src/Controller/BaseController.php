<?php

declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Qingpizi\HyperfFramework\Exception\BusinessException;
use Qingpizi\HyperfFramework\Kernel\Http\Response;
use Psr\Container\ContainerInterface;

class BaseController
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


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->response = $container->get(Response::class);
        $this->request = $container->get(RequestInterface::class);
    }
}
