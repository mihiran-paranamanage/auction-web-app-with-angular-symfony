<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version0001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE access_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, token VARCHAR(255) NOT NULL, INDEX IDX_B6A2DD68A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, item_id INT NOT NULL, bid NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, is_auto_bid TINYINT(1) DEFAULT \'0\' NOT NULL, date_time DATETIME NOT NULL, INDEX IDX_4AF2B3F3A76ED395 (user_id), INDEX IDX_4AF2B3F3126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(2000) DEFAULT NULL, price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, bid NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, close_date_time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_role_id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, INDEX IDX_8D93D6498E0E3CA6 (user_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_bid_config (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, max_bid_amount NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, INDEX IDX_854BF4E8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role_data_group (id INT AUTO_INCREMENT NOT NULL, user_role_id INT NOT NULL, data_group_id INT NOT NULL, can_read TINYINT(1) DEFAULT \'0\' NOT NULL, can_create TINYINT(1) DEFAULT \'0\' NOT NULL, can_update TINYINT(1) DEFAULT \'0\' NOT NULL, can_delete TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_BE978EF98E0E3CA6 (user_role_id), INDEX IDX_BE978EF9348A109B (data_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD68A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE user_bid_config ADD CONSTRAINT FK_854BF4E8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_role_data_group ADD CONSTRAINT FK_BE978EF98E0E3CA6 FOREIGN KEY (user_role_id) REFERENCES user_role (id)');
        $this->addSql('ALTER TABLE user_role_data_group ADD CONSTRAINT FK_BE978EF9348A109B FOREIGN KEY (data_group_id) REFERENCES data_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_role_data_group DROP FOREIGN KEY FK_BE978EF9348A109B');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3126F525E');
        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD68A76ED395');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3A76ED395');
        $this->addSql('ALTER TABLE user_bid_config DROP FOREIGN KEY FK_854BF4E8A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498E0E3CA6');
        $this->addSql('ALTER TABLE user_role_data_group DROP FOREIGN KEY FK_BE978EF98E0E3CA6');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE data_group');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_bid_config');
        $this->addSql('DROP TABLE user_role');
        $this->addSql('DROP TABLE user_role_data_group');
    }
}