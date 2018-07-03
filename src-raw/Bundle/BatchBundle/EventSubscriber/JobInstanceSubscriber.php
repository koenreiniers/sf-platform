<?php
namespace Raw\Bundle\BatchBundle\EventSubscriber;

use Cron\CronExpression;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Raw\Bundle\BatchBundle\Entity\JobInstance;

class JobInstanceSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return ['preUpdate'];
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $jobInstance = $event->getEntity();
        if(!$jobInstance instanceof JobInstance) {
            return;
        }
        if(!$jobInstance->isCronEnabled()) {
            $jobInstance->setCronNextRunAt(null);
        } else {

            $exprString = $jobInstance->getCronExpression();

            $expr = CronExpression::factory($exprString);

            $nextRunAt = $expr->getNextRunDate();
            $jobInstance->setCronNextRunAt($nextRunAt);
        }

    }
}