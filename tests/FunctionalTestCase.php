<?php

declare(strict_types=1);

namespace RashinMe;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use RashinMe\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class FunctionalTestCase extends WebTestCase
{
    private KernelBrowser $browser;
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->browser = self::createClient();

        $this->initDatabase(self::$kernel);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @param string $username
     * @param string $password
     *
     * @return void
     *
     * @throws \Exception
     */
    public function login(string $username, string $password): void
    {
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail($username);

        if ($user === null) {
            return;
        }

        $this->browser->loginUser($user);
    }

    /**
     * @param string               $method
     * @param string               $uri
     * @param array<string, mixed> $parameters
     *
     * @return Response
     */
    final protected function sendRequest(string $method, string $uri, array $parameters = []): Response
    {
        $this->browser->jsonRequest($method, $uri, $parameters);

        return $this->browser->getResponse();
    }


    final protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    private function initDatabase(KernelInterface $kernel)
    {
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $metaData = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($this->entityManager);
        $schemaTool->updateSchema($metaData);
    }
}
