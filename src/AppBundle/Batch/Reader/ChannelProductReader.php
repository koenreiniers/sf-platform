<?php
namespace AppBundle\Batch\Reader;

use AppBundle\Entity\Channel;
use CatalogBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Raw\Component\Batch\Item\InitializableInterface;
use Raw\Component\Batch\Item\ItemReaderInterface;
use Raw\Component\Batch\Item\Reader\BasicItemReader;
use Raw\Component\Batch\Step\StepExecutionAwareInterface;
use Raw\Component\Batch\Step\StepExecutionAwareTrait;

class ChannelProductReader extends BasicItemReader implements StepExecutionAwareInterface
{
    use StepExecutionAwareTrait;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ChannelReader constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    protected function loadItems()
    {
        throw new \Exception('ok');
        global $kernel;

        $channel = $this->entityManager->getRepository(Channel::class)->find($this->stepExecution->getJobParameters()['channel']);

        $productRepository = $this->entityManager->getRepository(Product::class);
        $lastUpdatedProduct = $productRepository->findLastUpdatedProduct($channel);
        $after = null;
        if($lastUpdatedProduct !== null) {
            $after = $lastUpdatedProduct->getExternalUpdatedAt();
        }

        return $kernel->getContainer()->get('app.platform_helper')->getAdapter($channel)->importProducts($channel, $after);
    }
}