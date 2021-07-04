<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Load master data
 */
final class Version0002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // up() migration

        // User roles
        $this->addSql("INSERT INTO `user_role` (`name`) VALUES ('Admin');");
        $this->addSql("INSERT INTO `user_role` (`name`) VALUES ('Regular');");
        $this->addSql("SET @user_role_admin_id := (SELECT `id` FROM `user_role` WHERE `name`='Admin');");
        $this->addSql("SET @user_role_regular_id := (SELECT `id` FROM `user_role` WHERE `name`='Regular');");

        // Data groups
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('item');");
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('bid');");
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('bid_history');");
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('configure_auto_bid');");
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('admin_dashboard');");
        $this->addSql("INSERT INTO `data_group` (`name`) VALUES ('user_details');");
        $this->addSql("SET @data_group_item_id := (SELECT `id` FROM `data_group` WHERE `name`='item');");
        $this->addSql("SET @data_group_bid_id := (SELECT `id` FROM `data_group` WHERE `name`='bid');");
        $this->addSql("SET @data_group_bid_history_id := (SELECT `id` FROM `data_group` WHERE `name`='bid_history');");
        $this->addSql("SET @data_group_configure_auto_bid_id := (SELECT `id` FROM `data_group` WHERE `name`='configure_auto_bid');");
        $this->addSql("SET @data_group_admin_dashboard_id := (SELECT `id` FROM `data_group` WHERE `name`='admin_dashboard');");
        $this->addSql("SET @data_group_user_details := (SELECT `id` FROM `data_group` WHERE `name`='user_details');");

        // Users
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`, `email`, `first_name`, `last_name`) VALUES ('admin1', 'e00cf25ad42683b3df678c61f42c6bda', @user_role_admin_id, 'admin1@gmail.com', 'John', 'Doe');");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`, `email`, `first_name`, `last_name`) VALUES ('admin2', 'c84258e9c39059a89ab77d846ddab909', @user_role_admin_id, 'admin2@gmail.com', 'Richard', 'Roe');");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`, `email`, `first_name`, `last_name`) VALUES ('user1', '24c9e15e52afc47c225b757e7bee1f9d', @user_role_regular_id, 'user1@gmail.com', 'Mike', 'Smith');");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`, `email`, `first_name`, `last_name`) VALUES ('user2', '7e58d63b60197ceb55a1c487989a3720', @user_role_regular_id, 'user2@gmail.com', 'Maria', 'Rodriguez');");
        $this->addSql("SET @user_admin1_id := (SELECT `id` FROM `user` WHERE `username`='admin1');");
        $this->addSql("SET @user_admin2_id := (SELECT `id` FROM `user` WHERE `username`='admin2');");
        $this->addSql("SET @user_user1_id := (SELECT `id` FROM `user` WHERE `username`='user1');");
        $this->addSql("SET @user_user2_id := (SELECT `id` FROM `user` WHERE `username`='user2');");

        // User role data groups
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_item_id, 1, 1, 1, 1);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_bid_id, 1, 1, 1, 1);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_bid_history_id, 1, 1, 1, 1);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_configure_auto_bid_id, 1, 1, 1, 1);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_admin_dashboard_id, 1, 1, 1, 1);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_admin_id, @data_group_user_details, 1, 0, 1, 0);");

        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_item_id, 1, 0, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_bid_id, 1, 1, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_bid_history_id, 0, 0, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_configure_auto_bid_id, 1, 1, 1, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_admin_dashboard_id, 0, 0, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_user_details, 1, 0, 1, 0);");

        // Access tokens
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_admin1_id, 'af874ho9s8dfush6');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_admin2_id, 'bf874ho9s8dfush7');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_user1_id, 'cf874ho9s8dfush8');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_user2_id, 'df874ho9s8dfush9');");

        // Email Notification Templates
        $this->addSql("INSERT INTO `email_notification_template` (`name`, `subject`, `body`) VALUES ('New Bid Notification', 'New Bid Notification', '<p>Hi #recipientFirstName# #recipientLastName#,
<br /><br />This is to notify you that there has been a new bid submitted on the item, #itemName#.</p>
<p>Item Details:</p>
<p>- Item: #itemName#</p>
<p>- Bid Owner: #bidOwnerFirstName# #bidOwnerLastName#</p>
<p>- Bid: $#bid#</p>
<p>- Auto Bid: #isAutoBid#</p>
<p>- Timestamp: #dateTime#</p>
<p><br />Thank you.<br /><br />This is an automated notification.</p>');");

        $this->addSql("INSERT INTO `email_notification_template` (`name`, `subject`, `body`) VALUES ('Bid Closed And Awarded Notification', 'Bid Closed And Awarded! - Item: #itemName#', '<p>Hi #recipientFirstName# #recipientLastName#,
<br /><br />This is to notify you that the bidding time of the item, #itemName# has finished and the item was awarded.</p>
<p>Item Details:</p>
<p>- Item: #itemName#</p>
<p>- Awarded To: #awardedUserFirstName# #awardedUserLastName#</p>
<p>- Winning Bid: $#winningBid#</p>
<p>- Timestamp: #dateTime#</p>
<p><br />Thank you.<br /><br />This is an automated notification.</p>');");

        $this->addSql("INSERT INTO `email_notification_template` (`name`, `subject`, `body`) VALUES ('Bid Closed And Awarded Notification - Winner', 'Congratulations! You Won the Item: #itemName#', '<p>Hi #recipientFirstName# #recipientLastName#,
<br /><br />This is to notify you that you are awarded the item, #itemName#.</p>
<p>Item Details:</p>
<p>- Item: #itemName#</p>
<p>- Awarded To: #awardedUserFirstName# #awardedUserLastName#</p>
<p>- Winning Bid: $#winningBid#</p>
<p>- Timestamp: #dateTime#</p>
<p><br />Thank you.<br /><br />This is an automated notification.</p>');");

        $this->addSql("INSERT INTO `email_notification_template` (`name`, `subject`, `body`) VALUES ('Maximum Auto Bid Exceeded Notification', 'Maximum Auto Bid Amount Exceeded!', '<p>Hi #recipientFirstName# #recipientLastName#,
<br /><br />This is to notify you that the maximum auto-bid amount has exceeded and the auto-bidding process stopped.</p>
<p>Please increase the maximum bid amount to continue.</p>
<p>Auto Bid Configuration Details:</p>
<p>- Maximum Bid Amount: #maxBidAmount#</p>
<p>- Current Bid Amount: #currentBidAmount#</p>
<p>- Auto-bidding Status: On-hold</p>
<p><br />Thank you.<br /><br />This is an automated notification.</p>');");

        // Item Bill Templates
        $this->addSql("INSERT INTO `item_bill_template` (`name`, `template`) VALUES ('Item Awarded Bill', '<p>Congratulations !!!
<br /><br />You are awarded the item, #itemName#.</p>
<p>Item Details:</p>
<p>- Item: #itemName#</p>
<p>- Item Owner: #itemOwnerFirstName# #itemOwnerLastName#</p>
<p>- Winning Bid: $#winningBid#</p>
<p>- Timestamp: #dateTime#</p>');");

        // Configurations
        $this->addSql("INSERT INTO `config` (`property`, `value`) VALUES ('MAILER_DSN', 'smtp://3bdf49e6b2f7d1:95d86fe3c23e6a@smtp.mailtrap.io:2525/?encryption=ssl&auth_mode=login');");
        $this->addSql("INSERT INTO `config` (`property`, `value`) VALUES ('MAILER_FROM', 'no-reply@notifications.auctionweb.com');");
        $this->addSql("INSERT INTO `config` (`property`, `value`) VALUES ('email_notification_enabled', '1');");
    }

    public function down(Schema $schema): void
    {
        // down() migration
    }
}
