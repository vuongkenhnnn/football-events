#!/bin/bash

# Create necessary directories
mkdir -p /var/log/supervisor
mkdir -p /var/run

# Start supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
