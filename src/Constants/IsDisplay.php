<?php
declare(strict_types=1);


namespace Qingpizi\HyperfFramework\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
#[Constants]
class IsDisplay extends AbstractConstants
{
    /**
     * @Message("隐藏")
     */
    const NO = 0;

    /**
     * @Message("显示")
     */
    const YES = 1;
}
