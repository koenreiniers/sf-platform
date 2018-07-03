<?php
namespace Raw\Bundle\UserBundle\Controller;

use Raw\Bundle\UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Model\UserManagerInterface;
use Knp\Menu\Loader\ArrayLoader;
use Raw\Bundle\AdminBundle\Form\Type\DeleteEntityType;
use Raw\Bundle\PlatformBundle\Controller\AdvancedController;
use Raw\Bundle\UserBundle\Form\Type\UserType;
use Raw\Bundle\VersioningBundle\Entity\Version;
use Raw\Filter\Context\FilterDefinition;
use Raw\Filter\Filter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\VarDumper\VarDumper;


class UserController extends AdvancedController
{

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);

        $this->userManager = $container->get('fos_user.user_manager');
    }

    protected function getClassName()
    {
        return User::class;
    }

    public function deleteAction(Request $request, $id)
    {
        $user = $this->findOr404($id);

        $this->denyAccessUnlessGranted('DELETE', $user);

        $deleteForm = $this->createForm(DeleteEntityType::class, $user);
        $deleteForm->handleRequest($request);
        if($this->isApiRequest() || $deleteForm->isValid()) {
            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }

        $this->addFlash('success', 'User has been deleted');

        return $this->redirectToRoute('raw_user.user.index');
    }
}