#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo " => Installing watchdog_watchdog"
docker-compose exec -T drupal /bin/bash -c 'drush en -y watchdog_watchdog'

echo " => Running self-tests"
docker-compose exec -T drupal /bin/bash -c 'drush ev "\Drupal::service('"'"'watchdog_watchdog.self_tester'"'"')->selfTest();"'

echo " => Uninstalling watchdog_watchdog"
docker-compose exec -T drupal /bin/bash -c 'drush pmu -y watchdog_watchdog'

echo " => Done running self-tests. All WWatchdog modules should be uninstalled."
echo " =>"
