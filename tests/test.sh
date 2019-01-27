#!/bin/bash
cat ../db_structure.sql | mysql -uinvoicelion -pinvoicelion invoicelion
./vendor/bin/phpunit SignUpTest.php
