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
namespace Qingpizi\HyperfFramework\Kernel\Log;

use Hyperf\Utils\Context;
use Hyperf\Utils\Coroutine;
use Monolog\Processor\ProcessorInterface;
use Qingpizi\HyperfFramework\Constants\GlobalParam;

class AppendRequestIdProcessor implements ProcessorInterface
{

    public function __invoke(array $records)
    {
        $records['context']['request_id'] = Context::getOrSet(GlobalParam::REQUEST_ID, uniqid());
        $records['context']['coroutine_id'] = Coroutine::id();
        $records['context']['user_id'] = Context::get(GlobalParam::USER_ID, 0);
        return $records;
    }
}
