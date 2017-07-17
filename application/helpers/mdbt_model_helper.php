<?php
/**
 * @author Adriaan Knapen <a.d.knapen@protonmail.com>
 * @date 1-3-2017
 */

if (! function_exists('field')) {
    /**
     * @param string $field
     * @param string|null $table
     * @return string
     */
    function field($field, $table = null) {
        if ($table === null) {
            return field;
        } else {
            return $table . '.' . $field;
        }
    }
}

if (! function_exists('eq')) {
    /**
     * @param string $field1
     * @param string $field2
     * @return string
     */
    function eq($field1, $field2) {
        return $field1 . ' = ' . $field2;
    }
}