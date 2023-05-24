<?php

declare(strict_types=1);

namespace RashinMe\Service\Response;

use Symfony\Component\HttpFoundation\Response;

/**
 * Response factory interface
 */
interface ResponseFactoryInterface
{
    /**
     * Creates response
     *
     * @param mixed|null              $content
     * @param int                     $statusCode
     * @param array<string, string> $headers
     *
     * @return Response
     */
    public function createResponse(
        mixed $content = null,
        int $statusCode = Response::HTTP_OK,
        array $headers = []
    ): Response;

    /**
     * Creates forbidden access response
     *
     * @param string $message
     *
     * @return Response
     */
    public function forbidden(string $message): Response;

    /**
     * Creates not-found response
     *
     * @param string $message
     *
     * @return Response
     */
    public function notFound(string $message): Response;

    /**
     * Creates bad-request response
     *
     * @param string $message
     *
     * @return Response
     */
    public function badRequest(string $message): Response;

    /**
     * Creates response for non authorized requests
     *
     * @param string $message
     *
     * @return Response
     */
    public function unauthorized(string $message): Response;
}
