<?php

namespace RashinMe\Service\Response;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactoryTest extends TestCase
{
    private ResponseFactory $responseFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->responseFactory = new ResponseFactory();
    }

    public function testCreateResponse(): void
    {
        $dataToConvert = ['a' => "one", "b" => "two"];
        $expectedData = json_encode($dataToConvert);
        $expectedCode = 200;
        $expectedHeadersKey = "Authorization";
        $expectedHeadersValue = "Bearer someToken";

        $response = $this
            ->responseFactory
            ->createResponse(
                $dataToConvert,
                $expectedCode,
                [$expectedHeadersKey => $expectedHeadersValue]
            );

        $this->assertEquals($expectedData, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertTrue(
            $response->headers->contains($expectedHeadersKey, $expectedHeadersValue),
            "Headers not contains expected header"
        );
    }

    public function testForbiddenResponse(): void
    {
        $expectedCode = Response::HTTP_FORBIDDEN;
        $message = "Access forbidden for your role";
        $expectedResponse = json_encode(['code' => $expectedCode, 'message' => $message]);

        $response = $this
            ->responseFactory
            ->forbidden($message);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public function testNotFoundResponse(): void
    {
        $expectedCode = Response::HTTP_NOT_FOUND;
        $message = "Page not found";
        $expectedResponse = json_encode(['code' => $expectedCode, 'message' => $message]);

        $response = $this
            ->responseFactory
            ->notFound($message);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public function testBadRequestResponse(): void
    {
        $expectedCode = Response::HTTP_BAD_REQUEST;
        $message = "Bad request";
        $expectedResponse = json_encode(['code' => $expectedCode, 'message' => $message]);

        $response = $this
            ->responseFactory
            ->badRequest($message);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public function testUnauthorizedResponse(): void
    {
        $expectedCode = Response::HTTP_UNAUTHORIZED;
        $message = "unauthorized user";
        $expectedResponse = json_encode(['code' => $expectedCode, 'message' => $message]);

        $response = $this
            ->responseFactory
            ->unauthorized($message);

        $this->assertEquals($expectedResponse, $response->getContent());
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }
}
