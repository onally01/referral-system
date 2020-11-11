<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController@register
     */
    public function test_successful_registration(): void
    {
        $request = [
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => 'zcqHEqbV7heEvz7K',
            'password_confirmation' => 'zcqHEqbV7heEvz7K',
        ];
        $response = $this->json('POST', route('register'), $request);
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController@register
     */
    public function test_successful_registration_request_with_referral_code(): void
    {
        $user = collect();

        for ($i = 0; $i < 5; $i++) {
            $request = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => 'zcqHEqbV7heEvz7K',
                'password_confirmation' => 'zcqHEqbV7heEvz7K',
            ];
            $user->push($this->json('POST', route('register'), $request));
        }
        $request['referral_code'] = User::first()->referral_code;

        $response = $this->json('POST', route('register'), $request);
        $response->assertRedirect('home');
    }

    /**
     * @covers \App\Http\Controllers\Auth\RegisterController@register
     */
    public function test_registration_validations(): void
    {
        $testValidation = function (array $request, string $errorKey) {
            $response = $this->json('POST', route('register'), $request);
            $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
            $response->assertJsonValidationErrors([$errorKey]);
        };
        $testValidation(['name' => null, 'email' => $this->faker->email, 'password' => 'secret', 'password_confirmation' => 'secret'], 'name');
        $testValidation(['email' => null, 'name' => $this->faker->name], 'email');
        $testValidation(['password' => null, 'name' => $this->faker->name], 'password');
        $testValidation(['password' => 'jdjjjjdjjd', 'password_confirmation' => 'jdjjdjjdddd'], 'password');
        $testValidation(['referral_code' => 'djjdjjjdjdj', 'name' => $this->faker->name], 'referral_code');
    }
}
