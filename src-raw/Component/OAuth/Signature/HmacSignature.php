<?php
namespace Raw\Component\OAuth\Signature;

use ZendOAuth\Signature\Hmac;

class HmacSignature
{
    /**
     * @var string
     */
    private $algo = 'sha1';

    public function sign(array $parameters, $method = 'POST', $url = null, $consumerSecret, $tokenSecret = null)
    {
        $signer = new Hmac($consumerSecret, $tokenSecret, 'sha1');

        return $signer->sign($parameters, $method, $url);

        $baseString = $method.'&'.rawurlencode($url).'&'.rawurlencode(http_build_query($parameters));

        echo $baseString;die;

        $key = $consumerSecret.'&'.$tokenSecret;

        return base64_encode(hash_hmac($this->algo, $baseString, $key, true));
    }
}