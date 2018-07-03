<?php
namespace Raw\Component\Rest\EventListener;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class AddFlashMessagesListener
{
    public function onRestResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();

        $session = $event->getRequest()->getSession();

        if(!$session instanceof Session) {
            return;
        }

        $flashMessages = [];
        foreach($session->getFlashbag()->all() as $type => $messages) {
            foreach($messages as $message) {
                $flashMessages[] = [
                    'type' => $type,
                    'message' => $message,
                ];
            }
        }

        if(count($flashMessages) > 0) {
            $response->headers->set('X-Flash-Messages', json_encode($flashMessages));
        }
    }
}