#!/bin/bash
#
# Destroy the environment.
#
set -e

docker-compose down -v
docker network rm watchdog_watchdog_default
