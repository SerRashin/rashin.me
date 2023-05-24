<?php

declare(strict_types=1);

namespace RashinMe\Controller\Job;

use RashinMe\FunctionalTestCase;

class CreateControllerTest extends FunctionalTestCase
{
    private const API_URL = '/api/jobs';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testBadRequest(): void
    {
        $response = $this->sendRequest('POST', self::API_URL);

        self::assertEquals(
            '{"code":400,"message":"Request body is empty"}',
            $response->getContent()
        );
    }

    public function testAccessForbidden(): void
    {
        $response = $this->sendRequest('POST', self::API_URL, [
            'name' => 'name',
            'company' => [
                'name' => 'company.name',
                'url' => 'company.url',
            ],
            'description' => 'description',
            'type' => 'type',
            'from' => [
                'month' => 1,
                'year' => 2011,
            ],
            'to' => [
                'month' => 2,
                'year' => 2012,
            ],
        ]);

        self::assertEquals(
            '{"code":403,"message":"You\u0027re not allowed to create jobs"}',
            $response->getContent()
        );
    }
}
