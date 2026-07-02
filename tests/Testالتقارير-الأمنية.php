<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ReportsController;
use App\Repository\ReportsRepository;
use App\Entity\Reports;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;

class Testالتقارير-الأمنية extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ReportsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->controller = new ReportsController($this->repository, $this->entityManager);
    }

    public function testGetReports(): void
    {
        $reports = [new Reports()];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($reports);

        $response = $this->controller->getReports();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);

        $response = $this->controller->getReport(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetReportNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->getReport(1);
    }

    public function testCreateReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('save')
            ->with($report);

        $request = new Request();
        $request->request->set('title', 'Report Title');
        $request->request->set('description', 'Report Description');

        $response = $this->controller->createReport($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($report);

        $request = new Request();
        $request->request->set('title', 'Report Title');
        $request->request->set('description', 'Report Description');

        $response = $this->controller->updateReport(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testUpdateReportNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request();
        $request->request->set('title', 'Report Title');
        $request->request->set('description', 'Report Description');

        $this->controller->updateReport(1, $request);
    }

    public function testDeleteReport(): void
    {
        $report = new Reports();
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($report);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($report);

        $response = $this->controller->deleteReport(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteReportNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->controller->deleteReport(1);
    }
}


This test file covers the following scenarios:

1.  **GET Reports**: Tests that the `getReports` method returns a list of reports.
2.  **GET Report**: Tests that the `getReport` method returns a single report by ID.
3.  **GET Report Not Found**: Tests that the `getReport` method throws a `NotFoundHttpException` when the report is not found.
4.  **CREATE Report**: Tests that the `createReport` method creates a new report.
5.  **UPDATE Report**: Tests that the `updateReport` method updates an existing report.
6.  **UPDATE Report Not Found**: Tests that the `updateReport` method throws a `NotFoundHttpException` when the report is not found.
7.  **DELETE Report**: Tests that the `deleteReport` method deletes a report.
8.  **DELETE Report Not Found**: Tests that the `deleteReport` method throws a `NotFoundHttpException` when the report is not found.

Note that this test file uses the `createMock` method to create mock objects for the `ReportsRepository` and `EntityManagerInterface` classes. This allows us to isolate the dependencies of the `ReportsController` class and test its behavior in isolation.