#!/bin/bash

abspath=$(cd ${0%/*} && pwd -P) 

if [[ `which php` == "" ]]; then
  echo "Error: can't locate PHP CLI interpreter"
  exit 1
fi

php -q $abspath/upgrade.php
