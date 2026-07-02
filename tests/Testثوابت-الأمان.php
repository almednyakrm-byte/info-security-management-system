<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PHPUnit\Framework\MockObject\MockObject;
use App\Repository\ThobatAlamanRepository;
use App\Service\ThobatAlamanService;

class TestThobatAlaman extends TestCase
{
    private $pdoMock;
    private $repositoryMock;
    private $serviceMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock('PDO');
        $this->repositoryMock = $this->createMock(ThobatAlamanRepository::class);
        $this->serviceMock = $this->createMock(ThobatAlamanService::class);
    }

    public function testGetThobatAlaman()
    {
        $this->repositoryMock->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'ثابت الأمان 1'],
                ['id' => 2, 'name' => 'ثابت الأمان 2'],
            ]);

        $this->serviceMock->expects($this->once())
            ->method('getThobatAlaman')
            ->willReturn($this->repositoryMock);

        $response = $this->serviceMock->getThobatAlaman();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPostThobatAlaman()
    {
        $data = ['name' => 'ثابت الأمان 3'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO thobat_alaman (name) VALUES (:name)')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name']]);

        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($data);

        $this->serviceMock->expects($this->once())
            ->method('postThobatAlaman')
            ->with($data)
            ->willReturn($this->pdoMock);

        $response = $this->serviceMock->postThobatAlaman($data);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testPutThobatAlaman()
    {
        $data = ['id' => 1, 'name' => 'ثابت الأمان 1'];

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('UPDATE thobat_alaman SET name = :name WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['name' => $data['name'], 'id' => $data['id']]);

        $this->repositoryMock->expects($this->once())
            ->method('update')
            ->with($data);

        $this->serviceMock->expects($this->once())
            ->method('putThobatAlaman')
            ->with($data)
            ->willReturn($this->pdoMock);

        $response = $this->serviceMock->putThobatAlaman($data);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteThobatAlaman()
    {
        $id = 1;

        $this->pdoMock->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM thobat_alaman WHERE id = :id')
            ->willReturn($this->createMock('PDOStatement'));

        $this->pdoMock->expects($this->once())
            ->method('execute')
            ->with(['id' => $id]);

        $this->repositoryMock->expects($this->once())
            ->method('delete')
            ->with($id);

        $this->serviceMock->expects($this->once())
            ->method('deleteThobatAlaman')
            ->with($id)
            ->willReturn($this->pdoMock);

        $response = $this->serviceMock->deleteThobatAlaman($id);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file uses PHPUnit to test the CRUD API operations on the 'ثوابت الأمان' module. It uses mocked PDO statements to simulate database interactions. The tests cover GET, POST, PUT, and DELETE requests.