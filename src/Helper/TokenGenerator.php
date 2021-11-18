<?php
declare(strict_types=1);

namespace Qingpizi\HyperfFramework\Helper;

use Firebase\JWT\JWT;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Qingpizi\HyperfFramework\Exception\RuntimeException;

class TokenGenerator
{
    /**
     * @param array $message
     * @param string $key
     * @param int $expire
     * @return string
     */
    public static function generateToken(array $message, string $key, int $expire): string
    {
        $time = time();
        $builder = new Builder();
        return (string) $builder->issuedAt($time)
            ->expiresAt($time + $expire)
            ->withHeader('alg', 'HS256')
            ->withClaim('message', $message)
            ->getToken(new Sha256(), new Key($key));
    }

    /**
     * @param $verifyToken
     * @param string $key
     * @return array
     */
    public static function verifyToken($verifyToken, string $key = ''): array
    {
        $parser = new Parser();
        $token = $parser->parse($verifyToken);
        if (!$token->verify(new Sha256(), new Key($key))) {
            throw new RuntimeException('The token verify failed.');
        }
        return (array) $token->getClaims('message');
    }
}
