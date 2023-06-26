<?php

$log_path = plugin_dir_path(__FILE__) . "../logs";

function print_log($data) {
    global $log_path;
    file_put_contents( "$log_path/amego.log", print_r( $data, true ), FILE_APPEND );
    file_put_contents( "$log_path/amego.log", print_r( "\n", true ), FILE_APPEND );
}

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', "$log_path/error.log");

// Handle errors and log them
function error_handler($errno, $errstr, $errfile, $errline) {
    $errorMessage = "Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}";
    error_log($errorMessage);
}

// Set the custom error handler
set_error_handler('error_handler');