#!/bin/sh

echo "Installing . . . ";

# Adding php src files
sudo rm -rf ./php/src/*
sudo cp -R ./server/composer.json ./php/src/
sudo cp -R ./server/composer.lock ./php/src/

# Running docker build and up commands in order to build docker images and up the containers.
sudo docker-compose build
sudo docker-compose up -d

# Database configurations
sudo docker exec -it auction_mysql mysql -uroot -p1234 -hauction_mysql -e "DROP DATABASE IF EXISTS auction_mysql;"
sudo docker exec -it auction_mysql mysql -uroot -p1234 -hauction_mysql -e "CREATE DATABASE auction_mysql;"
# sudo docker exec -it auction_php php bin/console make:migration
sudo docker exec -it auction_php php bin/console doctrine:migrations:migrate

# Cron job for sending emails
# Runs every minute
sudo docker exec -it auction_php sh -c "echo '* * * * * /usr/local/bin/php /var/www/symfony/bin/console app:send-emails' | crontab -"
sudo docker exec -it auction_php cron

echo "Installation Completed. Browse http://localhost:4200/";
