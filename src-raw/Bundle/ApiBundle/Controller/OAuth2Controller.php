<?php
namespace Raw\Bundle\ApiBundle\Controller;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\ApiBundle\Entity\Token;
use Raw\Component\OAuth2\Grant\Exception\GrantException;
use Raw\Component\OAuth2\Grant;
use Raw\Component\OAuth2\TokenFactory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class OAuth2Controller extends Controller
{
    /**
     * @var TokenFactory
     */
    private $tokenFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Grant\GrantRegistry
     */
    private $grantRegistry;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->tokenFactory = $container->get('raw_api.token_factory');
        $this->entityManager = $container->get('doctrine.orm.default_entity_manager');

        $this->grantRegistry = $container->get('raw_api.oauth2.grant_registry');
    }

    public function tokenAction(Request $request)
    {

        $provider = $this->get('raw_api.oauth2.grant_provider');

        try {
            $grant = $provider->getGrant($request->get('grant_type'), $request->query->all());
        } catch(GrantException $e) {
            throw new AccessDeniedHttpException($e->getMessage(), $e, $e->getCode());
        }

        $data = $this->processGrant($grant);

        return $data;
    }

    private function processGrant(Grant $grant)
    {
        $accessToken = $this->tokenFactory
            ->create(Token::TYPE_ACCESS)
            ->setOwner($grant->getUser());
        $this->entityManager->persist($accessToken);

        $data = [
            'token_type' => $accessToken->getType(),
            'expires_in' => $accessToken->getExpiresIn(),
            'access_token' => $accessToken->getValue(),
        ];

        if($grant->isRefreshTokenIncluded() && $this->grantRegistry->hasType(Token::TYPE_REFRESH)) {
            $refreshToken = $this->tokenFactory
                ->create(Token::TYPE_REFRESH)
                ->setOwner($grant->getUser());
            $this->entityManager->persist($refreshToken);

            $data['refresh_token'] = $refreshToken->getValue();
        }

        $this->entityManager->flush();
        return $data;
    }
}