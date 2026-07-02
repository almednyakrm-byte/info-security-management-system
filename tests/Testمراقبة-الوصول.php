<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مراقبة الوصولController;
use App\Repository\مراقبة الوصولRepository;
use App\Entity\مراقبة الوصول;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;

class Testمراقبة_الوصول extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(مراقبة الوصولRepository::class);
        $this->entityManager = $this->createMock(\Doctrine\ORM\EntityManagerInterface::class);
        $this->entityManager->method('getRepository')->willReturn($this->repository);
        $this->controller = new مراقبة الوصولController($this->entityManager);
    }

    public function testGetAll()
    {
        $expectedResponse = ['data' => []];
        $this->repository->method('findAll')->willReturn([]);
        $response = $this->controller->getAll();
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testGetOne()
    {
        $expectedResponse = ['data' => []];
        $this->repository->method('find')->willReturn(new مراقبة الوصول());
        $response = $this->controller->getOne(1);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testCreate()
    {
        $request = new Request([], [], ['json' => ['name' => 'Test', 'description' => 'Test description']]);
        $expectedResponse = ['data' => ['id' => 1, 'name' => 'Test', 'description' => 'Test description']];
        $this->repository->method('save')->willReturn(new مراقبة الوصول());
        $response = $this->controller->create($request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testUpdate()
    {
        $request = new Request([], [], ['json' => ['name' => 'Test', 'description' => 'Test description']]);
        $expectedResponse = ['data' => ['id' => 1, 'name' => 'Test', 'description' => 'Test description']];
        $this->repository->method('find')->willReturn(new مراقبة الوصول());
        $this->repository->method('save')->willReturn(new مراقبة الوصول());
        $response = $this->controller->update(1, $request);
        $this->assertEquals($expectedResponse, $response->getContent());
    }

    public function testDelete()
    {
        $expectedResponse = ['message' => 'Deleted successfully'];
        $this->repository->method('find')->willReturn(new مراقبة الوصول());
        $response = $this->controller->delete(1);
        $this->assertEquals($expectedResponse, $response->getContent());
    }
}


Note: This code assumes that the `مراقبة الوصولController` class has methods `getAll`, `getOne`, `create`, `update`, and `delete` which handle the respective CRUD operations. The `مراقبة الوصولRepository` class has methods `findAll`, `find`, `save`, and `delete` which are used by the controller to interact with the database. The `مراقبة الوصول` entity class is assumed to have properties `id`, `name`, and `description`.