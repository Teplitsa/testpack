#!/usr/bin/env bash

HOST=$1

: ${HOST:="dront.dev"}
php cli-u-options.php --host=$HOST

#import

# import all old content
#php cli-u-import-posts-csv.php --host=$HOST --file=data/dront_urls_actual_content.csv
#php cli-u-import-posts-csv.php --host=$HOST --file=data/dront_urls_news_content.csv
#php cli-u-import-posts-csv.php --host=$HOST --file=data/dront_urls_old_news_content.csv
#php cli-u-import-posts-csv.php --host=$HOST --file=data/dront_urls_old_other_content.csv
#php cli-u-import-posts-csv.php --host=$HOST --file=data/dront_urls_old_defence_content.csv

# import old files
#php cli-u-import-files-csv.php --host=$HOST --file=data/dront_urls_bereginya.csv --tag=bereginya
#php cli-u-import-files-csv.php --host=$HOST --file=data/dront_urls_publications.csv --tag=publication
#php cli-u-import-files-csv.php --host=$HOST --file=data/dront_urls_reports.csv --tag=report

php cli-u-fix-import-data.php --host=$HOST

php cli-import-events-csv.php --host=$HOST --file=data/dront_events_all.csv
php cli-import-map-markers-csv.php --host=$HOST
php cli-u-testpost.php --host=$HOST

# new structure
php cli-u-sections_init.php --host=$HOST
php cli-u-sections_sort.php --host=$HOST
php cli-u-people.php --host=$HOST
php cli-u-pages.php --host=$HOST
php cli-u-projects.php --host=$HOST

# import new prepared data
php cli-u-import-landings-csv.php --host=$HOST --file=data/landings.csv
php cli-u-import-projects-csv.php --host=$HOST --file=data/projects.csv

# setup posts
php cli-u-posts.php --host=$HOST

# setup tags
php cli-u-tags.php --host=$HOST

# set posts thumbnails
php cli-u-posts-thumbnails.php --host=$HOST

# init landings
php cli-u-landing_init.php --host=$HOST

# menu
php cli-u-menu.php --host=$HOST
#php cli-u-widgets.php --host=$HOST
