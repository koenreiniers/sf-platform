<?php
namespace Raw\Component\OAuth2\Model;

use Raw\Bundle\UserBundle\Behaviour\OwnableInterface;
use Raw\Bundle\UserBundle\Behaviour\OwnableTrait;

class Token implements OwnableInterface
{
    use OwnableTrait;

    const TYPE_ACCESS = 'Bearer';
    CONST TYPE_REFRESH = 'refresh_token';

    /**
     * @var int
     */
    protected $id;

    /**
     * @var \DateTime|null
     */
    protected $expiresAt;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $type;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Token
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expiresAt !== null && $this->expiresAt < (new \DateTime());
    }

    /**
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @return int|null
     */
    public function getExpiresIn()
    {
        if($this->expiresAt === null) {
            return null;
        }
        $now = new \DateTime();
        return $this->expiresAt->getTimestamp() - $now->getTimestamp();
    }

    public function toArray()
    {
        $data = [
            'token_type' => $this->type,
        ];
        $data[$this->type] = $this->value;
        $expiresIn = $this->getExpiresIn();
        if($expiresIn !== null) {
            $data['expires_in'] = $expiresIn;
        }
        return $data;
    }

    /**
     * @param \DateTime $expiresAt
     * @return $this
     */
    public function setExpiresAt(\DateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }
}