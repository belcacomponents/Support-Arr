# Belca\Support\Arr - PHP helper functions for handling arrays

> The documentation is actual for version 1.0.

The `Belca\Support\Arr` class has functions for handling arrays containing standard data (the data does not use special storage rules). Although you can use them as you like.

**Some examples**
```php
use Belca\Support\Arr;

// Determine whether an array is filled with integer keys
$result = Arr::isIntKeys([1, 2, 3, 4, 5]); // Output: true
$result = Arr::isIntKeys([1, 2, 'three' => 3, 4, 5]); // Output: false

// Check whether the first and last key of an array are integers.
$result = Arr::isFirstLastWithIntKeys([1, 2, 3, 4, 5]); // Output: true
$result = Arr::isFirstLastWithIntKeys([1, 2, 3, 4, 'five' => 5]); // Output: false

// Take a first existing value of your array
$result = Arr::firstExists(null, null, 'value'); // Output: 'value'
```

Read the documentation to know about other functions.

## Install

You must have PHP 7.0 and later and Composer.

Install the package. Look at the example:

```bash
composer require belca/support-array:1.*
```

Use the ```Belca\Support\Arr``` in your classes.

```php
use Belca\Support\Arr;

// Your code

$array = Arr::trim($array); // or another function

// or
$array = \Belca\Support\Arr::trim($array); // using the full path
```

Now you can use all functions of the package.

## Array functions

* [Arr::concatArray](#array-concat-array)
* [Arr::firstExists](#array-first-exists)
* [Arr::firstNotEmpty](#array-first-not-empty)
* [Arr::isArrayWithIntKeys](#array-is-array-with-int-keys)
* [Arr::isFirstLastWithIntKeys](#array-is-first-last-with-int-keys)
* [Arr::isIntKeys](#array-is-int-keys)
* [Arr::last](#array-last)
* [Arr::pushArray](#array-push-array)
* [Arr::removeArrays](#array-remove-arrays)
* [Arr::removeEmpty](#array-remove-empty)
* [Arr::removeEmptyRecurcive](#array-remove-empty-recurcive)
* [Arr::removeNotScalar](#array-remove-not-scalar)
* [Arr::removeNull](#array-remove-null)
* [Arr::removeNullRecurcive](#array-remove-null-recurcive)
* [Arr::trim](#array-trim)
* [Arr::unset](#array-unset)
* [Arr::unsetByReference](#array-unset-by-reference)

### <a name="array-concat-array"></a> Arr::concatArray

`Arr::concatArray(&$source, $array, $replace = true) : void`

Adds new values with new keys to the end of a source array.

If $replace is 'true' then all existing value with same keys will be replaced with new values.

Unlike the array_merge() the function does not create and it does not returns a new array, it works with the source array.

Unlike the array_merge() that adds a new array to the first array when to use integer keys, Arr::concatArray() replaces identical integer keys as if they associative keys.

The function executes `$array1 += $array2` or `$array2 + $array1` operations in accordance with a given *$replace* parameter.

Parameters:
- &$source - a source array;
- $array - an array to concat;
- $replace - replacing values. If **$replace** is *true* then all existing values that have same keys will be replaced.

**Example #1: Adding new values in the array and replacing values with same keys. The array has integer keys.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6, 7, 8, 9, 10, 11, 12];

Arr::concatArray($source, $array);

// Output $source: [6, 7, 8, 9, 10, 11, 12];
```

First 6 values of the soure array were be replaced the new values. Also was added a new value: 12. This was happened because keys of the source array coincided with the second array.

**Example #2: Adding new values in the array and replacing values with same keys. The keys of the second array are offset.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6 => 6, 7, 8, 9, 10, 11, 12];

Arr::concatArray($source, $array);

// Output $source: [1, 2, 3, 4, 5, 6, 6, 7, 8, 9, 10, 11, 12];
```

In this example we get the necessary result, the function concatenated the new values to the source array. This may sometimes be useful.

In the next example we will be to use an associative array where values that have same keys will be replaced.

**Example #3: Adding new values in the array and replacing old values with same keys.**

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::concatArray($source, $newValues);

// Output $source: ['key1' => 1, 'key2' => 3, 'key3' => 4];
```

However replacing values of the source array may not always be useful. You may add only new values that are not yet in the source array.

See this example.

**Example 4: Adding only new values in the array.**

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::concatArray($source, $newValues, false);

// Output $source: ['key1' => 1, 'key2' => 2, 'key3' => 4];
```

You might sight the function do not returns a result, it process the source array.

**See also**:
- [`Arr::pushArray()`](#array-push-array).

### <a name="array-first-exists"></a> Arr::firstExists

`Arr::firstExists(...$values): mixed`

Returns the first existing value or returns 'null'.

```php
$result = Arr::firstExists(null, null, false, 0, '', true); // Output: false
$result = Arr::firstExists(null, null, 0, '', 'value', true, []); // Output: 0
$result = Arr::firstExists(null, null, [], '', 'value', true); // Output: []
```

### <a name="array-first-not-empty"></a> Arr::firstNotEmpty

`Arr::firstNotEmpty(...$values): mixed`

Returns the first value which is not empty or returns 'null'.

```php
$result = Arr::firstNotEmpty(null, null, false, 0, '', true); // Output: true
$result = Arr::firstNotEmpty(null, null, false, 0, '', 'value', true, []); // Output: 'value'
```

### <a name="array-is-array-with-int-keys"></a> Arr::isArrayWithIntKeys

`Arr::isArrayWithIntKeys(array $array) : boolean`

Checks whether integer keys are in an array. Uses for detecting non-associative arrays.
Returns 'true' if all keys of the array are integer.

The empty array is not the integer array, because that its keys cannot be detected.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10];
$result1 = Arr::isArrayWithIntKeys($array1); // true

$array2 = []; // false
$result2 = Arr::isArrayWithIntKeys($array2); // false, потому что пустой массив

$array3 = ['1' => 1, 2, 3, '4' => 4];
$result3 = Arr::isArrayWithIntKeys($array4); // true, потому что числа в строке преобразованы в integer

$array4 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1];
$result4 = Arr::isArrayWithIntKeys($array5); // false
```

**See also**:
- [Arr::isFirstLastWithIntKeys()](#array-is-first-last-with-int-keys);
- [Arr::isIntKeys()](#array-is-int-keys).

### <a name="array-is-first-last-with-int-keys"></a> Arr::isFirstLastWithIntKeys

`Arr::isFirstLastWithIntKeys(array $array) : boolean`

Checks whether the first value and the last value have integer keys.
Returns 'true' if they are integer.
Uses for simple detecting non-associative arrays.

The function is analogue isArrayWithIntKeys(), but keys must have some one from types: string keys or integer key.

The empty array is not the integer array, because that its keys cannot be detected.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10];
$result1 = Arr::isFirstLastWithIntKeys($array1); // true

$array2 = [];
$result2 = Arr::isFirstLastWithIntKeys($array2); // false, because the array is empty

$array3 = ['1' => 1, 2, 3, '4' => 4];
$result3 = Arr::isFirstLastWithIntKeys($array4); // true, because the number in the keys converted to the integer

$array4 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1];
$result4 = Arr::isFirstLastWithIntKeys($array5); // true, because the first and the last keys are the integer

$array5 = [50 => 1, 'a2' => 3, 'a3' => 4, 'one' => 1];
$result5 = Arr::isFirstLastWithIntKeys($array6); // false, because the last key is the string
```

In comparasion with  [`Arr::isArrayWithIntKeys()` (`Arr::isIntKeys()`)](#array-is-array-with-int-keys), that may scan all values of an array, this function checks only the first and the last keys, that doing more faster.

**See also**:
- [Arr::isIntKeys()](#array-is-int-keys).

### <a name="array-is-int-keys"></a> Arr::isIntKeys

`Arr::isIntKeys(array $array) : boolean`

The alias of [`Arr::isArrayWithIntKeys()`](#array-is-array-with-int-keys).

```php
$normalArray = [1, 2, 3, 4, 5, 6, 7, 8, 10];
$result1 = Arr::isIntKeys($normalArray); // true

$badArray = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1];
$result2 = Arr::isIntKeys($badArray); // false
```

### <a name="array-last"></a> Arr::last

`Arr::last(&$array) : mixed`

Returns the last item of a given array. The pointer of the position of the array is saving.

```php
$array = [5 => 1, 2, 3, 4, 5];

$last = Arr::last($array); // Output: 5
```

### <a name="array-push-array"></a> Arr::pushArray

`Arr::pushArray(&$array, ...$array) : void`

Joins values of other arrays to the source array. Values with string keys will be replaced when they equals, values with number keys will be adjoined to the source array.

```php
$source = [1, 2, 3, 'key1' => 1, 'key2' => 2, 'key3' => 3];
$array1 = [4, 5, 6];
$array2 = [1, 2, 3, 'key1' => 10];

Arr::pushArray($source, $array1, $array2);

// Output $source:
// [1, 2, 3, 'key1' => 10, 'key2' => 2, 'key3' => 3, 4, 5, 6, 1, 2, 3]
```

See the result. *1, 2, 3* values from $array2 were added in the source array, but the source array had these values and were added duplicates.

If you want not same values in an array, then use `array_unique()` to get only unique values. The function will be apply to all keys, even to string keys, where different keys may keep same values.

**See also**:
- [Arr::concatArray()](#array-concat-array).

### <a name="array-remove-arrays"></a> Arr::removeArrays

`Arr::removeArrays($array, $resetIndex = false) : array`

Removes nested arrays (subarrays) from an array. If $resetIndex is 'true' then resets keys of the array.

Parameters:
- $array - an array;
- $resetIndex - the reset of keys of the array. If **$resetIndex** is *true* then resets keys of the array.

**Example 1: Removing inner arrays.**
```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$result = Arr::removeArrays($array);

// Output: [
//    1,
//    2,
//    3,
//    'four' => 4,
//    'five' => 5,
//    7,
//    'eight' => 8,
//    9,
//    'object' => new stdClass(),
// ];
```

In the example in the array were removed all arrays and kept other values.

Sometimes you need to reset keys of an array as shown below.

**Example 2: Remove inner arrays and resetting keys the array.**
```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$result = Arr::removeArrays($array, true);

// Output: [1, 2, 3, 4, 5, 7, 8, 9, new stdClass()];
```

### <a name="array-remove-empty"></a> Arr::removeEmpty

`Arr::removeEmpty(array $array) : array`

Removes each values of an array where the empty value. Uses the function 'empty'. Keys of the array are saving.

```php
$array4 = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeEmpty($array);

// Output: [1, 2, 5 => new stdClass];
```

### <a name="array-remove-empty-recurcive"></a> Arr::removeEmptyRecurcive

`Arr::removeEmptyRecurcive(array $array, boolean $resetIndex = true) : array`

Recursively removes the empty values of a multidimensional array.
If the function takes not array then it returns an unchanged value.

Parameters:
- $array - some array;
- $resetIndex - the reset of keys of the array. If **$resetIndex** is *true* then resets integer keys of each array, including keys set manually.

**Example #1. Recursive removing empty values and resetting keys of arrays.**
```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    'a3' => [
        1,
        2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeEmptyRecurcive($array);

// Output:
// [
//    1 => [0 => 1, 1 => 2, 2 => [1, 2, 3, 4], 3 => 4],
//    2 => -2,
//    'a3' => [
//         0 => 1,
//         1 => 2,
//         'a3.3' => [0 => 1, 1 => 2, 2 => 3],
//    ],
// ]
```

In the example you see that resetting keys were only in arrays with integer keys. So the basic keys of the array and the keys of the `$arrar['a3']` value was not changed, and the others keys were reset.

**Example 2. Recursive removing empty values without resetting keys**
```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    'a3' => [
        1,
        2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeEmptyRecurcive($array, false);

// Output:
// [
//    1 => [1, 2, 3 => [1, 2, 3, 4], 4 => 4],
//    2 => -2,
//    'a3' => [
//         0 => 1,
//         1 => 2,
//         'a3.3' => [1 => 1, 2 => 2, 3 => 3],
//    ],
// ]
```

In the example the keys were not changed, because **$resetIndex** was set as *false*.

**See also**:
- [Arr::removeNullRecurcive()](#array-remove-null-recurcive).

### <a name="array-remove-not-scalar"></a> Arr::removeNotScalar

`Arr::removeNotScalar(array $array) : array`

Removes each values of an array with the value is 'null' or other values that are not scalar values.
Scalar values are those containing an integer, float, string or boolean.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];
$result = Arr::removeNotScalar($array);

// Output: [0 => 1, 1 => 2, 3 => '', 6 => false, 7 => 0];
```

### <a name="array-remove-null"></a> Arr::removeNull

`Arr::removeNull(array $array) : array`

Removes each values of an array where a value is 'null'. Uses the function 'is_null'. Keys of the array are saving.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeNull($array);  

// Output: [1, 2, 3 => '', [], new stdClass, false, 0];
```

### <a name="array-remove-null-recurcive"></a> Arr::removeNullRecurcive

`Arr::removeNullRecurcive(array $array, boolean $resetIndex = true) : array`

Recursively removes the null values of a multidimensional array.

Parameters:
- $array - some array;
- $resetIndex - the reset of keys of the array. If **$resetIndex** is *true* then resets integer keys of each array, including keys set manually. If an array has one or more non-integer key then keys are saving. If the function takes not array then it returns an unchanged value.

**Example 1: Removing values with 'null' and resetting keys.**
```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    4 => [
        1 => 1,
        2 => 2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeNullRecurcive($array);

// Output:
// [
//    0 => [0 => 1, 1 => 2, 2 => [1, 2, 3, 4, []], 3 => 4, 4 => ''],
//    1 => -2,
//    2 => [
//         1 => 1,
//         2 => 2,
//         'a3.3' => [0 => 1, 1 => 2, 2 => 3],
//    ],
//    3 => '',
//    4 => 0,
//    5 => false,
// ]
```
In the example performing resetting keys of the arrays where all keys of an array are integer. So the keys of `$array[2]` were not changed, but the index of the value was changed.

**Example 2: Removing values with 'null' and without resetting keys.**
```php
$array = [
    1 => [1, 2, 3 => [1, 2, 3, 4, [], null], 4, ''],
    -2,
    4 => [
        1 => 1,
        2 => 2,
        'a3.3' => [0, 1, 2, 3],
    ],
    null,
    '',
    0,
    false,
];

$result = Arr::removeNullRecurcive($array, false);

// Output:
// [
//    1 => [1, 2, 3 => [1, 2, 3, 4, []], 4 => 4, 5 => ''],
//    2 => -2,
//    4 => [
//         1 => 1,
//         2 => 2,
//         'a3.3' => [1 => 1, 2 => 2, 3 => 3],
//    ],
//    6 => '',
//    7 => 0,
//    8 => false,
// ]
```

**See also**:
- [Arr::removeEmptyRecurcive()](#array-remove-empty-recurcive).

### <a name="array-trim"></a> Arr::trim

`Arr::trim(array $array) : array`

Trims string values of an array. Keys of the array are saving.
The function does not handle nested arrays.

```php
$array = [
    ' value ',
    'trim',
    'one ',
    ' two',
    '   three    ',
    1,
    2,
    'string',
    null,
    ['  no trim  '],
];

$result = Arr::trim($array);

// Output: ['value', 'trim', 'one', 'two', 'three', 1, 2, 'string', null, ['  no trim  ']];
```

### <a name="array-unset"></a> Arr::unset

`Arr::unset($array, ...$indexes) : array`

Removes values of an array by given keys without changing keys of the array. Returns a new array.

```php
$array = [
    1,
    2,
    3,
    'four' => 4,
    'five' => 5,
    'matrix' => [
        [1, 2, 3],
        [4, 5, 6],
        [7, 8, 9],
    ],
    7,
    'eight' => 8,
    9,
    'symbols' => ['a', 'b', 'c'],
    'object' => new stdClass(),
];

$output = Arr::unset($array, 0, 'four', 'eight', 4);
// or
$output = Arr::unset($array, [0, 'four', 'eight', 4]);
// or
$output = Arr::unset($array, [0, 'four'], ['eight', 4]);
// or
$output = Arr::unset($array, [0, 'four'], [['eight'], [4], []]);

// Output:
// [
//    1 => 2,
//    2 => 3,
//   'five' => 5,
//    'matrix' => [
//        [1, 2, 3],
//        [4, 5, 6],
//         [7, 8, 9],
//    ],
//    3 => 7,
//    'symbols' => ['a', 'b', 'c'],
//    'object' => new stdClass(),
// ]
```

This function may be useful when you need to remove from an array unknown values by knowing their indexes. The same effect may be get using `array_filter()` or `unset()`, but this function are more readable and compact.

### <a name="array-unset-by-reference"></a> Arr::unsetByReference

`Arr::unsetByReference(array &$array, ...$keys): void`

Removes values of an array by given keys. The function do not return a result.

**Example 1: Removing values using simple keys**
```php
$array = [
    1, 2, 3, 4, 5, 'six' => 6, 'seven' => 7, 8, 9, 'ten' => 10, 11
];

Arr::unsetByReference($array, 0, 'six', 'ten', 'unknown');

// Output $array:
// [
//    1 => 2, 3, 4, 5, 'seven' => 7, 8, 9, 11
// ]
```

**Example 2: Removing values using keys in arrays**
```php
$array = [
    1, 2, 3, 4, 5, 'six' => 6, 'seven' => 7, 8, 9, 'ten' => 10, 11
];

Arr::unsetByReference($array, [0, 1], ['six', 'ten', 'unknown']);

// Output $array:
// [
//    2 => 3, 4, 5, 'seven' => 7, 8, 9, 11
// ]
```

**See also**:
- [Arr::unset()](#array-unset).
