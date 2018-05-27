#!/bin/sh

php -d display_errors=On -f nukedb.php
php -d display_errors=On -f readhints.php
php -d display_errors=On -f readquestions.php
php -d display_errors=On -f readteams.php
php -d display_errors=On -f readtreinhints.php
php -d display_errors=On -f readkoppel.php
