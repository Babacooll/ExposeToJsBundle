<?php

namespace Babacooll\ExposeToJsBundle\EventListener;

use Babacooll\ExposeToJsBundle\Exposer\ExposerInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

/**
 * Class ExposerResponseListener
 *
 * @package Babacooll\ExposeToJsBundle\EventListener
 */
class ExposerResponseListener
{
    /** @var ExposerInterface */
    protected $exposer;

    /**
     * @param ExposerInterface $exposer
     */
    public function __construct(ExposerInterface $exposer)
    {
        $this->exposer = $exposer;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernel::MASTER_REQUEST && !$event->getRequest()->isXmlHttpRequest()) {
            $event->setResponse($this->exposer->updateResponse($event->getResponse()));
        }
    }
}
