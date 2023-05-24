<?php

declare(strict_types=1);

namespace RashinMe\Service\Response;

use RashinMe\Service\Response\Dto\SystemMessage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory implements ResponseFactoryInterface
{
    public function __construct()
    {
    }

    /**
     * @inheritDoc
     */
    public function createResponse(
        mixed $content = null,
        int $statusCode = Response::HTTP_OK,
        array $headers = [],
    ): Response {
        return new JsonResponse($content, $statusCode, $headers);
    }

    /**
     * @inheritDoc
     */
    public function forbidden(string $message): Response
    {
        $code = Response::HTTP_FORBIDDEN;

        return $this->createResponse(new SystemMessage($code, $message), $code);
    }

    /**
     * @inheritDoc
     */
    public function notFound(string $message): Response
    {
        $code = Response::HTTP_NOT_FOUND;

        return $this->createResponse(new SystemMessage($code, $message), $code);
    }

    /**
     * @inheritDoc
     */
    public function badRequest(string $message): Response
    {
        $code = Response::HTTP_BAD_REQUEST;

        return $this->createResponse(new SystemMessage($code, $message), $code);
    }

    /**
     * @inheritDoc
     */
    public function unauthorized(string $message): Response
    {
        $code = Response::HTTP_UNAUTHORIZED;

        return $this->createResponse(new SystemMessage($code, $message), $code);
    }
}
