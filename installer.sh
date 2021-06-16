#!/bin/sh

echo "Installing . . . ";

# Adding php src files
sudo rm -rf ./php/src/*
sudo cp -R ./server/composer.json ./php/src/
sudo cp -R ./server/composer.lock ./php/src/

# Running docker build and up commands in order to build docker images and up the containers.
sudo docker-compose build
sudo docker-compose up -d

echo "Installation Completed. Browse http://localhost:4200/";
