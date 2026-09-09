<?php
require 'vendor/autoload.php';

if (function_exists('array_last')) {
    echo "array_last exists\n";
} else {
    echo "array_last does NOT exist\n";
}