# Web-based Auction Application with Angular and Symfony

This is a web-based auction application that is developed specially for an antique item seller.
The application allows users to view list of items and bid on the items.
Additionally, with the auto-bidding feature, it allows users to activate auto-bidding functionality for selected items in order to bid automatically.

### Features & Behaviours

* Home Page
1. List of items.
2. Users can filter items by "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
3. "Item Name", "Price", "Current Bid" and "Closing Date & Time" columns are sortable.
4. With the "Bid Now" button next to each item, users can go to the "Item Details" page to see the details and bid on the item.
5. Users cannot see  "Bid Now" button if the item's bid is already closed.
6. Admin users also can bid on the items as same as Regular users if they need.
6. Pagination of the list is 10 by default. It is configurable to set 50 or 100 as well.

* Item Details Page
1. Users can see "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
2. Remaining time for the bid as a countdown.
3. "View Bid History" button to see the bid history (This can only be seen by the Admin users).
4. Users can submit bid.
5. Users can activate auto-bidding by selecting the "Activate Auto Bid" checkbox.
6. This page is automatically refreshing every 30 seconds to fetch the latest bid details of the item.
7. Ones the user submitted the bid, the item details refreshed and gets the latest details of the item.
8. If the user submits a bid which is below the current bid of the item, "Warning: Bid should be higher than the current bid of the item!" warning message shown.
9. Ones the countdown reaches to "0 day(s), 00 hr(s), 00 min(s), 00 sec(s)", the "Submit Bid" button will be automatically disabled. The button label will also be changed to "Bid Closed".
10. User will not be able to submit the bid ones the bid has been closed.
11. If the user has the maximum bid for the item in the system, he cannot bid on the same item until someone else outbid it. In this case, user can see the notification "Warning: You already have the highest bid for this item!".

* Bid History
1. Only admin users can see this page but, if needed, this accessibility can be changed easily from the database level (see the Configurations section below).
2. Admin users can see "User", "Bid" and "Date & Time" of the bid in the history list.
3. Pagination of the list is 10 by default. It is configurable to set 50 or 100 as well.

* Admin Dashboard
1. List of items.
2. Only admin users can see this page.
3. Items can be filtered by "Item Name", "Description", "Price", "Current Bid" and "Closing Date & Time".
4. "Item Name", "Price", "Current Bid", "Closing Date & Time" columns are sortable.
5. Admin users can update item details with the "Edit" icon.
6. Admin users can delete items with the "Delete" icon. A confirmation message will also be shown before the deletion.
7. Pagination of the list is 10 by default. It is configurable to set 50 or 100 as well.

* Add Item
1. Only admin users can see this page.
2. Admin users can add items with item details such as "Item Name", "Closing Date", "Closing Time", "Description", "Price" and "Starting Bid Amount".
3. "Add" button will be disabled until all the fields of the form are valid.

* Auto Bid Configurations
1. Users can add auto-bid configurations such as "Maximum Bid Amount" and "Notify Percentage".
2. Users can see current bid amount and its percentage of the maximum bid amount.
3. "Save" button will be disabled if the form values are invalid.
4. Once the user save the configuration details, current bid amount and the percentage will be updated.
5. Users can set the "Notify Percentage" to let them know once the bid amount reaches to that percentage. In this, case user can see the notification message "Warning: 90% of the maximum bid amount is reserved!".
6. Ones the maximum auto-bid amount reaches, auto-bid process will be stopped and a warning notification will be shown.

* Notifications on the system
1. "Warning: Bid should be higher than the current bid of the item!" - When the user tries to submit bid that is below the current bid of the item.
2. "Warning: You already have the highest bid for this item!" - When the user has the highest bid of the item on the system then he tries to outbid his own bid.
3. "Warning: 90% of the maximum bid amount is reserved!" - When the auto-bid percentage reaches the "Notify Percentage".
4. "Warning: Maximum bid amount has been reached & auto-bidding process stopped. Please increase the maximum bid amount to continue." - When the auto-bid amount reaches the "Maximum Bid Amount".

* Configurations
1. Accessibility of the behaviours can be changed from the database level.
As an example, If Regular users also needed to see the bid history, we can simply change it from the "user_role_data_group" table. Fist find the row where "user_role_id" equals "Regular" role id, and "data_group_id" equals "bid_history" data group id. (if you see the class diagram in the "images" folder, you will quickly get it!). Then change the "canRead" permission from 0 to 1. Now you can see "Regular" users also see the "Bid History" button in the Item Details page.

* Security & Accessibility
1. As mentioned in the Configurations section menu items and front end pages are configurable from the database.
2. Users cannot go to un-accessible urls by doing url attacks. As an example, You log-in as admin, go to "Add Item" page, copy the url, logout and re-login as a "Regular" user and paste the url of the "Add Item" page. In this case, it goes to a Forbidden page.
3. All the authorizations which are defined in the database, are handled in both the front-end and API levels.
4. All the APIs are authenticated by access tokens.
5. User credentials are hardcoded in the front-end function, "isCredentialsValid()", in the "login-page.component.ts" (auction-web-app-with-angular-symfony/client/src/app/public/login-page/login-page.component.ts).

* APIdoc
1. Go to the apidoc folder and click on the index.html file and open it from your browser. Then you can see all the details of the APIs.

* Credentials 
1. By default, the system has 2 Admin users and 2 Regular users as [username: admin1, password: admin1], [username: admin2, password: admin2], [username: user1, password: user1], [username: user2, password: user2].
2. admin1, admin2 users have "Admin" user role so, they can access the behaviours defined for the "Admin" role.
3. user1, user2 users are "Regular" users so, they cannot access "Admin" behaviours.
4. You can log into the database and change the accessibility if needed.
5. To do that, Access the database by the url, http://localhost:9001/ to log in to the database.
6. Credentials to access the database are, [System: MySql, Server: auction_mysql, Username: root, Password: 1234, Database: auction_mysql].
7. If you need to log into the php docker container in order to install composer libraries (if needed), go to the root folder, and run the docker command, "sudo docker exec -it auction_php bash".

### Used Technologies

1. Angular 11.2
2. Angular Material
3. Symfony 5.3.2
3. MySQL 8.0
4. REST APIs
5. Docker + Docker Compose

### Install (on Ubuntu)

1. Install Docker and Docker Compose (Refer https://docs.docker.com/engine/install/ubuntu/ and https://docs.docker.com/compose/install/).
2. Clone this repo ($ git clone https://github.com/mihiran-paranamana/auction-web-app-with-angular-symfony.git).
3. Move into the "auction-web-app-with-angular-symfony" root folder ($ cd auction-web-app-with-angular-symfony).
4. Run the installer script ($ ./installer.sh or $ sudo bash ./installer.sh).
5. Browse http://localhost:4200/

* With the 5th step, It wil run "sudo docker-compose build" and "sudo docker-compose up -d" docker commands to build and up the containers. At the first time, this will take around 20-30 minutes since it needs to pull all the related docker images.
* While running the ./installer.sh script, it will ask to enter sudo password, at the end it will prompt a warning message saying, "WARNING! You are about to execute a migration in database "auction_mysql" that could result in schema changes and data loss. Are you sure you wish to continue? (yes/no) [yes]:"
* Just hit enter (since the default option is "yes") then installation will be completed.
* You can re-install the system by running the ./installer.sh script again. Now it won't take more than 1 minute.
* This system uses local ports 4200, 3306, 8001, 9000, 9001. So, after the installation some docker containers will not be up if you already used one of these ports.
* As an example, if you use MySQL locally with 3306 port, MySQL container will not work. In that case, you may have to stop the local MySQL service by running, "sudo service mysql stop" and then run the ./installer.sh script again.

### Install (on Windows or Mac)

1. Install Docker Desktop (Refer https://docs.docker.com/docker-for-windows/install/ or https://docs.docker.com/docker-for-mac/install/).
2. Follow the above Ubuntu installation process. With docker, you won't have to do any other OS specific installation steps. Use the docker terminal to run the above commands.

### Contact Details

* Email: mihiran.hlrm@gmail.com
* SkypeId: live:mihiran.hlrm