<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Listener;

use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;
use Hyperf\Validation\Validator;

class CustomValidatorFactoryListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        /**  @var ValidatorFactoryInterface $validatorFactory */
        $validatorFactory = $event->validatorFactory;
        // 注册了 foo 验证器
        $validatorFactory->extend('password', function ($attribute, $value, $parameters, $validator) {
            /** @var Validator $validator $validator */
            return $validator->validateString($attribute, $value)
                && $validator->validateAlphaDash($attribute, $value)
                && $validator->validateBetween($attribute, $value, [8, 64]);
        });
        // 当创建一个自定义验证规则时，你可能有时候需要为错误信息定义自定义占位符这里扩展了 :foo 占位符
        $validatorFactory->replacer('password', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':password', $attribute, $message);
        });
    }
}
