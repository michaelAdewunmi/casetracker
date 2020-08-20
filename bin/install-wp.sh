#!/usr/bin/env sh

# Install WordPress.
wp core install \
  --title="Case Tracker" \
  --admin_user="MeekAndGentl" \
  --admin_password="thisismyspace" \
  --admin_email="casetrackernaija@gmail.com" \
  --url="http://casetracker.local" \
  --skip-email

# Update permalink structure.
wp option update permalink_structure "/%year%/%monthnum%/%postname%/" --skip-themes --skip-plugins

# Activate plugin.
wp plugin activate my-plugin
