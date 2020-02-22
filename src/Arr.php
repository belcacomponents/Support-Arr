<?php

namespace Belca\Support;

/**
 * Static functions to handle arrays.
 */
class Arr
{
    /**
     * Trims string values of an array. Keys of the array are saving.
     * The function does not handle nested arrays.
     *
     * @param  array $array
     * @return array
     */
    public static function trim(array $array): array
    {
        return array_map(function ($value) {
            return is_string($value) ? trim($value) : $value;
        }, $array);
    }

    /**
     * Removes each values of an array where the empty value.
     * Uses the function 'empty'. Keys of the array are saving.
     *
     * @param  array $array
     * @return array
     */
    public static function removeEmpty(array $array): array
    {
        return array_filter($array, function ($value) {
            return empty($value) === false;
        });
    }

    /**
     * Removes each values of an array where a value is 'null'.
     * Uses the function 'is_null'. Keys of the array are saving.
     *
     * @param  array $array
     * @return array
     */
    public static function removeNull(array $array): array
    {
        return array_filter($array, function ($value) {
            return is_null($value) === false;
        });
    }

    /**
     * Removes each values of an array with the value is 'null'
     * or other values that are not scalar values.
     * Scalar values are those containing an integer, float, string
     * or boolean.
     *
     * @param  array $array
     * @return array
     */
    public static function removeNotScalar(array $array): array
    {
        return array_filter($array, 'is_scalar');
    }

    /**
     * Recursively removes the empty values of a multidimensional array.
     * If $resetIndex is 'true' then resets integer keys of each array,
     * including keys set manually.
     * If the function takes not array then it returns an unchanged value.
     *
     * @param  mixed   $array
     * @param  bool    $resetIndex
     * @return mixed
     */
    public static function removeEmptyRecurcive($array, bool $resetIndex = true)
    {
        if (is_array($array) === false) {
            return $array;
        }

        foreach ($array as $key => &$value) {
            if (empty($value)) {
                unset($array[$key]);
            } else {
                if (is_array($value)) {
                    $value = self::removeEmptyRecurcive($value, $resetIndex);

                    if (empty($value)) {
                        unset($array[$key]);
                    }

                    // Resets keys when they are integer keys
                    // and they should be reset.
                    elseif (self::isIntKeys($value) && $resetIndex) {
                        $value = array_values($value);
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Recursively removes the null values of a multidimensional array.
     * If $resetIndex is 'true' then resets integer keys of each array,
     * including keys set manually.
     * If an array has one or more non-integer key then keys are saving.
     * If the function takes not array then it returns an unchanged value.
     *
     * @param  mixed   $array
     * @param  bool    $resetIndex
     * @return mixed
     */
    public static function removeNullRecurcive($array, bool $resetIndex = true)
    {
        if (is_array($array) && count($array)) {
            foreach ($array as $key => &$value) {
                if (is_null($value)) {
                    unset($array[$key]);
                } else {
                    if (is_array($value)) {
                        $value = self::removeNullRecurcive($value, $resetIndex);

                        if (is_null($value)) {
                            unset($array[$key]);
                        }

                        // Resets keys when they are integer keys
                        // and they should be reset.
                        elseif (self::isIntKeys($value) && $resetIndex) {
                            $value = array_values($value);
                        }
                    }
                }
            }
        }

        return $array;
    }

    /**
     * Checks whether integer keys are in an array.
     * Uses for detecting non-associative arrays.
     * Returns 'true' if all keys of the array are integer.
     * The empty array is not the integer array, because that its keys
     * cannot be detected.
     *
     * @param  array   $array
     * @return bool
     */
    public static function isArrayWithIntKeys(array $array): bool
    {
        if (count($array) === 0) {
            return false;
        }

        for (reset($array); is_int(key($array)); next($array));

        return is_null(key($array));
    }

    /**
     * A synonym for the function isArrayWithIntKey().
     *
     * @param  string  $array
     * @return bool
     */
    public static function isIntKeys(array $array): bool
    {
        return self::isArrayWithIntKeys($array);
    }

    /**
     * Checks whether the first value and the last value have integer keys.
     * Returns 'true' if they are integer.
     * Uses for simple detecting non-associative arrays.
     * The function is analogue isArrayWithIntKeys(), but keys must have
     * some one from types: string keys or integer key.
     * The empty array is not the integer array, because that its keys
     * cannot be detected.
     *
     * @param  array    $array
     * @return bool
     */
    public static function isFirstLastWithIntKeys(array $array): bool
    {
        if (count($array) === 0) {
            return false;
        }

        reset($array);
        $array_first_key = key($array);

        end($array);
        $array_last_key = key($array);

        return is_int($array_first_key) && is_int($array_last_key);
    }

    /**
     * Adds new values with new keys to the end of a source array.
     * If $replace is 'true' then all existing value with same keys
     * will be replaced with new values.
     * Unlike the array_merge() the function does not create
     * and it does not returns a new array, it works with the source array.
     * Unlike the array_merge() that adds a new array to the first array
     * when to use integer keys, Arr::concatArray() replaces
     * identical integer keys as if they associative keys.
     *
     * @param  array   &$source
     * @param  array   $array
     * @param  bool    $replace
     * @return void
     */
    public static function concatArray(array &$source, array $array, bool $replace = true)
    {
        if ($replace) {
            $source = $array + $source;
        } else {
            $source += $array;
        }
    }

    /**
     * Joins values of other arrays to a source array. Values with string keys
     * will be replace when they equals, values with number keys will be adjoins
     * to the source array.
     *
     * @param  array &$source
     * @param  array ...$arrays
     * @return void
     */
    public static function pushArray(array &$source, array ...$array)
    {
        $source = array_merge($source, ...$array);
    }

    /**
     * Removes nested arrays (subarrays) from an array.
     * If $resetIndex is 'true' then resets keys of the array.
     *
     * @param  array   $array
     * @param  bool    $resetIndex
     * @return array
     */
    public static function removeArrays(array $array, bool $resetIndex = false): array
    {
        $array = array_filter($array, function ($value) {
            return is_array($value) === false;
        });

        return $resetIndex ? array_values($array) : $array;
    }

    /**
     * Returns the last item of a given array.
     * The pointer of the position of the array is saving.
     *
     * @param  array &$array
     * @return mixed
     */
    public static function last(array &$array)
    {
        return array_slice($array, -1, 1)[0] ?? null;
    }

    /**
     * Removes values of an array by given keys without changing keys of the array.
     * Returns a new array.
     *
     * @param  array  $array
     * @param  mixed|int|string|array ...$keys
     * @return array
     */
    public static function unset(array $array, ...$keys): array
    {
        foreach ($keys as $key) {
            if (is_array($key)) {
                self::unsetByReference($array, ...$key);
            } elseif (is_scalar($key)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * Removes values of an array by given keys. The function do not return
     * a result.
     *
     * @param  array &$array
     * @param  mixed ...$keys
     * @return void
     */
    public static function unsetByReference(array &$array, ...$keys)
    {
        foreach ($keys as $key) {
            if (is_array($key)) {
                self::unsetByReference($array, ...$key);
            } elseif (is_scalar($key)) {
                unset($array[$key]);
            }
        }
    }

    /**
     * Returns the first existing value or returns 'null'.
     *
     * @param  mixed ...$values
     * @return mixed|null
     */
    public function firstExists(...$values)
    {
        foreach ($values as $value) {
            if (isset($value)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Returns the first value which is not empty or returns 'null'.
     *
     * @param  mixed ...$values
     * @return mixed|null
     */
    public function firstNotEmpty(...$values)
    {
        foreach ($values as $value) {
            if (empty($value) === false) {
                return $value;
            }
        }

        return null;
    }
}
