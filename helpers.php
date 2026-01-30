<?php

/**
 * Get base path
 * 
 * @param string $path
 * @return string
 */
function basePath($path = '') {
    return __DIR__ . '/' . $path;
}

/**
 * Load views
 * 
 * @param string $name
 * @return void
 */
function loadView($name, $db = []) {
    $viewPath = basePath("App/views/{$name}.view.php");

    if (file_exists($viewPath)) {
        // Extract for example 'listings' and save it in a variable named $listings available in $viewPath.
        // Equivalent to $db['listings'].
        extract($db);
        require $viewPath;
    } else {
        echo "View '{$name} not found'!";
    }
}

/**
 * Load partials
 * 
 * @param string $name
 * @return void
 */
function loadPartial($name) {
    $partialPath = basePath("App/views/partials/{$name}.php");

    if (file_exists($partialPath)) {
        require $partialPath;
    } else {
        echo "Partial '{$name} not found!'";
    }
}

/**
 * Inspect a value(s)
 * 
 * @param mixed $value
 * @return void
 */
function inspect($value) {
    echo '<pre>';
    var_dump($value);
    echo '</pre>';
}

/**
 * Inspect a value(s) and die
 * 
 * @param mixed $value
 * @return void
 */
function inspectAndDie($value) {
    echo '<pre>';
    die(var_dump($value));
    echo '</pre>';
}

/**
 * Format salary
 * 
 * @param string $salary
 * @return string Formatted Salary
 */
function formatSalary($salary) {
    return '$' . number_format(floatval($salary));
}