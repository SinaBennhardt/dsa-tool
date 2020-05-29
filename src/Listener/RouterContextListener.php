<?php

namespace App\Listener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;

class RouterContextListener
{
    private const CAMPAIGN_PARAMETER_NAME = 'campaignId';

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onKernelRequest(RequestEvent $event) {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $campaignId = $request->attributes->get(self::CAMPAIGN_PARAMETER_NAME);

        if (!$campaignId) {
            return;
        }

        $this->router->getContext()->setParameter(self::CAMPAIGN_PARAMETER_NAME, $campaignId);
    }
}