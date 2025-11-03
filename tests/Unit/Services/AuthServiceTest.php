<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\AuthServiceInterface;
use App\Contracts\CodeSenderInterface;
use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use App\Services\AuthService;
use Carbon\CarbonImmutable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthServiceInterface $authService;
    private UserRepositoryInterface $userRepository;
    private CodeSenderInterface $codeSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepositoryInterface::class);
        $this->codeSender = Mockery::mock(CodeSenderInterface::class);

        $this->authService = new AuthService(
            $this->userRepository,
            $this->codeSender
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_with_password_succeeds(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $result = $this->authService->loginWithPassword([
            'phone' => '+1234567890',
            'password' => 'password123'
        ]);

        $this->assertEquals($user, $result['user']);
        $this->assertIsString($result['token']);
    }

    public function test_login_with_password_fails_with_invalid_phone(): void
    {
        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+9999999999')
            ->andReturn(null);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials.');

        $this->authService->loginWithPassword([
            'phone' => '+9999999999',
            'password' => 'password123'
        ]);
    }

    public function test_login_with_password_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials.');

        $this->authService->loginWithPassword([
            'phone' => '+1234567890',
            'password' => 'wrongpassword'
        ]);
    }

    public function test_login_with_password_fails_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid credentials.');

        $this->authService->loginWithPassword([
            'phone' => '+1234567890',
            'password' => 'password123'
        ]);
    }

    public function test_login_with_code_succeeds(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 0,
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('resetActivationAttempts')
            ->with($user)
            ->andReturn($user);

        $result = $this->authService->loginWithCode([
            'phone' => '+1234567890',
            'code' => '123456'
        ]);

        $this->assertEquals($user, $result['user']);
        $this->assertIsString($result['token']);
    }


    public function test_login_with_code_fails_with_invalid_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 0,
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('incrementActivationAttempts')
            ->with($user)
            ->andReturn($user);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid activation code.');

        $this->authService->loginWithCode([
            'phone' => '+1234567890',
            'code' => '654321'
        ]);
    }

    public function test_login_with_code_fails_with_expired_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->subMinutes(1),
            'activation_attempts' => 0,
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Activation code has expired.');

        $this->authService->loginWithCode([
            'phone' => '+1234567890',
            'code' => '123456'
        ]);
    }

    public function test_request_activation_code_creates_new_user(): void
    {
        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn(null);

        $this->userRepository
            ->shouldReceive('create')
            ->andReturnUsing(function ($data) {
                return User::factory()->create($data);
            });

        $this->codeSender
            ->shouldReceive('sendCode')
            ->with('+1234567890', Mockery::type('string'));

        $this->authService->requestActivationCode('+1234567890');

        $this->assertTrue(true); // If we get here, no exception was thrown
    }

    public function test_request_activation_code_updates_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        $this->userRepository
            ->shouldReceive('findByPhone')
            ->with('+1234567890')
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('updateActivationCode')
            ->with($user, Mockery::type('string'), Mockery::type(CarbonImmutable::class))
            ->andReturn($user);

        $this->userRepository
            ->shouldReceive('updateLastActivationRequest')
            ->with($user)
            ->andReturn($user);

        $this->codeSender
            ->shouldReceive('sendCode')
            ->with('+1234567890', Mockery::type('string'));

        $this->authService->requestActivationCode('+1234567890');

        $this->assertTrue(true); // If we get here, no exception was thrown
    }
}
