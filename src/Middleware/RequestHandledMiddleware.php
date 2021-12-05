<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Middleware;

use App\Constants\Code;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpMessage\Exception\MethodNotAllowedHttpException;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Validation\ValidationException;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qingpizi\HyperfFramework\Exception\BaseException;
use Qingpizi\HyperfFramework\Kernel\Http\Response;


class RequestHandledMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    protected array $defaultHeaderWhiteList = ['content-type', 'content-length', 'authorization', 'user_id', 'sdk_version', 'sign', 'sign_nonce', 'sign_version', 'timestamp'];

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $config = $this->container->get(ConfigInterface::class);
        if ($config->get('logger.default.custom.request.enable') === false) {
            return $handler->handle($request);
        }
        $time = microtime(true);
        try {
            $response = $handler->handle($request);
        } catch (ValidationException $exception) { // 参数验证异常
            $body = $exception->validator->errors()->first();
            $response = $this->container->get(Response::class)->fail($exception->status, $body);
            $exception = null;
        } catch (MethodNotAllowedHttpException $exception) { // 请求方法异常
            $response = $this->container->get(Response::class)->fail(405, $exception->getMessage());
            $exception = null;
        } catch (BaseException $exception) { // 业务异常正常输出
            $response = $this->container->get(Response::class)->fail($exception->getCode(), $exception->getMessage());
            $exception = null;
        } catch (\Throwable $exception) {
            throw $exception;
        } finally {
            if (! in_array($request->getUri()->getPath(), $config->get('logger.default.custom.request.exclude_url_path'))) {
                $time = round((microtime(true) - $time) * 1000, 2);

                $logger = $this->container->get(LoggerFactory::class)->get('request');

                // 日志
                $debug = $request->getMethod() . ' ' . (string) $request->getUri() . PHP_EOL;
                $debug .= $this->getRequestString($request) . PHP_EOL;
                if (isset($response)) {
                    $debug .= 'RESPONSE: ' . $this->getResponseString($response) . PHP_EOL;
                }

                if (isset($exception) && $exception instanceof \Throwable) {
                    $debug .= 'EXCEPTION: ' . $exception->getMessage() . PHP_EOL;
                }
                $context = ['time' => $time];
                if ($time > $config->get('logger.default.custom.request.timeout')) {
                    $logger->error($debug, $context);
                } else {
                    $logger->info($debug, $context);
                }
            }
        }

        return $response;
    }

    protected function getResponseString(ResponseInterface $response): string
    {
        return (string) $response->getBody();
    }

    protected function getRequestString(ServerRequestInterface $request): string
    {
        $result = '';
        $config = $this->container->get(ConfigInterface::class);
        foreach ($request->getHeaders() as $header => $values) {
            if (in_array($header, $this->defaultHeaderWhiteList)) {
                foreach ((array) $values as $value) {
                    $result .= $header . ': ' . $value . PHP_EOL;
                }
            }
        }

        $result .= (string) $request->getBody();
        return $result;
    }
}
