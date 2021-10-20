<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Kernel\Log;

use Psr\Container\ContainerInterface;

class StdoutLoggerFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return Log::get('sys');
    }
}
