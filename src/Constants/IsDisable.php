<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
#[Constants]
class IsDisable extends AbstractConstants
{
    /**
     * @Message("正常")
     */
    const NO = 0;

    /**
     * @Message("禁用")
     */
    const YES = 1;
}
