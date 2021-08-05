<?php

function combineRecursive(array &$r, int &$max, array &$array, array $arr = [], int $index = 0 ): void {
    for($j = 0, $l = count($array[$index]); $j<$l; $j++) {
        $a = [...$arr];
        $a[] = $array[$index][$j];
        if ($index === $max)
            $r[] = $a;
        else
            combineRecursive($r, $max, $array, $a, $index+1);
    }
}
// функция делает комбинации из n массивов беря из кадого массива по одному элементу для каждой комбинации
// input: [[1,2,3], [4,5,6]]
// output: [[1,4],[1,5],[1,6],[2,4],[2,5],[2,6],[3,4],[3,5],[3,6]]
function combine(array $array): array {
    $r = [];
    $max = count($array) - 1;
    combineRecursive($r, $max, $array);
    return $r;
}

// функция принимает строку c n к-вом плейсхолдеров и массив массивов с данными для подстановки
// на выходе имеем все возможные варианты строк.
// input: $str = '[0] мы пошли [1]', $arr = [['Сегодня', 'Завтра'], ['в лес', 'в тайгу']]
// output: ['Сегодня мы пошли в лес', 'Сегодня мы пошли в тайгу', 'Завтра мы пошли в лес', 'Завтра мы пошли в тайгу']
function replacePlaceholder(string $str, array $arr): array {
    $combinations = combine($arr);
    $result = [];

    foreach ($combinations as $array) {
        $resultString = $str;
        foreach ($array as $key=>$value) {
            $resultString = str_replace("[{$key}]", $value, $resultString);
        }
        $result[] = $resultString;
    }

    return $result;
}

// функция которая находит в переданном массиве строки с плейсходерами, берет по индексу нужные массивы с данными и
// отдает replacePlaceholder, после получения результата сливает все в один массив
function replacePlaceholders(array $placeholders, array $insertElements): array {
    $result = [];
    $matches = [];
    $re = '|\[(?<index>[0-9]+)\]|';

    foreach ($placeholders as $value) {
        if(preg_match_all($re, $value, $matches)) {
            $arrayToReplace = [];
            $count = 0;
            foreach ($matches['index'] as $index) {
                $value = str_replace("{$index}", $count++, $value);
                $arrayToReplace[] = $insertElements[$index];
            }
            $result = [...$result, ...replacePlaceholder($value, $arrayToReplace)];
        } else {
            $result[] = $value;
        }
    }

    return $result;
}

function stringTegParser(string $str): array {
    if ($str === '') {
        return [];
    }

    $string = '';
    $array = [];
    $answer = [];
    $nesting = 0;
    $start = 0;
    $placeholder = 0;

    for($i = 0; $i < strlen($str); $i++) {

        if($str[$i] === '<') {
            $start = $nesting === 0 ? $i + 1 : $start;
            $nesting++;
            continue;
        } else if ($str[$i] === '>') {
            $nesting--;
            if($nesting === 0) {
                $array[$placeholder] = stringTegParser(substr($str, $start, $i - $start));
                $string .= "[{$placeholder}]";
                $placeholder++;
            }
        }

        if($nesting === 0 && $str[$i] !== '>') {
            $string .= $str[$i];
        }

        if(strlen($str) - $i === 1) {
            if ($nesting === 0) {
                $arr = explode('::', $string);
                if(count($array) === 0) {
                    return $arr;
                } else {
                    $answer = replacePlaceholders($arr, $array);
                }
                $string = $arr[array_rand($arr)];
            } else {
                throw new Exception('Syntax error');
            }
        }
    }

    return $answer;
}
