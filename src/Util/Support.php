<?php


namespace Crmdesenvolvimentos\PixSicredi\Util;

use Closure;
use ArrayAccess;

final class Support
{

    public static function camel_case($name): string
    {
        $string = str_replace(['-', '_'], ' ', $name);
        return str_replace(' ', '', lcfirst(ucwords($string)));
    }


    public static function validateCnpjOrCpf($value): bool
    {
        $length = strlen(preg_replace('/\D/', '', $value));
        if ($length == 14) {
            return self::validateCnpj($value);
        }
        return self::validateCpf($value);
    }


    public static function validateCpf($value): bool
    {
        $c = preg_replace('/\D/', '', $value);

        if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
            return false;
        }

        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--) ;

        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }


    public static function validateCnpj($value): bool
    {
        $c = preg_replace('/\D/', '', $value);

        $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        if (strlen($c) != 14) {
            return false;
        } elseif (preg_match("/^{$c[0]}{14}$/", $c) > 0) {
            return false;
        }

        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]) ;

        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]) ;

        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
            return false;
        }

        return true;
    }


    public static function validateCep($value): bool
    {
        return strlen(preg_replace('/\D/', '', $value)) == 8;
    }


    public static function validateDate($date)
    {
        try {
            if ((! is_string($date) && ! is_numeric($date)) || strtotime($date) === false) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }

        $date = date_parse($date);

        return checkdate($date['month'], $date['day'], $date['year']);
    }


    public static function onlyNumbers($value)
    {
        return preg_replace('/\D/', '', $value);
    }


    public static function toDateTime($dateTime, $format = 'Y-m-d H:i:s')
    {
        if (!is_null($dateTime))
            $date = date($format, strtotime(str_replace('/', '-', $dateTime)));
        else
            $date = null;

        return $date === false ? null : $date;
    }


    public static function data_get($target, $key, $default = null)
    {
        if ( !function_exists('value') ) {
            function value($value, ...$args)
            {
                return $value instanceof Closure ? $value(...$args) : $value;
            }
        }

        if (is_null($key)) {
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);

        foreach ($key as $i => $segment) {
            unset($key[$i]);

            if (is_null($segment)) {
                return $target;
            }

            if ($segment === '*') {

                if (!is_iterable($target)) {
                    return value($default);
                }

                $result = [];

                foreach ($target as $item) {
                    $result[] = self::data_get($item, $key);
                }

                return in_array('*', $key) ? self::collapse($result) : $result;
            }

            if ((is_array($target) || $target instanceof ArrayAccess) && self::exists($target, $segment)) {
                $target = $target[$segment];
            } elseif (is_object($target) && isset($target->{$segment})) {
                $target = $target->{$segment};
            } else {
                return value($default);
            }
        }

        return $target;
    }


    public static function exists($array, $key): bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        if (is_float($key)) {
            $key = (string)$key;
        }

        return array_key_exists($key, $array);
    }


    public static function collapse($array): array
    {
        $results = [];

        foreach ($array as $values) {
            if (!is_array($values)) {
                continue;
            }

            $results[] = $values;
        }

        return array_merge([], ...$results);
    }


    public static function dot($array, $prepend = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {

            if (is_object($value)){
                $value = (array) $value;
            }

            if (is_array($value) && ! empty($value)) {
                $results = array_merge($results, static::dot($value, $prepend.$key.'.'));
            } else {
                $results[$prepend.$key] = $value;
            }
        }

        return $results;
    }


    public static function undot($array)
    {
        $results = [];

        foreach ($array as $key => $value) {
            static::set($results, $key, $value);
        }

        return $results;
    }


    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        foreach ($keys as $i => $key) {
            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }


    public static function length($value, $encoding = null): int
    {
        if ($encoding) {
            return mb_strlen($value, $encoding);
        }

        return mb_strlen($value);
    }


    public static function substr($string, $start, $length = null): string
    {
        return mb_substr($string, $start, $length, 'UTF-8');
    }


    public static function upper($value): string
    {
        return mb_strtoupper($value, 'UTF-8');
    }


    public static function lower($value): string
    {
        return mb_strtolower($value, 'UTF-8');
    }


    public static function get($value, $default = null)
    {
        return self::isNotNull($value) ? $value : $default;
    }


    public static function isNotNull( $value ): bool
    {
        if ( is_array($value) || is_object($value) ){
            return !empty($value);
        }

        if ($value == 'null') return false;

        return !is_null( $value );
    }


    public static function start($value, $prefix): string
    {
        $quoted = preg_quote($prefix, '/');

        return $prefix.preg_replace('/^(?:'.$quoted.')+/u', '', $value);
    }


    public static function finish($value, $cap): string
    {
        $quoted = preg_quote($cap, '/');

        return preg_replace('/(?:'.$quoted.')+$/u', '', $value).$cap;
    }


    public static function startsWith($haystack, $needles)
    {
        if (! is_iterable($needles)) {
            $needles = [$needles];
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && str_starts_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }


    public static function endsWith($haystack, $needles)
    {
        if (! is_iterable($needles)) {
            $needles = (array) $needles;
        }

        foreach ($needles as $needle) {
            if ((string) $needle !== '' && str_ends_with($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

}
