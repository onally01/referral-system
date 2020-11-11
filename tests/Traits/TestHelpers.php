<?php

namespace Tests\Traits;

use App\Models\User;

trait TestHelpers
{
    /**
     * Create a single User.
     *
     * @param array $attributes
     *
     * @return User
     */
    protected function createTestUser(array $attributes = []): User
    {
        $user = User::factory()->create($attributes);
        if (isset($attributes['referral_code'])){
            $referral = User::firstWhere('referral_code', $attributes['referral_code']);
            $referral->saveReferral($referral->id, $user->id);
        }
        return $user;
    }

}
