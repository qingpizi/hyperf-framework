<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Aspect;


use Hyperf\Contract\ConfigInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Hyperf\Di\Aop\AroundInterface;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Redis\Redis;


class RedisAccessLogAspect implements AroundInterface
{

    /**
     * @var array
     */
    public $classes
        = [
            Redis::class . '::__call',
        ];

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed return the value from process method of ProceedingJoinPoint, or the value that you handled
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $config = $this->container->get(ConfigInterface::class);
        if ($config->get('logger.default.custom.redis.enable') === false) {
            return $proceedingJoinPoint->process();
        }

        $time = microtime(true);
        try {
            $result = $proceedingJoinPoint->process();
        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            // 日志
            $time = round((microtime(true) - $time) * 1000, 2);

            $arguments = $proceedingJoinPoint->arguments['keys'];
            $logger = $this->container->get(LoggerFactory::class)->get('redis');
            $cmd = sprintf('CALL: %s %s', $arguments['name'], json_encode($arguments['arguments'], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));

            $context = [
                'time' => $time,
            ];

            if (isset($result)) {
                $context['result'] = is_array($result) ? json_encode($result) : (string) $result;
            }
            if (isset($exception) && $exception instanceof \Throwable) {
                $context['exception'] = $exception->getMessage();
            }

            if ($time > $config->get('logger.default.custom.redis.timeout')) {
                $logger->warning($cmd, $context);
            } else {
                $logger->debug($cmd, $context);
            }
        }
        return $result;
    }
}
