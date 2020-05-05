<?php

namespace App\EventListener;

use App\Response\ApiProblem;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use SoftPassio\ApiExceptionBundle\Component\Factory\ApiProblemFactoryInterface;
use SoftPassio\ApiExceptionBundle\Component\Factory\ResponseFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResponseJsonListener
{
    private const INTERNAL_SERVER_ERROR_CODE = 500;

    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var ResponseFactoryInterface
     */
    private $responseErrorFactory;
    /**
     * @var ApiProblemFactoryInterface
     */
    private $apiProblemFactory;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var string
     */
    private $appEnv;

    public function __construct(
        SerializerInterface $serializer,
        ResponseFactoryInterface $responseErrorFactory,
        ApiProblemFactoryInterface $apiProblemFactory,
        TranslatorInterface $translator,
        string $appEnv
    ) {
        $this->serializer = $serializer;
        $this->responseErrorFactory = $responseErrorFactory;
        $this->apiProblemFactory = $apiProblemFactory;
        $this->translator = $translator;
        $this->appEnv = $appEnv;
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ('application/json' !== $event->getResponse()->headers->get('Content-Type')) {
            return;
        }

        if (!$event->getResponse()->isSuccessful()) {
            if ($event->getResponse()->getStatusCode() >= 400) {
                $event->getResponse()->headers->set('Content-Type', 'application/problem+json');

                return;
            }

            return;
        }

        $data = $this->serializer->deserialize($event->getResponse()->getContent(), 'array', 'json');

        $newResponse = null;
        if (isset($data['current_page'])) {
            $meta = [
                'current_page'   => $data['current_page'],
                'items_per_page' => $data['items_per_page'],
                'total_items'    => $data['total_items'],
            ];
            $newResponse['paging'] = $meta;
        }

        if (isset($data['total_commenters']) && isset($meta)) {
            $meta['total_commenters'] = $data['total_commenters'];
            $newResponse['paging'] = $meta;
        }

        if (isset($data['items'])) {
            if (count($data['items'])) {
                $newResponse['data'] = $data['items'];
            }
        } else {
            $newResponse['data'] = $data;
        }

        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $content = $this->serializer->serialize($newResponse, 'json', $context);

        $event->getResponse()->setContent($content);
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getException();
        if (self::INTERNAL_SERVER_ERROR_CODE === $exception->getCode()) {
            /** @var ApiProblem $apiProblem */
            $apiProblem = $this->apiProblemFactory->create(
                self::INTERNAL_SERVER_ERROR_CODE,
                null,
                [
                    ApiProblem::KEY_TITLE   => $this->translator->trans('error_response.internal_server_error.title'),
                    ApiProblem::KEY_DETAILS => $this->translator->trans('error_response.internal_server_error.detail'),
                ]
            );
            $response = new JsonResponse($apiProblem->toArray(), self::INTERNAL_SERVER_ERROR_CODE);
            $response->headers->set('Content-Type', 'application/problem+json');

            $event->setResponse($response);
        }
    }
}
