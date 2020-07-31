#!/usr/bin/env sh

# Install WordPress.
wp core install \
  --title="Learn TDD" \
  --admin_user="Sleek_Devigner" \
  --admin_password="adewunmi65" \
  --admin_email="d.devignersplace@gmail.com" \
  --url="http://tddlearn.local" \
  --skip-email

# Update permalink structure.
wp option update permalink_structure "/%year%/%monthnum%/%postname%/" --skip-themes --skip-plugins

# Activate plugin.
wp plugin activate my-plugin
