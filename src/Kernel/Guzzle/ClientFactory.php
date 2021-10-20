<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Qingpizi\HyperfFramework\Kernel\Guzzle;

use GuzzleHttp\Client;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\RetryMiddleware;
use Psr\Container\ContainerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Guzzle\HandlerStackFactory;
use Qingpizi\HyperfFramework\Constants\Time;

class ClientFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(array $options = []): Client
    {
        $config = $this->container->get(ConfigInterface::class);
        $retryCount = $config->get('http.retry.count', 0);
        if ($retryCount > 0) {
            $middlewares = ['retry' => [RetryMiddleware::class, [$retryCount, $config->get('guzzle.retry.delay', 3 * Time::SECOND)]]];
        }

        if ($config->get('logger.default.custom.http.enable') !== false) {
            $middlewares['logger'] = [HttpRequestAccessLogMiddleware::class, [$this->container->get(LoggerFactory::class), $config->get('logger.default.custom.http.timeout')]];
        }

        $factory = new HandlerStackFactory();
        $stack = $factory->create([
            'min_connections' => $config->get('http.pool.min_connections', 1),
            'max_connections' => $config->get('http.pool.max_connections', 30),
            'wait_timeout' => $config->get('http.pool.wait_timeout', 3.0),
            'max_idle_time' => $config->get('http.pool.max_idle_time', 60),
        ], $middlewares);

        $config = array_replace([
            'handler' => $stack,
            'timeout' => $config->get('http.timeout', 60),
        ], $options);

        if (method_exists($this->container, 'make')) {
            // Create by DI for AOP.
            return $this->container->make(Client::class, ['config' => $config]);
        }
        return new Client($config);
    }
}
