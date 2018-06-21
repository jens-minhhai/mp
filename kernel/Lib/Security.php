<?php

namespace Kernel\Lib;

class Security
{
    public static function str($length = 8, $level = 1)
    {
        switch ($level) {
            case 1:
                $chars = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!@#$%^&*()_-=+;:,.?';
                break;
            case 5:
                $chars = '!#$%&()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[]^_`abcdefghijklmnopqrstuvwxyz{|}~';
                break;
            default:
                $chars = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789';
                break;
        }

        return substr(str_shuffle($chars), 0, $length);
    }

    public static function unique($time = true)
    {
        return $time ? dechex(time()) : sha1(uniqid(rand(), true));
    }

    public static function hash($string = '', $cost = 11)
    {
        return password_hash($string, PASSWORD_BCRYPT, compact('cost'));
    }

    public static function encrypt($message, $key, $encode = 1)
    {
        $method = 'aes-256-ctr';
        $nonceSize = openssl_cipher_iv_length($method);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt($message, $method, $key, OPENSSL_RAW_DATA, $nonce);

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        $ciphertext = $nonce.$ciphertext;
        if ($encode) {
            while ($encode--) {
                $ciphertext = base64_encode($ciphertext);
            }
        }

        return $ciphertext;
    }

    public static function decrypt($message, $key, $encoded = 1)
    {
        if ($encoded) {
            while ($encoded--) {
                $message = base64_decode($message, true);
                if ($message === false) {
                    break;
                }
            }
        }

        $method = 'aes-256-ctr';
        $nonceSize = openssl_cipher_iv_length($method);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        return openssl_decrypt($ciphertext, $method, $key, OPENSSL_RAW_DATA, $nonce);
    }

    public static function sha1(string $string, string $key = '')
    {
        $key = hash('haval160,4', $key);

        return sha1(md5($key . $string) . $key);
    }
}
