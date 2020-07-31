# If the 'wordpress' volume wasn't during the down/up earlier, but the post port has changed,
# then we need to update it.
CURRENT_URL=$(docker-compose run -T --rm cli option get siteurl)

if [ "$CURRENT_URL" != "http://localhost:$HOST_PORT" ]; then
	docker-compose run --rm cli option update home "http://localhost:$HOST_PORT" >/dev/null
	docker-compose run --rm cli option update siteurl "http://localhost:$HOST_PORT" >/dev/null
fi
echo -e $(status_message "Server is running at:")
echo -e $(status_message "http://localhost:$HOST_PORT")

# Install Composer
echo -e $(status_message "Installing and updating Composer modules...")
docker-compose run --rm composer install

# Install the PHPUnit test scaffolding
echo -e $(status_message "Installing PHPUnit test scaffolding...")
docker-compose run --rm wordpress_phpunit /app/bin/install-wp-tests.sh wordpress_test root example mysql "${WP_VERSION}" false >/dev/null
echo -e $(status_message "Completed installing tests")