<?php

namespace App\Traits;

use App\Notifications\sendVerifySMS;
use Exception;

trait MustVerifyMobile
{
    /**
     * @return bool
     */
    public function hasVerifiedMobile(): bool
    {
        return ! is_null($this->mobile_verified_at);
    }

    /**
     * @return bool
     */
    public function markMobileAsVerified(): bool
    {
        return $this->forceFill([
            'verification_code' => NULL,
            'mobile_verified_at' => $this->freshTimestamp(),
            'attempts_left' => 0,
        ])->save();
    }

    /**
     * @throws Exception
     */
    public function sendMobileVerificationNotification(bool $newData = false): void
    {
        if($newData)
        {
            $this->forceFill([
                'verification_code' => random_int(111111, 999999),
                'attempts_left' => config('verification.max_attempts'),
                'verification_code_sent_at' => now(),
            ])->save();
        }
        $this->notify(new sendVerifySMS);
    }
}
