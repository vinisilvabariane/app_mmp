<?php

namespace App\config;

class Env
{
    private static ?array $cache = null;

    public static function get(string $key, string $default = ''): string
    {
        $value = getenv($key);

        if (is_string($value) && $value !== '') {
            return $value;
        }

        if (self::$cache === null) {
            $envPath = dirname(__DIR__, 2) . '/.env';
            self::$cache = [];

            if (is_file($envPath)) {
                $parsed = parse_ini_file($envPath, false, INI_SCANNER_RAW);
                if (is_array($parsed)) {
                    self::$cache = $parsed;
                }
            }
        }

        return isset(self::$cache[$key]) ? (string) self::$cache[$key] : $default;
    }
}
