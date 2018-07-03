<?php

namespace Raw\Component\OAuth\Signature;


class PlaintextSignature
{

    public function sign(array $parameters, $method = 'POST', $url = null, $consumerSecret, $tokenSecret = null)
    {
        if ($tokenSecret === null) {
            return $consumerSecret . '&';
        }
        $return = implode('&', array($consumerSecret, $tokenSecret));
        return $return;
    }
}
