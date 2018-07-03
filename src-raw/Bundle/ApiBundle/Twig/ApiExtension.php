<?php
namespace Raw\Bundle\ApiBundle\Twig;

use Doctrine\ORM\EntityManager;
use Raw\Bundle\ApiBundle\Entity\Token;
use Raw\Component\OAuth2\Repository\TokenRepository;
use Raw\Component\OAuth2\TokenFactory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiExtension extends \Twig_Extension implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var TokenFactory
     */
    private $tokenFactory;

    /**
     * @var TokenRepository
     */
    private $tokenRepository;

    private function initDeps()
    {
        $this->entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $this->tokenStorage = $this->container->get('security.token_storage');
        $this->tokenFactory = $this->container->get('raw_api.token_factory');
        $this->tokenRepository = $this->container->get('raw_api.repository.token');
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('get_api_token', [$this, 'getApiToken']),
            new \Twig_SimpleFunction('get_normalized_flash_messages', [$this, 'getNormalizedFlashMessages']),
        ];
    }

    public function getNormalizedFlashMessages()
    {
        $session = $this->container->get('session');
        $flashes = [];
        foreach($session->getFlashBag()->all() as $level => $messages) {
            foreach($messages as $message) {
                $flashes[] = ['level' => $level, 'message' => $message];
            }
        }
        return $flashes;
    }

    public function getApiToken()
    {
        $this->initDeps();

        $owner = $this->tokenStorage->getToken()->getUser();
        $token = $this->tokenRepository->findNonExpiredAccessToken($owner);

        if($token === null) {
            $token = $this->tokenFactory->create(Token::TYPE_ACCESS);
            $token->setOwner($owner);
            $this->entityManager->persist($token);
            $this->entityManager->flush();
        }

        return $token->getValue();
    }

}