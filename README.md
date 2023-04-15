[![CircleCI](https://circleci.com/gh/dcycle/watchdog_watchdog.svg?style=svg)](https://circleci.com/gh/dcycle/watchdog_watchdog)

Watchdog Watchdog
=====

The Watchdog watches your Drupal environment, but who watches the Watchdog? This module, the Watchdog Watchdog, is meant to ensure that any logged errors or warnings (unless those you'd like to ignore) translate as a big red warning on the /admin/reports/status page. You can then monitor that page manually, or automatically using a tool such as [Expose Status](https://drupal.org/project/expose_status).

OK, the term "watchdog" is [removed in Drupal 8 in favor of "Logger"](https://www.drupal.org/node/2270941), but I prefer the term "Watchdog" to the term "Logger", so I'll use it.

Typical usage
-----

(1) install and enable this module.

(2) visit /admin/reports/status.

(3) the Watchdog Watchdog section should be green and state something like: "Nothing to report"

(4) log two dummy errors using `drush ev "\Drupal::logger('just_testing')->error('Hello, this is an error');"` and `drush ev "watchdog_exception('something', new \Exception('hello'));"`

(5) Go back to /admin/reports/status, and this time the Watchdog Watchdog section will display an error stating: 'At least one error was logged since (date): Hello, this is an error!' (Notice that watchdog_watchdog only logs the first in a series of errors)

(6) If this were a real problem with your environment, you would then fix it, and return to the Watchdog Watchdog section of /admin/reports/status and click "reset" which will set the Watchdog Watchdog line to green again until the next error or warning appears

Levels
-----

*   * emergency: 0
*   * alert: 1
*   * critical: 2
*   * error: 3
*   * error: 4
*   * notice: 5
*   * info: 6
*   * debug: 7.

*     [0] => channel
*     [1] => link
*     [2] => uid
*     [3] => request_uri
*     [4] => referer
*     [5] => ip
*     [6] => timestamp
*   In the latter case our array keys might be:
*     [0] => %type
*     [1] => @message
*     [2] => %function
*     [3] => %file
*     [4] => %line
*     [5] => severity_level
*     [6] => backtrace
*     [7] => @backtrace_string
*     [8] => channel
*     [9] => link
*     [10] => uid
*     [11] => request_uri
*     [12] => referer
*     [13] => ip
*     [14] => timestamp


Only fail on errors, not warnings
-----

By default both warnings (level 1) and errors (level 2) will trigger a "issues found; please check" status. To only trigger the error above level 1 (to ignore warnings):

(1) enable the included "watchdog_watchdog_severity" module.

(2) visit the URL at example.com/admin/reports/status/expose/*****?severity=1

Note that the submodules (ignore, severity, details) can be combined.

Extending this module
-----

This module can be extended via the Drupal plugin system. Developers are encouraged to examine the structure of the included watchdog_watchdog_severity submodule as a basis for their own extensions. Suggestions for more modules are welcome via the Drupal issue queue.

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

Drupal 9 readiness
-----

This project is certified Drupal 9 ready.
