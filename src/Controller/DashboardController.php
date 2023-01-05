<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use whatwedo\MonitorBundle\Manager\MonitoringManager;

class DashboardController extends AbstractController
{
    public function __invoke(MonitoringManager $monitoringManager): Response
    {
        return $this->render('@whatwedoMonitor/dashboard.html.twig', [
            'result' => $monitoringManager->getResult(),
        ]);
    }
}
