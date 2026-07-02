<?php

namespace App\Tests\Unit\Auth;

use App\Auth\Auth;
use App\Auth\User;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DBALException;

class TestAuth extends TestCase
{
    private $auth;
    private $connectionMock;

    protected function setUp(): void
    {
        $this->connectionMock = $this->createMock(Connection::class);
        $this->auth = new Auth($this->connectionMock);
    }

    public function testLogin(): void
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([
                ['id' => 1, 'username' => $username, 'password' => $password],
            ]);

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ? AND password = ?', [$username, $password])
            ->willReturn([
                ['id' => 1, 'username' => $username, 'password' => $password],
            ]);

        $this->auth->login($username, $password);

        $this->assertTrue($this->auth->isLoggedIn());
    }

    public function testRegister(): void
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeStatement')
            ->with('INSERT INTO users (username, password) VALUES (?, ?)', [$username, $password]);

        $this->auth->register($username, $password);

        $this->assertTrue($this->auth->isLoggedIn());
    }

    public function testLoginFailed(): void
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeQuery')
            ->with('SELECT * FROM users WHERE username = ?', [$username])
            ->willReturn([]);

        $this->auth->login($username, $password);

        $this->assertFalse($this->auth->isLoggedIn());
    }

    public function testRegisterFailed(): void
    {
        $username = 'test_user';
        $password = 'test_password';

        $this->connectionMock
            ->expects($this->once())
            ->method('executeStatement')
            ->willThrowException(new DBALException('Mocked exception'));

        $this->auth->register($username, $password);

        $this->assertFalse($this->auth->isLoggedIn());
    }
}


This test suite covers the following scenarios:

- `testLogin`: Tests successful login with correct credentials.
- `testRegister`: Tests successful registration with correct credentials.
- `testLoginFailed`: Tests failed login with incorrect credentials.
- `testRegisterFailed`: Tests failed registration with a mocked exception.

Note: You will need to replace the `App\Auth\Auth` and `App\Auth\User` classes with your actual class names. Also, make sure to configure the database connection in your `Auth` class to use the mocked connection.