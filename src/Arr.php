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

                    // Resets keys when all keys in the array are integer keys
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

                        // Resets keys when all keys in the array are integer keys
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
     * A synonym for isArrayWithIntKey().
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
     * Joins values of other arrays to the source array. Values with string keys
     * will be replaced when they equals, values with number keys will be adjoined
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
    public static function firstExists(...$values)
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
    public static function firstNotEmpty(...$values)
    {
        foreach ($values as $value) {
            if (empty($value) === false) {
                return $value;
            }
        }

        return null;
    }

    /**
     * Returns an array of values from the source array that are integer keys.
     * The keys of the source array are not saved.
     *
     * @param  array $array
     * @return array
     */
    public static function getValuesUsingIntKeys(array $array): array
    {
        $values = array_filter($array, 'is_int', ARRAY_FILTER_USE_KEY);

        return array_values($values);
    }

    /**
     * Returns an array of values from the source array that are string keys.
     * The keys of the source array are saved.
     *
     * @param  array $array
     * @return array
     */
    public static function getValuesUsingStringKeys(array $array): array
    {
        return array_filter($array, 'is_string', ARRAY_FILTER_USE_KEY);
    }

    /**
     * Resets integer keys and return the new array.
     * First integer keys, then string keys.
     *
     * @param  array $array
     * @return array
     */
    public static function resetIntKeys(array $array): array
    {
        $onlyKeys = Arr::getValuesUsingIntKeys($array);
        $keysWithValues = Arr::getValuesUsingStringKeys($array);

        return $onlyKeys + $keysWithValues;
    }

    /**
     * Finds the first index by the string key in the array of keys and returns its.
     *
     * @param  array            $keys
     * @param  int|string|mixed $key
     * @return int|string|null
     */
    public static function findFirstIndexByKey(array $keys, string $key)
    {
        foreach ($keys as $index => $value) {
            if ((is_string($index) && $key === $index) || ($key === $value)) {
                return $index;
            }
        }

        return null;
    }

    /**
     * Returns a key from the pair of a key and a value.
     * If $intKey is 'true' then the key can be integer key, else only string key.
     *
     * @param  int|string $key
     * @param  mixed      $value
     * @param  bool       $intKey
     * @return string|null
     */
    public static function getKeyFromPairOfValues($key, $value, $intKey = true)
    {
        if (is_string($key) || ($intKey && is_int($key))) {
            return $key;
        } elseif (is_string($value)) {
            return $value;
        }

        return null;
    }

    /**
     * Returns united keys and their values from the array of keys.
     *
     * @param  array $keys
     * @param  bool  $rewrite
     * @return array
     */
    public static function uniteKeys(array $keys, bool $rewrite = true): array
    {
        return self::mergeKeys([], $keys, $rewrite);
    }

    /**
     * Merges arrays of keys and returns the new array.
     * If the first array (an array of keys) is empty then the function returns
     * a result similar to the uniteKeys() function.
     *
     * @param  array $keys
     * @param  array $array
     * @param  bool  $replace
     * @return array
     */
    public static function mergeKeys(array $keys, array $array, $replace = true): array
    {
        if (count($keys) === 0 and count($array) === 0) {
            return [];
        }

        $keys = self::uniteKeys($keys);

        foreach ($array as $key => $value) {
            /** @var string|null **/
            $needle = self::getKeyFromPairOfValues($key, $value, false);

            if (! isset($needle)) {
                break;
            }

            /** @var string|int|null **/
            $index = self::findFirstIndexByKey($keys, $needle);

            // Removes an existing key if necessary
            if ($index && $replace) {
                unset($keys[$index]);
            }

            // Adds a new key and its value if necessary
            if ((is_string($key) && $index && $replace) || (is_string($key) && ! $index)) {
                $keys[$key] = $value;
            } elseif ($replace || ! $index) {
                $keys[] = $value;
            }
        }

        return self::resetIntKeys($keys);
    }


    /**
     * Differences between two arrays and returns its.
     *
     * The structure of the result:
     * [
     *    'left' => [
     *        // values that are in the left array and who are not in the right array
     *    ],
     *    'intersection' => [
     *        // intersecting values
     *    ],
     *    'right' => [
     *        // values that are in the right array and who are not in the left array
     *    ]
     * ]
     *
     * @param  array $leftArray
     * @param  array $rightArray
     * @return array
     */
    public static function difference(array $leftArray, array $rightArray): array
    {
        $left = [];
        $intersection = [];
        $right = $rightArray; // At first all values are new

        foreach ($leftArray as $item) {
            /** @var int|bool $index **/
            $index = array_search($item, $rightArray);

            if ($index !== false) {
                $intersection[] = $item;

                // Excludes a new item
                unset($right[$index]);
            } else {
                $left[] = $item;
            }
        }

        // Reset keys
        $right = array_values($right);

        return compact('left', 'intersection', 'right');
    }

    /**
     * Returns intersecting values of the arrays.
     *
     * @param  array $leftArray
     * @param  array $rightArray
     * @return array
     */
    public static function intersection(array $leftArray, array $rightArray): array
    {
        return self::difference($leftArray, $rightArray)['intersection'];
    }

    /**
     * Returns divergence from the right array. That is returns values
     * that are in the left array and that are not in the right array.
     *
     * @param  array $leftArray
     * @param  array $rightArray
     * @return array
     */
    public static function leftDivergence(array $leftArray, array $rightArray): array
    {
        return self::difference($leftArray, $rightArray)['left'];
    }

    /**
     * Returns divergence from the left array. That is returns values
     * that are in the right array and that are not in the left array.
     *
     * @param  array $leftArray
     * @param  array $rightArray
     * @return array
     */
    public static function rightDivergence(array $leftArray, array $rightArray): array
    {
        return self::difference($leftArray, $rightArray)['right'];
    }
}
