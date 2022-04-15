# Web-based Auction Application with Angular and Symfony

This is a web-based, real-time auction application that is developed specially for an antique item seller.
The application allows users to view list of items and bid on the items.
Additionally, with the auto-bidding feature, it allows users to activate auto-bidding functionality for selected items in order to bid automatically.

### Images & Videos
* Images of the application can be found in the _images_ folder.
* [Click here](https://drive.google.com/drive/folders/1mZjjC_NEZQTcPBP4auC_BIaGpki-6e7j?usp=sharing) to see the Demo.

### Languages & Tools

* Angular 11.2
* Angular Material
* Symfony 5.3.2
* Symfony Mailer
* Websocket with Ratchet 0.4
* MySQL 8.0
* REST APIs
* Docker + Docker Compose

### Features & Behaviours

* Home Page
1. List of items.
2. Users can filter items by "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
3. "Item Name", "Price", "Current Bid" and "Closing Date & Time" columns are sortable.
4. With the "Bid Now" button next to each item, users can go to the "Item Details" page to see the details and bid on the item.
5. Users cannot see  "Bid Now" button if the item's bid already closed. They will see "View Item" button instead.
6. Admin users also can bid on the items as same as Regular users if they need.
6. Pagination of the list is 10 by default. It is configurable to set 50 or 100 as well (Pagination & Sorting behaviours are similar in the other lists as well).

* Item Details Page
1. Users can see "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
2. Remaining time for the bid as a countdown.
3. "View Bid History" button to see the item's bid history.
4. "Download Bill" button to download the bill of the awarded item. This will only show if the item awarded to the logged-in user.
5. Users can submit bids.
6. Users can activate auto-bidding by selecting the "Activate Auto Bid" checkbox. This checkbox will be disabled until enable the auto-bidding process from the Auto Bid Configurations page. 
7. This page is automatically updating real-time (When someone else bid on the same item or Admin updates the item details).
8. If the user submits a bid which is below the current bid of the item, "Warning: Bid should be higher than the current bid of the item!" warning message shown.
9. Ones the countdown reaches to "0 day(s), 00 hr(s), 00 min(s), 00 sec(s)", the "Submit Bid" button will be automatically disabled. The button label will also be changed to "Bid Closed".
10. Ones the item closed, it will be awarded to the user who has the highest bid on the item. Winner's name, and the "Winning Bid" will also be displayed in this page (User may have to refresh or come back to the page to see the winning details if he was already there when the time closed).
11. User will not be able to submit the bid, ones the bid closed. Instead, they can only see the winning details and the bid history of the item.
12. If the user has the maximum bid for the item in the system, he cannot bid on the same item until someone else outbid it. In this case, user can see the notification "Warning: You already have the highest bid for this item!".
13. When the user bid on the item, an email notification with the bid details will be sent to all the other users who have bid on the same item.
14. Ones the item awarded, an email with the winner's details will be sent to the users who have bid on the item. Winner will get a separate email with the winning details.

* Admin Dashboard
1. List of items.
2. Only admin users can see this page.
3. Items can be filtered by "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
4. "Item Name", "Price", "Current Bid", "Closing Date & Time" columns are sortable.
5. Admin users can update item details with the "Edit" icon.
6. Admin users can delete items with the "Delete" icon. A confirmation message will also be shown before the deletion.
7. Pagination of the list is 10 by default. It is configurable to set 50 or 100 as well (Pagination & Sorting behaviours are similar in the other lists as well).

* Add Item
1. Only admin users can see this page.
2. Admin users can add items with item details such as "Item Name", "Closing Date", "Closing Time", "Description", "Price" and "Starting Bid Amount".
3. "Add" button will be disabled until all the fields of the form are valid.

* Auto Bid Configurations
1. Users can add auto-bid configurations such as "Maximum Bid Amount" and "Notify Percentage".
2. Users can see current bid amount and its percentage of the maximum bid amount.
3. "Save" button will be disabled if the form values are invalid.
4. Once the user save the configuration details, current bid amount, and the percentage will be updated.
5. Users can set the "Notify Percentage" to let them know once the bid amount reaches to that percentage. In this case, user can see the notification message "Warning: 90% of the maximum bid amount is reserved!".
6. Ones the maximum auto-bid amount reaches, auto-bidding process will be stopped, and a warning notification will be shown.
7. Users can enable/disable the auto-bidding process with the checkbox "Enable Auto Bid". When this is disabled, user cannot enable the "Activate Auto Bid" from the item details page.

* User Profile
1. With the "My profile" button in the navbar, users can go to their profile page.
2. In this page, users can see their user details such as "Name", "Role", "Username" and "Email".
3. Users can edit their profile details with the "Edit Profile" button.
4. Users can see the lists for "Item History", "Bid History" and "Awarded Item History" of the items they have bid on.
5. From the "Item History" list, User can see the current status of the item in the "Item Status" column.
6. From the "Awarded Item History" list, User can download the bill of the awarded item with the "Download Bill" button.

* Notifications on the system
1. "Warning: Bid should be higher than the current bid of the item!" - When the user tries to submit bid that is below the current bid of the item.
2. "Warning: You already have the highest bid for this item!" - When the user has the highest bid of the item on the system then he tries to outbid his own bid.
3. "Warning: 90% of the maximum bid amount is reserved!" - When the auto-bid percentage reaches the "Notify Percentage".
4. "Warning: Maximum bid amount has been reached & auto-bidding process stopped. Please increase the maximum bid amount to continue." - When the auto-bid amount reaches the "Maximum Bid Amount".

* Email Notifications on the system
1. New Bid Notification - When user bid on an item, this email notification with the bid details will be sent to all the other users who have bid on the same item.
2. Bid Closed And Awarded Notification - Once the item closed and awarded, this email notification with the item details will be sent to all the users (except the winner) who have bid on the item.
3. Bid Closed And Awarded Notification - Winner's Notification - Once the item closed and awarded, this email notification will be sent to the winner with the winning details.
4. Maximum Auto Bid Exceeded Notification - when the current bid exceeds the maximum auto-bid amount, this email notification will be sent to the user with the auto-bid amount details.

* Configurations
1. Accessibility of the behaviours can be changed from the database level.
2. Accessibility can be changed from the "user_role_data_group" table. First, find the row where "user_role_id" equals the expected role id, and "data_group_id" equals the expected data group id. Then enable/disable the permission ("canRead", "canCreate", "canUpdate", "canDelete").
3. "MAILER_DSN" - To send emails, First, you have to configure the dsn in the database. Login to the database (Browse http://localhost:9001/ and check the Credentials section for the credentials), change the "MAILER_DSN" value in the "config" table.
4. See the blog of the symfony mailer (https://symfony.com/doc/current/mailer.html) for more details about the symfony mailer dsn configurations.
5. To see the emails in the development environment, you can simply use Mailtrap (https://mailtrap.io/). Login to Mailtrap, go to the inbox, get your smtp credentials, change the USERNAME and PASSWORD in the "MAILER_DSN" value (`MAILER_DSN=smtp://USERNAME:PASSWORD@smtp.mailtrap.io:2525/?encryption=ssl&auth_mode=login`) with your credentials from the "config" table in the database.
6. See the Mailtrap dsn configurations (https://mailtrap.io/blog/send-emails-in-symfony/) for more details.
7. To send emails to real emails, you have to get a dsn from a third party provider (Google Gmail, Amazon SES, etc.).
8. See the "Using a 3rd Party Transport" section of the above symfony mailer blog mentioned in the 4th step.
9. "MAILER_FROM" - You can change the "From" email from the "config" table in the database.
10. "email_notification_enabled" - You can enable/disable sending emails from the "config" table in the database. When this is disabled, you can see all the emails which are generated from the system in the "email_queue" table.
11. To send emails, there is a Cron Task enabled in the docker php container which is running every minute. This will be automatically enabled by default after installing the system with "installer.sh" script.

* Security & Accessibility
1. As mentioned in the Configurations section, menu items and front end pages are configurable from the database.
2. Users cannot go to un-accessible urls by doing url attacks. For example, You log-in as admin, go to "Add Item" page, copy the url, logout and re-login as a "Regular" user and paste the url of the "Add Item" page. In this case, it goes to a Forbidden page.
3. All the authorizations which are defined in the database, are handled in both the front-end and API levels.
4. All the APIs are authenticated by access tokens.
5. User passwords are encrypted by MD5 encryption in database. 

* APIdoc
1. Go to the apidoc folder and click on the index.html file and open it from your browser. Then you can see all the details of the APIs.

* Credentials 
1. By default, the system has 2 Admin users and 2 Regular users as [username: admin1, password: admin1], [username: admin2, password: admin2], [username: user1, password: user1], [username: user2, password: user2].
2. admin1, admin2 users have "Admin" user role so, they can access the behaviours defined for the "Admin" role.
3. user1, user2 users are "Regular" users so, they cannot access "Admin" behaviours.
4. You can log into the database and change the accessibility if needed.
5. To do that, Access the database by the url, http://localhost:9001/ to log in to the database.
6. Credentials to access the database are, [System: MySql, Server: auction_mysql, Username: root, Password: 1234, Database: auction_mysql].
7. If you need to log into the php docker container in order to install composer libraries (if needed), go to the root folder, and run the docker command, `sudo docker exec -it auction_php bash`.

* Login to the database
1. Browse http://localhost:9001/
2. Credentials - [System: MySql, Server: auction_mysql, Username: root, Password: 1234, Database: auction_mysql]

* Login to docker containers
1. `sudo docker exec -it auction_php bash` - For the php container
2. `sudo docker exec -it auction_websocket sh` - For the websocket container
3. `sudo docker exec -it auction_mysql mysql -uroot -p1234` - For the mysql container

* Checking the docker logs
1. `sudo docker-compose logs -f [docker-compose-service-name]`
2. `sudo docker-compose logs -f php` - For the php container
3. `sudo docker-compose logs -f websocket` - For the websocket container
4. `sudo docker-compose logs -f client` - For the angular client container

* Used local ports
1. 4200 - Angular client app
2. 3306 - MySQL server
3. 8001 - Symfony server
4. 5000 - Websocket server
5. 5001 - Websocket server
6. 9000 - PHP server
7. 9001 - Adminer

* Add/Change the Cron Task (Cronjob)
1. By default, the send-emails cron task is running every minute.
2. To change that time interval or add a new cron task, First, login to the docker php container (`sudo docker exec -it auction_php bash`), run the command `crontab -l` to check the existing crontab, run the command `crontab -e` to open it from the nano text editor. After edit the crontab, press "Ctrl+X", and then press "Y" to save the changes. Finally, run the command `cron` to start the cron task.
3. If you get an error something similar to, "cron: can't lock /var/run/crond.pid, otherpid may be 246: Resource temporarily unavailable", kill the PID mentioned in the error message (here it is "246") by running `kill 246`, and run the `cron` command again.

### Installation (on Ubuntu)

1. Install Docker and Docker Compose (Refer https://docs.docker.com/engine/install/ubuntu/ and https://docs.docker.com/compose/install/).
2. Clone this repo (`git clone https://github.com/mihiran-paranamana/auction-web-app-with-angular-symfony.git`).
3. Move into the _auction-web-app-with-angular-symfony_ root folder (`cd auction-web-app-with-angular-symfony`).
4. Run the installer script (`./installer.sh` or `sudo bash ./installer.sh`).
5. Check whether any error occurred in the terminal (like "npm ERR!" or php "Fatal error:"). If there is any error shown, please read the below instructions.
6. Set "MAILER_DSN" to send emails from the "config" table in the database (see the Configurations section under the Features & Behaviours).
7. Browse http://localhost:4200/

* With the 4th step, It wil run `sudo docker-compose build` and `sudo docker-compose up -d` docker commands to build and up the containers. At the first time, this will take around 20-30 minutes since it needs to pull all the related docker images.
* While running the "installer.sh" script, it will ask to enter sudo password, at the end it will prompt a warning message saying, "WARNING! You are about to execute a migration in database "auction_mysql" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:"
* Just hit enter (since the default option is "yes") then installation will be completed.
* You can re-install the system by running the "installer.sh" script again. Now it won't take more than 1 minute.
* This system uses local ports 4200, 3306, 8001, 5000, 5001, 9000, 9001. So, after the installation some docker containers will not be getting up if you already used one of these ports.
* For example, if you use MySQL locally with 3306 port, MySQL container will not work. In that case, you may have to stop the local MySQL service by running, `sudo service mysql stop` and then run the "installer.sh" script again.
* If you get any npm errors (like npm ERR!) while installing, give full permission to the application root folder, delete "client/package-lock.json" file and run the "installer.sh" script again.
* If you get any composer errors while installing, give full permission to the application root folder, delete "server/composer-lock.json" file and run the "installer.sh" script again.
* If you get any composer errors something like, "Fatal error: Uncaught LogicException: Symfony Runtime is missing. Try running `composer require symfony/runtime`", First, log into the docker php container (sudo docker exec -it auction_php bash), then run the mentioned command in the error message (In this case, it is `composer require symfony/runtime`) inside the php docker container, exit from the container with "Ctrl+D", and finally, run the "installer.sh" script again.
* If you get any cron task failure (like, "no crontab for root"), change the crontab to add a new cron task (see the Change the Cron Task section). To run send-emails task in every minute, add the cron task `* * * * * /usr/local/bin/php /var/www/symfony/bin/console app:send-emails` to the crontab (put a new line after the cron task in the crontab).

### Installation (on Windows or Mac)

1. Install Docker Desktop (Refer https://docs.docker.com/docker-for-windows/install/ or https://docs.docker.com/docker-for-mac/install/).
2. Follow the above Ubuntu installation process. With docker, you won't have to do any other OS specific installation steps. Use the docker terminal to run the above commands.

### Contact Details

* Email: mihiran.hlrm@gmail.com
* SkypeId: live:mihiran.hlrm
