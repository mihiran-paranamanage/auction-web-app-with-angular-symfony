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
        $this->addSql("SET @data_group_item_id := (SELECT `id` FROM `data_group` WHERE `name`='item');");
        $this->addSql("SET @data_group_bid_id := (SELECT `id` FROM `data_group` WHERE `name`='bid');");
        $this->addSql("SET @data_group_bid_history_id := (SELECT `id` FROM `data_group` WHERE `name`='bid_history');");
        $this->addSql("SET @data_group_configure_auto_bid_id := (SELECT `id` FROM `data_group` WHERE `name`='configure_auto_bid');");
        $this->addSql("SET @data_group_admin_dashboard_id := (SELECT `id` FROM `data_group` WHERE `name`='admin_dashboard');");

        // Users
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`) VALUES ('admin1', 'admin1', @user_role_admin_id);");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`) VALUES ('admin2', 'admin2', @user_role_admin_id);");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`) VALUES ('user1', 'user1', @user_role_regular_id);");
        $this->addSql("INSERT INTO `user` (`username`, `password`, `user_role_id`) VALUES ('user2', 'user2', @user_role_regular_id);");
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
                            VALUES (@user_role_regular_id, @data_group_item_id, 1, 0, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_bid_id, 1, 1, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_bid_history_id, 0, 0, 0, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_configure_auto_bid_id, 1, 1, 1, 0);");
        $this->addSql("INSERT INTO `user_role_data_group` (`user_role_id`, `data_group_id`, `can_read`, `can_create`, `can_update`, `can_delete`) 
                            VALUES (@user_role_regular_id, @data_group_admin_dashboard_id, 0, 0, 0, 0);");

        // Access tokens
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_admin1_id, 'af874ho9s8dfush6');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_admin2_id, 'bf874ho9s8dfush7');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_user1_id, 'cf874ho9s8dfush8');");
        $this->addSql("INSERT INTO `access_token` (`user_id`, `token`) VALUES (@user_user2_id, 'df874ho9s8dfush9');");
    }

    public function down(Schema $schema): void
    {
        // down() migration
    }
}
