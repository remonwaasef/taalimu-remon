<?php
file_put_contents('filtered_log.txt', substr(file_get_contents('storage/logs/laravel.log'), -5000));
