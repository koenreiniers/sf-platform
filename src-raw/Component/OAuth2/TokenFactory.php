<?php
namespace Raw\Component\OAuth2;

use Raw\Component\OAuth2\Model\Token;

class TokenFactory
{
    /**
     * @var string
     */
    protected $tokenClass;

    /**
     * TokenFactory constructor.
     * @param string $tokenClass
     */
    public function __construct($tokenClass = Token::class)
    {
        $this->tokenClass = $tokenClass;
    }

    /**
     * @return Token
     */
    protected function newToken()
    {
        return new $this->tokenClass;
    }

    /**
     * @param string $type
     * @return Token
     */
    public function create($type)
    {
        $expirationTimes = [
            Token::TYPE_ACCESS => 3600,
            Token::TYPE_REFRESH => 86400,
        ];
        $expiresIn = $expirationTimes[$type];
        $expiresAt = null;
        if($expiresIn !== null) {
            $expiresAt = new \DateTime();
            $expiresAt->modify(sprintf('+ %s seconds', $expiresIn));
        }
        $value = $this->generateTokenValue();
        $token = $this->newToken()
            ->setType($type)
            ->setExpiresAt($expiresAt)
            ->setValue($value);
        return $token;
    }

    private function generateTokenValue($length = 16)
    {
        return bin2hex(random_bytes($length));
    }
}