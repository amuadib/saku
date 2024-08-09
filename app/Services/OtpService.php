<?php

namespace App\Services;

class OtpService
{
    //https://wangyiguo.medium.com/a-very-easy-php-function-to-create-one-time-password-otp-60dc0055ba09
    public static function generateOTP(): string
    {
        $key = "hX2lkIjo5OSw6";
        $time_step = 60;
        $length = 6;

        $counter = floor(time() / $time_step);
        $data = pack("NN", 0, $counter);
        $hash = hash_hmac('sha1', $data, $key, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $value = unpack("N", substr($hash, $offset, 4));
        $otp = ($value[1] & 0x7FFFFFFF) % pow(10, $length);

        return str_pad(strval($otp), $length, '0', STR_PAD_LEFT);
    }
}
