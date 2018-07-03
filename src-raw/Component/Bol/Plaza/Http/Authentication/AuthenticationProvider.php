<?php
namespace Raw\Component\Bol\Plaza\Http\Authentication;

use Raw\Component\Bol\Plaza\PlazaCredentials;
use Psr\Http\Message\RequestInterface;

class AuthenticationProvider implements AuthenticationProviderInterface
{

    /**
     * @var PlazaCredentials
     */
    protected $plazaCredentials;

    /**
     * AuthenticationProvider constructor.
     * @param PlazaCredentials $plazaCredentials
     */
    public function __construct(PlazaCredentials $plazaCredentials)
    {
        $this->plazaCredentials = $plazaCredentials;
    }

    /**
     * @inheritdoc
     */
    public function authenticate(RequestInterface $request)
    {
        $pub = $this->plazaCredentials->getPublicKey();
        $priv = $this->plazaCredentials->getPrivateKey();
        $target = $request->getUri();

        $method = $request->getMethod();
        $contentType = 'application/xml';
        $date = gmdate('D, d M Y H:i:s T');
        $headers = $this->getHeaders($pub, $priv, $target, $date, $method, $contentType);
        foreach($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }


        return $request;
    }

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @param string $target
     * @param string $date
     * @param string $method
     * @param string $contentType
     *
     * @return array
     */
    private function getHeaders($publicKey, $privateKey, $target, $date, $method, $contentType)
    {
        $headers = [
            'Content-Type'          => sprintf('%s; charset=UTF-8', $contentType),
            'X-BOL-Date'            => $date,
            'X-BOL-Authorization'   => $this->getAuthorizationHeader($publicKey, $privateKey, $target, $date, $method, $contentType)
        ];
        return $headers;
    }

    /**
     * @param string $publicKey
     * @param string $privateKey
     * @param string $target
     * @param string $date
     * @param string $method
     * @param string $contentType
     *
     * @return string
     */
    private function getAuthorizationHeader($publicKey, $privateKey, $target, $date, $method, $contentType)
    {
        $signatureElements = [];
        $signatureElements [] = strtoupper($method) . "\n";
        $signatureElements [] = $contentType . "; charset=UTF-8";
        $signatureElements [] = $date;
        $signatureElements [] = "x-bol-date:" . $date;
        $signatureElements [] = $target;

        $signatureString = implode("\n", $signatureElements);

        $signature = $publicKey . ':';
        $signature .= base64_encode(hash_hmac('SHA256', $signatureString, $privateKey, true));

        return $signature;
    }
}