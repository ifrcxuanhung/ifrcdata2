<?php

/**
 * Retrieves muliple single-key values from a list of arrays.
 *
 *     // Get all of the "id" values from a result
 *     $ids = Arr::pluck($result, 'id');
 *
 * [!!] A list of arrays is an array that contains arrays, eg: array(array $a, array $b, array $c, ...)
 *
 * @param   array   list of arrays to check
 * @param   string  key to pluck
 * @return  array
 */
function pluck($array, $key) {
    $values = array();

    foreach ($array as $row) {
        if (isset($row[$key])) {
            // Found a value in this row
            $values[] = $row[$key];
        }
    }

    return $values;
}

/**
 * Adds a value to the beginning of an associative array.
 *
 *     // Add an empty value to the start of a select list
 *     Arr::unshift($array, 'none', 'Select a value');
 *
 * @param   array   array to modify
 * @param   string  array key name
 * @param   mixed   array value
 * @return  array
 */
function unshift(array & $array, $key, $val) {
    $array = array_reverse($array, TRUE);
    $array[$key] = $val;
    $array = array_reverse($array, TRUE);

    return $array;
}