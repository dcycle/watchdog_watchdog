[![CircleCI](https://circleci.com/gh/dcycle/watchdog_watchdog.svg?style=svg)](https://circleci.com/gh/dcycle/watchdog_watchdog)

Watchdog Watchdog
=====

Monitors any environment and remembers the first error to occur, triggering an error on /admin/reports/status. You can then monitor that page manually, or automatically using a tool such as [Expose Status](https://drupal.org/project/expose_status).

Typical usage
-----

(1) install and enable this module.

(2) visit /admin/reports/status.

(3) the Watchdog Watchdog section should be green and state something like: "Nothing to report"

(4) log two mock errors using `drush ev "\Drupal::logger('just_testing')->error('Hello, this is an error');"` and `drush ev "\Drupal::logger('just_testing')->error('%type: @message in %function (line %line of %file).', \Drupal\Core\Utility\Error::decodeException(new \Exception('hello')));"` (the latter being [the standard way of logging an exception](https://www.drupal.org/node/2932520))

(5) Go back to /admin/reports/status, and this time the Watchdog Watchdog section will display an error stating: 'At least one error was logged since (date): Hello, this is an error!' (Notice that watchdog_watchdog only logs the first in a series of errors)

(6) If this were a real problem with your environment, you would then fix it, then reset the system using `drush ev "watchdog_watchdog()->reset();"` and return to the Watchdog Watchdog section of /admin/reports/status to monitor for new errors.

Levels and extending this module
-----

This module can be extended via the Drupal plugin system. Developers are encouraged to examine the structure of the included files in ./src/plugins/* which can be used as a base for their own plugins.

For example, these are the log message severity levels in Drupal:

* emergency: 0
* alert: 1
* critical: 2
* error: 3
* warning: 4
* notice: 5
* info: 6
* debug: 7

In ./src/Plugin/WWatchdogPlugin/WWatchdogBaseCase.php, Watchdog Watchdog only logs errors that are below level 3. You can define your plugin in a custom module, with a higher weight, if you would like to log different error levels or ignore certain errors.

Local development
-----

If you install Docker on your computer:

* you can set up a complete local development workspace by downloading this codebase and running `./scripts/deploy.sh` for Drupal 9 development, or `./scripts/deploys.sh 8` for Drupoal 8 development. You do not need a separate Drupal instance or database for local development, only Docker. `./scripts/uli.sh` will provide you with a login link to your environment.
* you can destroy your local environment by running `./scripts/destroy.sh`.
* you can run all tests by running `./scripts/ci.sh`; please make sure all tests before submitting a patch.

Automated testing
-----

This module's main page is on [Drupal.org](http://drupal.org/project/watchdog_watchdog); a mirror is kept on [GitHub](http://github.com/dcycle/watchdog_watchdog).

Unit tests are performed on Drupal.org's infrastructure and in GitHub using CircleCI. Linting is performed on GitHub using CircleCI and Drupal.org. For details please see  [Start unit testing your Drupal and other PHP code today, October 16, 2019, Dcycle Blog](https://blog.dcycle.com/blog/2019-10-16/unit-testing/).

* [Test results on Drupal.org's testing infrastructure](https://www.drupal.org/node/3098822/qa)
* [Test results on CircleCI](https://circleci.com/gh/dcycle/watchdog_watchdog)

To run automated tests locally, install Docker and type:

    ./scripts/ci.sh
