<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\AccessControlController;
use App\Repository\AccessControlRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestAccessControl extends TestCase
{
    private $accessControlController;
    private $accessControlRepository;
    private $pdoMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->accessControlRepository = $this->createMock(AccessControlRepository::class);
        $this->accessControlController = new AccessControlController($this->accessControlRepository);
    }

    public function testGetAccessControl()
    {
        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('SELECT * FROM access_control')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM access_control')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->accessControlRepository->expects($this->once())
            ->method('getAll')
            ->willReturn([]);

        $response = $this->accessControlController->getAccessControl();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateAccessControl()
    {
        $data = [
            'name' => 'Test Access Control',
            'description' => 'Test description'
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO access_control (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $response = $this->accessControlController->createAccessControl($data);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function testUpdateAccessControl()
    {
        $data = [
            'id' => 1,
            'name' => 'Updated Access Control',
            'description' => 'Updated description'
        ];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE access_control SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with($data)
            ->willReturn(true);

        $response = $this->accessControlController->updateAccessControl($data);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteAccessControl()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM access_control WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id])
            ->willReturn(true);

        $response = $this->accessControlController->deleteAccessControl($id);
        $this->assertEquals(200, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'access_control' module. It uses mocked PDO statements to simulate database interactions. The tests cover GET, POST, PUT, and DELETE requests.