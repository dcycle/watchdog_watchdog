#!/bin/bash
set -e
source ~/.docker-host-ssh-credentials

# Create a droplet
PRIVATE_IP=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" \
  "./digitalocean/scripts/new-droplet.sh watchdog-watchdog")
# https://github.com/dcycle/docker-digitalocean-php#public-vs-private-ip-addresses
IP=$(ssh "$DOCKERHOSTUSER@$DOCKERHOST" "./digitalocean/scripts/list-droplets.sh" |grep "$PRIVATE_IP" --after-context=10|tail -1|cut -b 44-)
echo "Created Droplet at $IP"
sleep 90
ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no \
  root@"$IP" \
  "git clone http://github.com/dcycle/watchdog_watchdog && \
  cd watchdog_watchdog && \
  ./scripts/ci.sh"
