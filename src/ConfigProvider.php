<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace Qingpizi\HyperfFramework;


class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                \Hyperf\Contract\StdoutLoggerInterface::class => \Qingpizi\HyperfFramework\Kernel\Log\StdoutLoggerFactory::class,
                \Hyperf\Guzzle\ClientFactory::class => \Qingpizi\HyperfFramework\Kernel\Guzzle\ClientFactory::class
            ],
            'aspects' => [
                \Qingpizi\HyperfFramework\Aspect\RedisAccessLogAspect::class
            ],
            'listeners' => [
                \Qingpizi\HyperfFramework\Listener\DbAccessLogListener::class
            ],
            'middlewares' => [
                'http' => [
                    \Qingpizi\HyperfFramework\Middleware\RequestHandledMiddleware::class
                ]
            ],
            'commands' => [
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ]
        ];
    }
}
