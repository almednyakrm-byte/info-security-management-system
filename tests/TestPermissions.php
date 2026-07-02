<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Controller\PermissionsController;
use App\Repository\PermissionsRepository;
use App\Entity\Permission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestPermissions extends TestCase
{
    private $permissionsController;
    private $permissionsRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->permissionsRepository = $this->createMock(PermissionsRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->permissionsController = new PermissionsController($this->permissionsRepository, $this->entityManager);
    }

    public function testGetPermissions(): void
    {
        $permissions = [
            new Permission('permission1'),
            new Permission('permission2'),
        ];

        $this->permissionsRepository
            ->method('findAll')
            ->willReturn($permissions);

        $response = $this->permissionsController->getPermissions();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($permissions), $response->getContent());
    }

    public function testGetPermission(): void
    {
        $permission = new Permission('permission1');

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn($permission);

        $response = $this->permissionsController->getPermission(1);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($permission), $response->getContent());
    }

    public function testGetPermissionNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->permissionsController->getPermission(1);
    }

    public function testCreatePermission(): void
    {
        $permission = new Permission('permission1');

        $this->permissionsRepository
            ->method('save')
            ->with($permission)
            ->willReturn($permission);

        $request = new Request([], [], [], [], [], ['json' => ['name' => 'permission1']]);

        $response = $this->permissionsController->createPermission($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($permission), $response->getContent());
    }

    public function testCreatePermissionInvalidRequest(): void
    {
        $request = new Request([], [], [], [], [], ['json' => []]);

        $response = $this->permissionsController->createPermission($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function testUpdatePermission(): void
    {
        $permission = new Permission('permission1');

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn($permission);

        $this->permissionsRepository
            ->method('save')
            ->with($permission)
            ->willReturn($permission);

        $request = new Request([], [], [], [], [], ['json' => ['name' => 'permission2']]);

        $response = $this->permissionsController->updatePermission(1, $request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($permission), $response->getContent());
    }

    public function testUpdatePermissionNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $request = new Request([], [], [], [], [], ['json' => ['name' => 'permission2']]);

        $this->permissionsController->updatePermission(1, $request);
    }

    public function testDeletePermission(): void
    {
        $permission = new Permission('permission1');

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn($permission);

        $this->permissionsRepository
            ->method('remove')
            ->with($permission)
            ->willReturn(null);

        $response = $this->permissionsController->deletePermission(1);

        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeletePermissionNotFound(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->permissionsRepository
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->permissionsController->deletePermission(1);
    }
}


This test file covers the following scenarios:

- `testGetPermissions`: Verifies that the `getPermissions` method returns a list of permissions.
- `testGetPermission`: Verifies that the `getPermission` method returns a single permission.
- `testGetPermissionNotFound`: Verifies that the `getPermission` method throws a `NotFoundHttpException` when the permission is not found.
- `testCreatePermission`: Verifies that the `createPermission` method creates a new permission.
- `testCreatePermissionInvalidRequest`: Verifies that the `createPermission` method returns a `BadRequestHttpException` when the request is invalid.
- `testUpdatePermission`: Verifies that the `updatePermission` method updates an existing permission.
- `testUpdatePermissionNotFound`: Verifies that the `updatePermission` method throws a `NotFoundHttpException` when the permission is not found.
- `testDeletePermission`: Verifies that the `deletePermission` method deletes an existing permission.
- `testDeletePermissionNotFound`: Verifies that the `deletePermission` method throws a `NotFoundHttpException` when the permission is not found.