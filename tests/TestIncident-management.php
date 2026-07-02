<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\IncidentManagementController;
use App\Repository\IncidentManagementRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;

class TestIncidentManagement extends TestCase
{
    private $incidentManagementController;
    private $incidentManagementRepository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->incidentManagementRepository = $this->createMock(IncidentManagementRepository::class);
        $this->incidentManagementController = new IncidentManagementController($this->incidentManagementRepository);
    }

    public function testGetAllIncidentManagement()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM incident_management')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->incidentManagementRepository->expects($this->once())
            ->method('getAllIncidentManagement')
            ->willReturn([]);

        $response = $this->incidentManagementController->getAllIncidentManagement();
        $this->assertEquals([], $response);
    }

    public function testCreateIncidentManagement()
    {
        $incidentManagement = [
            'id' => 1,
            'name' => 'Test Incident Management',
            'description' => 'This is a test incident management'
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO incident_management (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->incidentManagementRepository->expects($this->once())
            ->method('createIncidentManagement')
            ->with($incidentManagement)
            ->willReturn($incidentManagement);

        $response = $this->incidentManagementController->createIncidentManagement($incidentManagement);
        $this->assertEquals($incidentManagement, $response);
    }

    public function testUpdateIncidentManagement()
    {
        $incidentManagement = [
            'id' => 1,
            'name' => 'Test Incident Management',
            'description' => 'This is a test incident management'
        ];

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE incident_management SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->incidentManagementRepository->expects($this->once())
            ->method('updateIncidentManagement')
            ->with($incidentManagement)
            ->willReturn($incidentManagement);

        $response = $this->incidentManagementController->updateIncidentManagement($incidentManagement);
        $this->assertEquals($incidentManagement, $response);
    }

    public function testDeleteIncidentManagement()
    {
        $id = 1;

        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM incident_management WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $this->incidentManagementRepository->expects($this->once())
            ->method('deleteIncidentManagement')
            ->with($id)
            ->willReturn(true);

        $response = $this->incidentManagementController->deleteIncidentManagement($id);
        $this->assertTrue($response);
    }
}



// IncidentManagementController.php

namespace App\Controller;

use App\Repository\IncidentManagementRepository;

class IncidentManagementController
{
    private $incidentManagementRepository;

    public function __construct(IncidentManagementRepository $incidentManagementRepository)
    {
        $this->incidentManagementRepository = $incidentManagementRepository;
    }

    public function getAllIncidentManagement()
    {
        return $this->incidentManagementRepository->getAllIncidentManagement();
    }

    public function createIncidentManagement($incidentManagement)
    {
        return $this->incidentManagementRepository->createIncidentManagement($incidentManagement);
    }

    public function updateIncidentManagement($incidentManagement)
    {
        return $this->incidentManagementRepository->updateIncidentManagement($incidentManagement);
    }

    public function deleteIncidentManagement($id)
    {
        return $this->incidentManagementRepository->deleteIncidentManagement($id);
    }
}