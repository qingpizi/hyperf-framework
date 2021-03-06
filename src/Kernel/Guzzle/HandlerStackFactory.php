<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Kernel\Guzzle;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\RetryMiddleware;
use Hyperf\Logger\LoggerFactory;
use Qingpizi\HyperfFramework\Constants\Time;

class HandlerStackFactory
{

    public function create($middlewares = [])
    {
        $config = di()->get(ConfigInterface::class);
        $retryCount = (int) $config->get('http.retry.count', 0);
        if ($retryCount > 0) {
            $middlewares['retry'] = [RetryMiddleware::class, [$retryCount, (int) $config->get('http.retry.delay', 3 * Time::SECOND)]];
        }

        if ($config->get('logger.default.custom.http.enable') !== false) {
            $middlewares['logger'] = [HttpRequestAccessLogMiddleware::class, [di()->get(LoggerFactory::class), (int) $config->get('logger.default.custom.http.timeout')]];
        }

        $factory = new \Hyperf\Guzzle\HandlerStackFactory();
        return $factory->create([
            'min_connections' => (int) $config->get('http.pool.min_connections', 1),
            'max_connections' => (int) $config->get('http.pool.max_connections', 30),
            'wait_timeout' => (float) $config->get('http.pool.wait_timeout', 3.0),
            'max_idle_time' => (int) $config->get('http.pool.max_idle_time', 60),
        ], $middlewares);
    }
}
