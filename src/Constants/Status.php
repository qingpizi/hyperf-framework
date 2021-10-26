<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Constants;

use Hyperf\Constants\AbstractConstants;

/**
 * @Constants
 */
#[Constants]
class Status extends AbstractConstants
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
