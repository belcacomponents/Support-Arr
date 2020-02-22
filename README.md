# Support - вспомогательные функции PHP

> Документация актуальна для версии v0.10.

Вспомогательные классы и их функции могут быть использованы в любом PHP-проекте.

Вспомогательные классы используются в компонентах Belca.

## <a name="arrays"></a> Массивы (Arrays) - функции для обработки массивов

Класс `Belca\Support\Arr` используется для обработки массивов с простым набором данных (данные и ключи массивов могут не иметь каких-то специальных правил хранения, в отличии от обрабатываемых данных классом `Belca\Support\SpecialArr`).

Для работы с функциями класса необходимо подключить его или вызывать его функции указывая полный путь к классу. Примеры подключения и использования функций класса показаны ниже.

```php
use Belca\Support\Arr;

// или

$result = \Belca\Support\Arr::trim($array); // и другие функции
```

||||
|--|--|--|
|[Arr::trim](#array-trim) |[Arr::removeEmpty](#array-remove-empty)|[Arr::removeNull](#array-remove-null)|
|[Arr::removeNotScalar](#array-remove-not-scalar)|[Arr::removeEmptyRecurcive](#array-remove-empty-recurcive)|[Arr::removeNullRecurcive](#array-remove-null-recurcive)|
|[Arr::isArrayWithIntKeys](#array-is-array-with-int-keys)|[Arr::isIntKeys](#array-is-int-keys)|[Arr::isFirstLastWithIntKeys](#array-is-first-last-with-int-keys)|
|[Arr::concatArray](#array-concat-array)|[Arr::removeArrays](#array-remove-arrays)|[Arr::last](#array-last)|
|[Arr::unset]|(#array-unset)||

### <a name="array-trim"></a> Arr::trim

`Arr::trim(array $array) : array`

Удаляет лишние пробелы, табуляции, переносы в строковых значениях массива с помощью функции `trim()`. Ключи массива остаются в неизменном виде.

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

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$notArray = null;
$result = Arr::trim($notArray);

// Output: [];
```

Значения вложенных массивов не обрабатываются этой функцией.

### <a name="array-remove-empty"></a> Arr::removeEmpty

`Arr::removeEmpty(array $array) : array`

Удаляет пустые значения массива проверяя значения функцией `empty()`. Ключи массива остаются в неизменном виде.

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$array4 = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeEmpty($array);

// Output: [1, 2, 5 => new stdClass];
```

### <a name="array-remove-null"></a> Arr::removeNull

`Arr::removeNull(array $array) : array`

Удаляет элементы массива со значением *null* с помощью функции `is_null()`. Ключи массива остаются в неизменном виде.

Если в качестве аргумента передан не массив, то будет возвращен пустой массив.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];

$result = Arr::removeNull($array);  

// Output: [1, 2, 3 => '', [], new stdClass, false, 0];
```

### <a name="array-remove-not-scalar"></a> Arr::removeNotScalar

`Arr::removeNotScalar(array $array) : array`

Удаляет значения массива со значением 'null' или с другими значениями не являющимися скалярными (скалярные значения: integer, float, string, boolean). Ключи остаются в неизменном виде.

```php
$array = [1, 2, null, '', [], new stdClass, false, 0];
$result = Arr::removeNotScalar($array);

// Output: [0 => 1, 1 => 2, 3 => '', 6 => false, 7 => 0];
```

### <a name="array-remove-empty-recurcive"></a> Arr::removeEmptyRecurcive

`Arr::removeEmptyRecurcive(array $array, boolean $resetIndex = true) : array`

Рекурсивно удаляет пустые значения многомерного массива.

Если в качестве значения будет указан не массив, то это значение будет возвращено в неизменном виде.

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс массива. Если **$resetIndex** - *true*, то сбрасывает числовые ключи массива, в т.ч., которые были заданы вручную, а не автоматически присвоены при инициализации массива.

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

В примере выше сброс ключей произешел только в тех массивах, в которых все ключи были числовыми. Таким образом основные ключи массива и ключи значения `$arrar['a3']` остались без изменений, а все другие ключи были обнулены.

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

В примере выше все ключи остались без изменений, потому что в качестве аргумента **$resetIndex** мы указали *false*.

### <a name="array-remove-null-recurcive"></a> Arr::removeNullRecurcive

`Arr::removeNullRecurcive(array $array, boolean $resetIndex = true) : array`

Рекурсивно удаляет значения равные *null* в многомерном массиве.

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс индексов. Если **$resetIndex** - *true*, то сбрасывает числовые ключи массива во всех внутренних массивах. Если обрабатываемом массиве есть хотя бы один нечисловой ключ, то все ключи, в т.ч. числовые ключи обрабатываемого массива, остаются неизменными.

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

В примере выше происходит сброс ключей массива, если все ключи обрабатываемого массива числовые. Таким образом ключи значения `$array[2]` остались неизменными, хотя сам индекс этого значения изменился.

В примере ниже все ключи остаются без изменений.

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

### <a name="array-is-array-with-int-keys"></a> Arr::isArrayWithIntKeys

`Arr::isArrayWithIntKeys(array $array) : boolean`

Проверяет, числовые ли ключи в массиве. Может служить для проверки массива на ассоциативность.

Возвращает *true*, только если все ключи являются числовыми. Пустой массив не является числовым, т.к. его ключи и значения еще не определены.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$array2 = []; // false
$array3 = 1; // false
$array4 = ['1' => 1, 2, 3, '4' => 4]; // true
$array5 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false

$result1 = Arr::isArrayWithIntKeys($array1); // true
$result2 = Arr::isArrayWithIntKeys($array2); // false, потому что пустой массив
$result3 = Arr::isArrayWithIntKeys($array3); // false, потому что не массив
$result4 = Arr::isArrayWithIntKeys($array4); // true, потому что числа в строке преобразованы в integer
$result5 = Arr::isArrayWithIntKeys($array5); // false
```

### <a name="array-is-int-keys"></a> Arr::isIntKeys

`Arr::isIntKeys(array $array) : boolean`

Синоним функции `Arr::isArrayWithIntKeys()`.

```php
$normalArray = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$badArray = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // false

$result1 = Arr::isIntKeys($normalArray); // true
$result2 = Arr::isIntKeys($badArray); // false
```

### <a name="array-is-first-last-with-int-keys"></a> Arr::isFirstLastWithIntKeys

`Arr::isFirstLastWithIntKeys(array $array) : boolean`

Проверяет, является ли первое и последнее значения в массиве с числовыми ключами.

Этот прстой алгоритм помогает определить ассоциативность массива. Фактически это аналог функции `isArrayWithIntKeys()`, но в этом случае все ключи переданного массива должны принадлежать одному или другому типу (т.е. быть либо числовыми, либо строковыми).

При передачи пустого массива или не массива, результатом функции будет *false*.

```php
$array1 = [1, 2, 3, 4, 5, 6, 7, 8, 10]; // true
$array2 = []; // false
$array3 = 1; // false
$array4 = ['1' => 1, 2, 3, '4' => 4]; // true
$array5 = [50 => 1, 'a2' => 3, 'a3' => 4, 0 => 1]; // true
$array6 = [50 => 1, 'a2' => 3, 'a3' => 4, 'one' => 1]; // false

$result1 = Arr::isFirstLastWithIntKeys($array1); // true
$result2 = Arr::isFirstLastWithIntKeys($array2); // false, потому что пустой массив
$result3 = Arr::isFirstLastWithIntKeys($array3); // false, потому что не массив
$result4 = Arr::isFirstLastWithIntKeys($array4); // true, потому что числа в строке преобразованы в integer
$result5 = Arr::isFirstLastWithIntKeys($array5); // true, потому что первый и последний ключ является числовым
$result6 = Arr::isFirstLastWithIntKeys($array6); // false, потому что последний ключ строка
```

В отличии от фукнции `Arr::isArrayWithIntKeys()`, которая может пройти весь массив, текущая функция проверяет только первое и последнее значение, что выполнится быстрее.

### <a name="array-concat-array"></a> Arr::concatArray

`Arr::concatArray(&$source, $array, $replace = true) : void`

Добавляет к указанному массиву массив новых ключей и значений в конец массива.

Функция заменяет операции `$array1 += $array2` и `$array2 + $array1`, в зависимости от переданного параметра *$replace*.

Параметры функции:
- &$source - исходный массив (используется ссылка на массив);
- $array - добавляемый массив;
- $replace - замена значений. Если **$replace** - *true*, то все существующие значения с одинаковыми ключами будут заменены на новые.

** Пример 1: добавление новых значений в массив и замена значений с одинаковыми ключами, массив с цифровыми ключами.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6, 7, 8, 9, 10, 11, 12];

Arr::concatArray($source, $array);

// Output $source: [6, 7, 8, 9, 10, 11, 12];
```

В примере выше может показаться неожиданный результат, т.к. все значения были переписаны значениями нового массива. Это произошло из-за того, что все ключи массива совпадали и было добавлено одно новое значение - *12*.

** Пример 2: добавление новых значений в массив и замена значений с одинаковыми ключами, массив с цифровыми ключами. Смещение цифрового ключа.**

```php
$source = [1, 2, 3, 4, 5, 6];
$array = [6 => 6, 7, 8, 9, 10, 11, 12];

Arr::concatArray($source, $array);

// Output $source: [1, 2, 3, 4, 5, 6, 6, 7, 8, 9, 10, 11, 12];
```

В примере выше мы получили уже необходимый для нас результат, т.е. присоединили новые значения к переданному массиву. Такой результат наиболее полезен при работе с ассоциативными массивами.

В примере ниже мы используем ассоциативный массив, в котором будут переписаны значения с совпадающими ключами добавляемого массива.

** Пример 3: добавление новых значений в массив с заменой предыдущих значений с одинаковыми ключами. **

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::concatArray($source, $newValues);

// Output $source: ['key1' => 1, 'key2' => 3, 'key3' => 4];
```

Однако не всегда может быть полезным заменять значения исходного массива и может быть необходимость добавлять исключительно новые значения, которых еще не было в исходном массиве.

Такой пример показан ниже.

** Пример 4: добавление только новых значений в массив. **

```php
$source = ['key1' => 1, 'key2' => 2];
$newValues = ['key2' => 3, 'key3' => 4];

Arr::concatArray($source, $newValues, false);

// Output $source: ['key1' => 1, 'key2' => 2, 'key3' => 4];
```

Как вы заметили, функция не возвращает результат, а работает с исходным массивом, т.е. передается по ссылке.

### <a name="array-remove-arrays"></a> Arr::removeArrays

`Arr::removeArrays($array, $resetIndex = false) : array`

Удаляет из массива вложенные массивы (подмассивы).

Параметры функции:
- $array - любой массив;
- $resetIndex - сброс массива. Если **$resetIndex** - *true*, то сбрасывает ключи массива.

** Пример 1: удаление внутренних массивов. **

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

В примере выше мы удалили все массивы и оставили другие значения.

Иногда при такой операции может потребоваться обнулять и ключи массива, как это показано в примере ниже.

** Пример 2: удаление внутренних массивов и сброс ключей массива. **

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

// Output: [1, 2, 3, 4, 5, 7, 8, 9, new stdClass()];
```

### <a name="array-last"></a> Arr::last

`Arr::last(&$array) : mixed`

Функция возвращает последний элемент массива. Не смотря на то, что в функцию передается ссылка на массив, внутренний указатель массива не сбрасывается.

```php
$array = [5 => 1, 2, 3, 4, 5];

$last = Arr::last($array); // Output: 5
```

### <a name="array-unset"></a> Arr::unset

`Arr::unset($array, ...$indexes) : array`

Удаляет указанные индексы и возвращает измененный массив с сокранением индексов.

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
$output = Arr::unset($array, [0, 'four', 'eight', 4]);
$output = Arr::unset($array, [0, 'four'], ['eight', 4]);
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

Как видите из примера выше, функция принимает практически любые допустимые ключи и вложенные массивы, которые могут содержать ключи.

Эта функция может быть полезна, когда необходимо удалить из массива заранее неизвестные значения, но будут известны их индексы. Похожего эффекта можно достичь с помощью функции `array_filter()`, однако эта функция более читаема, компакта и универсальна.

### <a name="array-push-array"></a> Arr::pushArray

`Arr::pushArray(&$array, ...$array) : void`

Присоединяет к базовому массиву значения других массивов. Значения со строковыми ключами будут заменяться, в случае совпадения, а значения с числовыми ключами будут добавляться.

```php
$source = [1, 2, 3, 'key1' => 1, 'key2' => 2, 'key3' => 3];
$array1 = [4, 5, 6];
$array2 = [1, 2, 3, 'key1' => 10];

Arr::pushArray($source, $array1, $array2);

// Output $source:
// [1, 2, 3, 'key1' => 10, 'key2' => 2, 'key3' => 3, 4, 5, 6, 1, 2, 3]
```

Обратите внимание результат примера. Значения *1, 2, 3* из массива $array2 были добавлены в исходный массив, при этом в массиве уже были такие элементы и появились одинаковые значения.

Если вам не нужны одинаковые значения в массиве, то воспользуйтесь функцией `array_unique()` для возврата только уникальных значений, однако это коснется и значений со строковыми ключами, где разные ключи могут иметь одинаковые значения.
