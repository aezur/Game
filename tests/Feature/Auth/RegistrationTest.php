<?php

namespace Tests\Feature\Auth;

use App\Listeners\CreateNewLudus;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private $validName = 'Test User';
    private $validEmail = 'test@example.com';
    private $validPassword = 'iJbCsJ4Hx*37';
    private $invalidName = 'X';
    private $invalidEmail = 'X@X';
    private $invalidPassword = 'X';

    public function testRegistrationScreenCanBeRendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function testNewUsersCanRegister(): void
    {
        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function testNewUsersCanNotRegisterWithInvalidName(): void
    {
        /**
         * Name rules are defined in app/Http/Controllers/Auth/RegisteredUserController.php
         * Minimum length is 5 characters, maximum length is 64 characters.
         */
        $response = $this->post('/register', [
            'name' => 'X',
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function testNewUsersCanNotRegisterWithInvalidPassword(): void
    {
        /**
         * Password rules are defined in app/Http/Controllers/Auth/RegisteredUserController.php
         * Minimum length is 8 characters, must contain at least one uppercase and one lowercase letter,
         * and must contain at least one number.
         */
        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->invalidPassword,
            'password_confirmation' => $this->invalidPassword,
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function testNewUsersCanNotRegisterWithMismatchedPasswords(): void
    {
        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->invalidPassword,
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function testNewUsersCanNotRegisterWithTakenEmail(): void
    {
        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        $this->assertAuthenticated();
        auth()->logout();

        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function testNewUsersCanNotRegisterWithInvalidEmail(): void
    {
        $response = $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->invalidEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function testRegistrationEventIsDispatched()
    {
        Event::fake(Registered::class);

        $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        Event::assertDispatched(Registered::class, 1);
    }

    public function testEmailVerificationListenerIsBoundToRegistrationEvent()
    {
        Event::fake();
        Event::assertListening(
            Registered::class,
            SendEmailVerificationNotification::class,
        );
    }

    public function testEmailVerificationMailIsSentIfRequired()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        if (auth()->user() instanceof MustVerifyEmail) {
            Notification::assertSentTo(auth()->user(), VerifyEmail::class);
        } else {
            Notification::assertNotSentTo(auth()->user(), VerifyEmail::class);
        }
    }

    public function testLudusCreationListenerIsBoundToRegistrationEvent()
    {
        Event::fake();
        Event::assertListening(
            Registered::class,
            CreateNewLudus::class,
        );
    }

    public function testLudusIsCreatedWhenNewUsersRegister()
    {
        $ludusCount = \App\Models\Ludus::count();

        $this->post('/register', [
            'name' => $this->validName,
            'email' => $this->validEmail,
            'password' => $this->validPassword,
            'password_confirmation' => $this->validPassword,
        ]);

        self::assertEquals($ludusCount + 1, \App\Models\Ludus::count());
    }
}
