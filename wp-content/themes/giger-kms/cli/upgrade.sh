#!/usr/bin/env bash

HOST=$1

: ${HOST:="dront.dev"}
php cli-u-options.php --host=$HOST

#import
php cli-u-import-posts-csv.php --host=$HOST --file=dront_urls_actual_content.csv
php cli-u-import-posts-csv.php --host=$HOST --file=dront_urls_news_content.csv

php cli-u-import-files-csv.php --host=$HOST --file=dront_urls_bereginya.csv --tag=bereginya
php cli-u-import-files-csv.php --host=$HOST --file=dront_urls_publications.csv --tag=publication
php cli-u-import-files-csv.php --host=$HOST --file=dront_urls_reports.csv --tag=report
php cli-import-events-csv.php --host=$HOST --file=dront_events_all.csv
php cli-import-map-markers-csv.php --host=$HOST
php cli-u-testpost.php --host=$HOST

#new structure
#php cli-u-sections_init.php --host=$HOST
#php cli-u-sections_sort.php --host=$HOST
#php cli-u-people.php --host=$HOST
#php cli-u-pages.php --host=$HOST
#php cli-u-projects.php --host=$HOST
##
#php cli-u-menu.php --host=$HOST
#php cli-u-widgets.php --host=$HOST
