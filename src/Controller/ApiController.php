<?php

declare(strict_types=1);

namespace whatwedo\MonitorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\SerializerInterface;
use whatwedo\MonitorBundle\Manager\MonitoringManager;

class ApiController extends AbstractController
{
    public function __construct(
        protected ?string $authToken = null
    ) {
    }

    public function __invoke(Request $request, MonitoringManager $monitoringManager, SerializerInterface $serializer): Response
    {
        if ($this->authToken !== null
            && $request->headers->get('X-Auth-Token', '') !== $this->authToken) {
            return new Response('Unauthorized', 401);
        }

        return new Response(
            $serializer->serialize($monitoringManager->getResult(), $request->attributes->get('_format'), [
                JsonEncode::OPTIONS => $request->query->has('pretty') ? JSON_PRETTY_PRINT : 0,
            ]),
            $monitoringManager->isSuccessful() ? Response::HTTP_OK : Response::HTTP_SERVICE_UNAVAILABLE,
            [
                'Content-type' => match ($request->attributes->get('_format')) {
                    'json' => 'application/json',
                    'xml' => 'application/xml',
                },
            ]
        );
    }
}
