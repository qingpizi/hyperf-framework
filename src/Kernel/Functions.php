<?php
declare(strict_types=1);

use Hyperf\ExceptionHandler\Formatter\FormatterInterface;
use Hyperf\HttpMessage\Server\Request;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Qingpizi\HyperfFramework\Kernel\Http\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Qingpizi\HyperfFramework\Constants\Env;

if (! function_exists('isDev')) {
    /**
     * @return bool
     */
    function isDev()
    {
        return in_array(config('app_env', Env::LOCAL), [Env::LOCAL, Env::DEV, Env::TEST]);
    }
}

if (! function_exists('request')) {
    /**
     * @return Request
     */
    function request()
    {
        return Context::get(ServerRequestInterface::class);
    }

}


if (! function_exists('response')) {
    /**
     * @return Response
     */
    function response()
    {
        return Context::get(PsrResponseInterface::class);
    }

}

if (! function_exists('di')) {
    /**
     * Finds an entry of the container by its identifier and returns it.
     * @param null|mixed $id
     * @return mixed|ContainerInterface
     */
    function di($id = null)
    {
        $container = ApplicationContext::getContainer();
        if ($id) {
            return $container->get($id);
        }

        return $container;
    }
}

if (! function_exists('formatThrowable')) {
    /**
     * Format a throwable to string.
     * @param Throwable $throwable
     * @return string
     */
    function formatThrowable(Throwable $throwable): string
    {
        return di()->get(FormatterInterface::class)->format($throwable);
    }
}

if (! function_exists('getClientIp')) {
    /**
     * 获取客户端IP
     * @return string
     */
    function getClientIp(): string
    {
        if (request()->hasHeader('x-real-ip')) {
            $clientIp = current(request()->getHeader('x-real-ip'));
        } else {
            $clientIp = request()->getServerParams()['remote_addr'] ?? '';
        }
        return $clientIp;
    }
}
