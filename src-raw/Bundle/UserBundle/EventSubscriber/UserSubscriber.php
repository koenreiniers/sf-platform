<?php
namespace Raw\Bundle\UserBundle\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Mapping\ClassMetadata;
use FOS\UserBundle\Model\UserManagerInterface;
use Raw\Bundle\PlatformBundle\Entity\File;
use Raw\Bundle\UserBundle\Entity\Notification;
use Raw\Bundle\UserBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\VarDumper\VarDumper;

class UserSubscriber implements EventSubscriber, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    /**
     * @var string
     */
    private $userClass = User::class;

    public function getSubscribedEvents()
    {
        return [
            'prePersist', 'preUpdate', 'loadClassMetadata'
        ];
    }

    private function initDeps()
    {
        $this->userManager = $this->container->get('fos_user.user_manager');
        $this->userClass = $this->container->getParameter('raw_user.user_class');
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $event)
    {
        /** @var ClassMetadata $classMetadata */
        $classMetadata = $event->getClassMetadata();

        if($classMetadata->getName() !== $this->userClass) {
            return;
        }
        $classMetadata->mapManyToOne([
            'fieldName' => 'profileImage',
            'targetEntity' => File::class,
            'cascade' => ['persist', 'remove'],
        ]);
//        $classMetadata->mapOneToMany([
//            'fieldName' => 'notifications',
//            'targetEntity' => Notification::class,
//            'mappedBy' => 'owner',
//        ]);
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $user = $event->getEntity();
        if(!$user instanceof User) {
            return;
        }
        $this->initDeps();

        if($user->getPlainPassword() === null && $user->getPassword() === null) {
            $plainPassword = bin2hex(random_bytes(8));
            $user->setPlainPassword($plainPassword);
        }

        $this->userManager->updateCanonicalFields($user);
        $this->userManager->updatePassword($user);
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $user = $event->getEntity();
        if(!$user instanceof User) {
            return;
        }
        $this->initDeps();

        $this->userManager->updateCanonicalFields($user);
        $this->userManager->updatePassword($user);
    }
}