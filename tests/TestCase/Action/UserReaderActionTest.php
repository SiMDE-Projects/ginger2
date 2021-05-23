<?php

namespace App\Test\TestCase\Action;

use App\Domain\User\Data\UserReaderData;
use App\Domain\User\Repository\UserReaderRepository;
use App\Test\Traits\AppTestTrait;
use PHPUnit\Framework\TestCase;

/**
 * Test.
 */
class UserReaderActionTest extends TestCase
{
    use AppTestTrait;

    /**
     * Test.
     *
     * @dataProvider provideUserReaderAction
     *
     * @param UserReaderData $user The user
     * @param array<mixed> $expected The expected result
     *
     * @return void
     */
    public function testUserReaderAction(UserReaderData $user, array $expected): void
    {
        // Mock the repository resultset
        $this->mock(UserReaderRepository::class)->method('getUserById')->willReturn($user);

        // Create request with method and url
        $request = $this->createRequest('GET', '/users/1');

        // Make request and fetch response
        $response = $this->app->handle($request);

        // Asserts
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonData($expected, $response);
    }

    /**
     * Provider.
     *
     * @return array<mixed> The data
     */
    public function provideUserReaderAction(): array
    {
        $user = new UserReaderData();
        $user->id = 1;
        $user->username = 'admin';
        $user->email = 'john.doe@example.com';
        $user->firstName = 'John';
        $user->lastName = 'Doe';

        return [
            'User' => [
                $user,
                [
                    'user_id' => 1,
                    'username' => 'admin',
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'email' => 'john.doe@example.com',
                ]
            ]
        ];
    }
}
