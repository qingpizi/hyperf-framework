<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace Qingpizi\HyperfFramework\Listener;

use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;
use Psr\Container\ContainerInterface;
use Hyperf\Contract\ConfigInterface;

class DbAccessLogListener implements ListenerInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            QueryExecuted::class,
        ];
    }

    /**
     * @param QueryExecuted|object $event
     */
    public function process(object $event)
    {
        $config = $this->container->get(ConfigInterface::class);
        if ($config->get('logger.default.custom.database.enable') === false) {
            return;
        }
        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (! Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }
            $logger = $this->container->get(LoggerFactory::class)->get('sql');
            $context = ['time' => $event->time, 'connection_name' => $event->connectionName];
            if ($event->time > $config->get('logger.default.custom.database.timeout')) {
                $logger->error($sql, $context);
            } else {
                $logger->debug($sql, $context);
            }
        }
    }
}
