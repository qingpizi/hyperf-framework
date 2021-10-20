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
namespace Qingpizi\HyperfFramework\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use Hyperf\ModelCache\CacheableInterface;

class BaseModel extends Model implements CacheableInterface
{
    use Cacheable;
}
