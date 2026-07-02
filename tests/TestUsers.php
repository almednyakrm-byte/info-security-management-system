<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\UsersController;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class TestUsers extends TestCase
{
    private $usersController;
    private $userRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->usersController = new UsersController($this->userRepository, $this->entityManager);
    }

    public function testGetUsers()
    {
        $users = [
            new User('John Doe', 'john@example.com'),
            new User('Jane Doe', 'jane@example.com'),
        ];

        $this->userRepository->expects($this->once())
            ->method('findAll')
            ->willReturn($users);

        $response = $this->usersController->getUsers();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($users), $response->getContent());
    }

    public function testGetUser()
    {
        $user = new User('John Doe', 'john@example.com');

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($user);

        $response = $this->usersController->getUser(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($user), $response->getContent());
    }

    public function testPostUser()
    {
        $user = new User('John Doe', 'john@example.com');

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user)
            ->willReturn($user);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->usersController->postUser($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($user), $response->getContent());
    }

    public function testPutUser()
    {
        $user = new User('John Doe', 'john@example.com');

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($user);

        $this->userRepository->expects($this->once())
            ->method('save')
            ->with($user)
            ->willReturn($user);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->usersController->putUser(1, $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($user), $response->getContent());
    }

    public function testDeleteUser()
    {
        $user = new User('John Doe', 'john@example.com');

        $this->userRepository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($user);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($user);

        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willThrowException(new OptimisticLockException());

        $response = $this->usersController->deleteUser(1);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('User deleted successfully', $response->getContent());
    }
}



// App\Controller\UsersController.php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class UsersController
{
    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return new JsonResponse($users);
    }

    public function getUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        return new JsonResponse($user);
    }

    public function postUser(Request $request): JsonResponse
    {
        $user = new User($request->request->get('name'), $request->request->get('email'));
        $this->userRepository->save($user);
        return new JsonResponse($user, Response::HTTP_CREATED);
    }

    public function putUser(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        $user->setName($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $this->userRepository->save($user);
        return new JsonResponse($user);
    }

    public function deleteUser(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        $this->entityManager->remove($user);
        try {
            $this->entityManager->flush();
        } catch (OptimisticLockException $e) {
            return new JsonResponse('User could not be deleted due to optimistic locking', Response::HTTP_CONFLICT);
        }
        return new JsonResponse('User deleted successfully');
    }
}