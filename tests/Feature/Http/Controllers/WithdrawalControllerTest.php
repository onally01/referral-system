<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class WithdrawalControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var int
     */
    protected $roleId;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->createTestUser();
    }

    /**
     * @covers \App\Http\Controllers\WithdrawalController::create
     */
    public function test_successful_withdrawal(): void
    {
        $user = $this->user;

        for ($i = 0; $i < 5; $i++) {
            $this->createTestUser([
                'referral_code' => $user->referral_code
            ]);
        }
        $request = [
          'amount' => 500
        ];
        $response = $this->actingAs($user)->json('POST', route('withdraw'), $request);
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * @covers \App\Http\Controllers\WithdrawalController::create
     */
    public function test_insufficient_wallet(): void
    {
        $request = [
            'amount' => 500
        ];
        $response = $this->actingAs($this->user)->json('POST', route('withdraw'), $request);
        $response->assertSeeText('Insufficient wallet!');
    }

    /**
     * @covers \App\Http\Controllers\WithdrawalController::create
     */
    public function test_withdrawal_without_valid_identity(): void
    {
        $user = $this->user;

        $referrals = collect();

        for ($i = 0; $i < 55; $i++) {
            $request = [
                'name' => $this->faker->name,
                'email' => $this->faker->email,
                'password' => 'zcqHEqbV7heEvz7K',
                'password_confirmation' => 'zcqHEqbV7heEvz7K',
                'referral_code' => $user->referral_code
            ];
            $referrals->push($this->json('POST', route('register'), $request));
        }
        $request = [
            'amount' => 50000
        ];
        $response = $this->actingAs($user)->json('POST', route('withdraw'), $request);

        $response->assertSeeText("You cannot withdraw above ".number_format(config('referral.withdrawal_limit')));
    }

    /**
     * @covers \App\Http\Controllers\WithdrawalController::create
     */
    public function test_withdrawal_with_valid_identity(): void
    {
        $user = $this->user;

        $request = [
            'identity' => base64_encode(UploadedFile::fake()->image('identity.jpg'))
        ];
        $this->actingAs($user)->json('POST', route('identity'), $request);

        for ($i = 0; $i < 55; $i++) {
            $this->createTestUser([
                'referral_code' => $user->referral_code
            ]);
        }
        $request = [
            'amount' => 50000
        ];
        $response = $this->actingAs($user)->json('POST', route('withdraw'), $request);
        $response->assertStatus(Response::HTTP_OK);
    }
}
