<?php

declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Kernel\Context;

use Qingpizi\HyperfFramework\Constants\GlobalParam;
use Qingpizi\HyperfFramework\Kernel\Log\AppendRequestIdProcessor;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Engine\Coroutine as Co;
use Hyperf\Utils;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class Coroutine
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger = $container->get(StdoutLoggerInterface::class);
    }

    /**
     * @return int Returns the coroutine ID of the coroutine just created.
     *             Returns -1 when coroutine create failed.
     */
    public function create(callable $callable): int
    {
        $id = Utils\Coroutine::id();
        $coroutine = Co::create(function () use ($callable, $id) {
            try {
                // Shouldn't copy all contexts to avoid socket already been bound to another coroutine.
                Utils\Context::copy($id, [
                    GlobalParam::REQUEST_ID,
                    ServerRequestInterface::class,
                ]);
                call($callable);
            } catch (Throwable $throwable) {
                $this->logger->warning((string) $throwable);
            }
        });

        try {
            return $coroutine->getId();
        } catch (\Throwable $throwable) {
            $this->logger->warning((string) $throwable);
            return -1;
        }
    }
}
