#!/bin/bash
#
# Run some checks on a running environment
#
set -e

echo " => Running self-tests"
docker-compose exec drupal /bin/bash -c 'drush ev "\Drupal::service('"'"'watchdog_watchdog.self_tester'"'"')->selfTest();"'

echo " => Uninstalling watchdog_watchdog_severity"
docker-compose exec drupal /bin/bash -c 'drush pmu -y watchdog_watchdog_severity'

echo " => Uninstalling watchdog_watchdog"
docker-compose exec drupal /bin/bash -c 'drush pmu -y watchdog_watchdog_ignore'

echo " => Done running self-tests. All WWatchdog modules should be uninstalled."
echo " =>"
