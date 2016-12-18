#!/usr/bin/env bash

HOST=$1

: ${HOST:="dront.dev"}

#new structure
php cli-u-sections_init.php --host=$HOST
php cli-u-sections_sort.php --host=$HOST
php cli-u-people.php --host=$HOST
php cli-u-pages.php --host=$HOST
php cli-u-projects.php --host=$HOST
php cli-u-menu.php --host=$HOST
