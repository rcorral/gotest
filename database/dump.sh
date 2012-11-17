#!/bin/bash

read -s -p "Enter Password: " dbpass

mysqldump --opt --dump-date --no-data -u root -p$dbpass clicker eab78_test_answers eab78_test_questions eab78_test_question_options eab78_test_question_types eab78_test_sessions eab78_test_tests | sed -e 's#AUTO_INCREMENT=[0-9]*##g' > dump.sql

echo $'\r\r\r'"-- Dumping rows for eab78_test_question_types table" >> dump.sql

mysqldump --skip-triggers --compact --no-create-info -u root -p$dbpass clicker eab78_test_question_types >> dump.sql