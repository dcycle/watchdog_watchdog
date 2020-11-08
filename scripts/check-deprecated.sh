#!/bin/bash
#
# Check for deprecated code.
#
set -e

docker run --rm -v "$(pwd)":/var/www/html/modules/watchdog_watchdog dcycle/drupal-check:1 watchdog_watchdog/src
