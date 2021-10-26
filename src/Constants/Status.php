<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Constants;

/**
 * @Constants
 * @method string getMessage(int $code)
 */
#[Constants]
class Status
{
    /**
     * @Message("正常")
     */
    const ENABLE = 0;

    /**
     * @Message("禁用")
     */
    const DISABLE = 1;
}
