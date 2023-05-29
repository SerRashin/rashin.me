<?php

declare(strict_types=1);

namespace RashinMe;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Tools\SchemaTool;
use RashinMe\Entity\File;
use RashinMe\Entity\Section;
use RashinMe\Entity\User;
use RashinMe\Repository\SectionRepository;
use RashinMe\Repository\StorageRepository;
use RashinMe\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
     * @return User|null
     */
    public function login(string $username, string $password = null): ?User
    {
        $userRepository = self::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneByEmail($username);

        if ($user === null) {
            return null;
        }

        $this->browser->loginUser($user);

        return $user;
    }

    public function registerUser(string $email, ?string $password = null)
    {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $password = $password ?? 'password';

        $user = new User(
            $email,
            $password,
            'TestUserName',
            'TestUserLastName',
        );

        $hashedPassword = $userPasswordHasher->hashPassword(
            $user,
            $password
        );

        $user->changePassword($hashedPassword);

        $userRepository->save($user);
    }

    public function registerAdmin(string $email, ?string $password = null)
    {
        $userRepository = self::getContainer()->get(UserRepository::class);
        $userPasswordHasher = self::getContainer()->get(UserPasswordHasherInterface::class);

        $password = $password ?? 'password';

        $user = new User(
            $email,
            $password,
            'TestUserName',
            'TestUserLastName',
        );

        $user->setRoles(['ROLE_ADMIN']);

        $hashedPassword = $userPasswordHasher->hashPassword(
            $user,
            $password
        );

        $user->changePassword($hashedPassword);

        $userRepository->save($user);
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

    /**
     * Creates fake file in storage
     *
     * @return File
     */
    final protected function createStorageFile(): File
    {
        $storageRepository = self::getContainer()->get(StorageRepository::class);

        $file = new File(
            'testFileName.jpg',
            '/path/to/file/',
            'image/png',
            300,
        );

        $storageRepository->save($file);

        return $file;
    }

    /**
     * @param object ...$entities
     *
     * @return void
     */
    protected function saveEntities(object ...$entities): void
    {
        $em = $this->getEntityManager();
        foreach ($entities as $entity) {
            $em->persist($entity);
        }
        $em->flush();
    }

    /**
     * Check if response contains some data
     *
     * @param array<array-key, mixed> $data Expected data
     *
     * @return void
     */
    final protected function assertJsonContainsData(array $data): void
    {
        $response = $this->browser->getResponse();
        $jsonString = (string)$response->getContent();
        $responseData = (array) json_decode($jsonString, true);

        $actualData = $this->getActualData($data, $responseData);

        $this->assertEquals($data, $actualData);
    }

    private function getActualData(array $data, array $response)
    {
        $actualData = [];
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $response)) {
                $val = $data[$key];

                if (is_array($val)) {
                    $actualData[$key] = $this->getActualData(
                        $val,
                        $response[$key]
                    );
                } else {
                    $actualData[$key] = $response[$key];
                }
            }
        }

        return $actualData;
    }

    /**
     * @param int $code
     *
     * @return void
     */
    final protected function assertStatusCodeEqualsTo(int $code)
    {
        $response = $this->browser->getResponse();

        $this->assertEquals($code, $response->getStatusCode());
    }

    /**
     * Check if response equals some data
     *
     * @param array<array-key, mixed> $data Expected data
     *
     * @return void
     */
    final protected function assertJsonEqualsData(array $data): void
    {
        $response = $this->browser->getResponse();
        $jsonString = (string)$response->getContent();
        $responseData = json_decode($jsonString, true);

        $this->assertEquals($data, $responseData);
    }


    final protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    /** @var array<ClassMetadata<object>> */
    private static array $cachedMetadata = [];

    private function initDatabase(KernelInterface $kernel)
    {
        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $schema = new SchemaTool($this->getEntityManager());

        if (self::$cachedMetadata === []) {
            self::$cachedMetadata = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        }

        $schema->createSchema(static::$cachedMetadata);
    }
}
