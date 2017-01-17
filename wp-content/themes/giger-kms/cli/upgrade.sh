#!/usr/bin/env bash

HOST=$1

: ${HOST:="nlnew.dev"}

php cli-u-options.php --host=$HOST
php cli-u-sections_init.php --host=$HOST
php cli-u-sections_sort.php --host=$HOST
php cli-u-pages.php --host=$HOST
php cli-u-import-books-csv.php --host=$HOST
php cli-u-import-stories-csv.php --host=$HOST

php cli-u-menu.php --host=$HOST
php cli-u-widgets.php --host=$HOST