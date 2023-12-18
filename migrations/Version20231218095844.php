<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218095844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, payment_id INT NOT NULL, shipping_id INT NOT NULL, date DATETIME NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_F5299398A76ED395 (user_id), UNIQUE INDEX UNIQ_F52993984C3A3BB (payment_id), INDEX IDX_F52993984887F3F8 (shipping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_product_entry (id INT AUTO_INCREMENT NOT NULL, order_ref_id INT NOT NULL, item_id INT NOT NULL, count INT NOT NULL, INDEX IDX_271EAEFEE238517C (order_ref_id), INDEX IDX_271EAEFE126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id INT AUTO_INCREMENT NOT NULL, method_id INT NOT NULL, status_id INT NOT NULL, paid_amount DOUBLE PRECISION NOT NULL, INDEX IDX_6D28840D19883967 (method_id), INDEX IDX_6D28840D6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping (id INT AUTO_INCREMENT NOT NULL, method_id INT NOT NULL, status_id INT NOT NULL, address_id INT NOT NULL, shipped_date DATETIME DEFAULT NULL, tracking INT DEFAULT NULL, INDEX IDX_2D1C172419883967 (method_id), INDEX IDX_2D1C17246BF700BD (status_id), INDEX IDX_2D1C1724F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping_address (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, building_nr INT NOT NULL, locale_nr INT DEFAULT NULL, postcode INT NOT NULL, city VARCHAR(255) NOT NULL, phone_nr INT NOT NULL, INDEX IDX_EB066945A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shipping_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_item (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, is_hidden TINYINT(1) NOT NULL, require_login TINYINT(1) NOT NULL, price DOUBLE PRECISION NOT NULL, INDEX IDX_DEE9C36512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993984887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id)');
        $this->addSql('ALTER TABLE order_product_entry ADD CONSTRAINT FK_271EAEFEE238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_product_entry ADD CONSTRAINT FK_271EAEFE126F525E FOREIGN KEY (item_id) REFERENCES shop_item (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D19883967 FOREIGN KEY (method_id) REFERENCES payment_method (id)');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840D6BF700BD FOREIGN KEY (status_id) REFERENCES payment_status (id)');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C172419883967 FOREIGN KEY (method_id) REFERENCES shipping_method (id)');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C17246BF700BD FOREIGN KEY (status_id) REFERENCES shipping_status (id)');
        $this->addSql('ALTER TABLE shipping ADD CONSTRAINT FK_2D1C1724F5B7AF75 FOREIGN KEY (address_id) REFERENCES shipping_address (id)');
        $this->addSql('ALTER TABLE shipping_address ADD CONSTRAINT FK_EB066945A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shop_item ADD CONSTRAINT FK_DEE9C36512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984C3A3BB');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993984887F3F8');
        $this->addSql('ALTER TABLE order_product_entry DROP FOREIGN KEY FK_271EAEFEE238517C');
        $this->addSql('ALTER TABLE order_product_entry DROP FOREIGN KEY FK_271EAEFE126F525E');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D19883967');
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840D6BF700BD');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C172419883967');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C17246BF700BD');
        $this->addSql('ALTER TABLE shipping DROP FOREIGN KEY FK_2D1C1724F5B7AF75');
        $this->addSql('ALTER TABLE shipping_address DROP FOREIGN KEY FK_EB066945A76ED395');
        $this->addSql('ALTER TABLE shop_item DROP FOREIGN KEY FK_DEE9C36512469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE order_product_entry');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE payment_status');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('DROP TABLE shipping_address');
        $this->addSql('DROP TABLE shipping_method');
        $this->addSql('DROP TABLE shipping_status');
        $this->addSql('DROP TABLE shop_item');
        $this->addSql('DROP TABLE user');
    }
}
