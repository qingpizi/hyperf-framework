<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Kernel\Guzzle;


use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\MessageFormatterInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Hyperf\Guzzle\MiddlewareInterface;
use Hyperf\Logger\LoggerFactory;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use GuzzleHttp\Promise\Create;
use Psr\Http\Message\MessageInterface;

class HttpRequestAccessLogMiddleware implements MiddlewareInterface
{

    /**
     * @var LoggerFactory
     */
    protected $logger;

    protected $timeout;


    public function __construct(LoggerFactory $logger, int $timeout)
    {
        $this->logger = $logger;
        $this->timeout = $timeout;
    }

    public function getMiddleware(): callable
    {
        $debug = '{method} {uri}' . PHP_EOL;
        $debug .= '{request}' . PHP_EOL;
        $debug .= '{response}' . PHP_EOL;
        $debug .= 'EXCEPTION: {error}' . PHP_EOL;

        return $this->log(
            $this->logger->make('http'),
            new MessageFormatter($debug),
            $this->timeout
        );
    }

    /**
     * Middleware that logs requests, responses, and errors using a message
     * formatter.
     *
     * @phpstan-param \Psr\Log\LogLevel::* $logLevel  Level at which to log requests.
     *
     * @param LoggerInterface                            $logger    Logs messages.
     * @param MessageFormatterInterface|MessageFormatter $formatter Formatter used to create message strings.
     *
     * @return callable Returns a function that accepts the next handler.
     */
    public static function log(LoggerInterface $logger, $formatter, $timeout): callable
    {
        // To be compatible with Guzzle 7.1.x we need to allow users to pass a MessageFormatter
        if (!$formatter instanceof MessageFormatter && !$formatter instanceof MessageFormatterInterface) {
            throw new \LogicException(sprintf('Argument 2 to %s::log() must be of type %s', self::class, MessageFormatterInterface::class));
        }

        return static function (callable $handler) use ($logger, $formatter, $timeout): callable {
            return static function (RequestInterface $request, array $options = []) use ($handler, $logger, $formatter, $timeout) {
                $time = microtime(true);
                return $handler($request, $options)->then(
                    static function ($response) use ($logger, $request, $formatter, $time, $timeout): ResponseInterface {
                        $time = round((microtime(true) - $time) * 1000, 2);
                        $message = $formatter->format($request, $response);
                        if ($response instanceof MessageInterface) {
                            $response->getBody()->rewind();
                        }
                        if ($time > $timeout) {
                            $logger->error($message, ['time' => $time]);
                        } else {
                            $logger->info($message, ['time' => $time]);
                        }

                        return $response;
                    },
                    static function ($reason) use ($logger, $request, $formatter, $time): PromiseInterface {
                        $time = round((microtime(true) - $time) * 1000, 2);
                        $response = $reason instanceof RequestException ? $reason->getResponse() : null;
                        $message = $formatter->format($request, $response, Create::exceptionFor($reason));
                        $logger->error($message, ['time' => $time]);
                        if ($response instanceof MessageInterface) {
                            $response->getBody()->rewind();
                        }
                        return Create::rejectionFor($reason);
                    }
                );
            };
        };
    }

}
