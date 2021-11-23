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

        $factory = new HandlerStackFactory();
        $stack = $factory->create($config);

        $config = array_replace([
            'handler' => $stack,
            'timeout' => $config->get('http.timeout', 3),
        ], $options);

        if (method_exists($this->container, 'make')) {
            // Create by DI for AOP.
            return $this->container->make(Client::class, ['config' => $config]);
        }
        return new Client($config);
    }
}
