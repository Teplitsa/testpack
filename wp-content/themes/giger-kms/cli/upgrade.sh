#!/usr/bin/env bash

HOST=$1

: ${HOST:="nlnew.dev"}

php cli-u-options.php --host=$HOST
php cli-u-sections_init.php --host=$HOST

php cli-u-menu.php --host=$HOST