<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260407135642 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE auction (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image_file_name VARCHAR(255) DEFAULT NULL, start_bid INT NOT NULL, minimum_bid INT NOT NULL, is_only_one_bid_allowed TINYINT(1) NOT NULL, quantity INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_DEE4F593D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auction_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL, object_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL, discriminator VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, transaction_hash VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT NULL, diffs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', blame_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user_fqdn VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user_firewall VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL, ip VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_7068a6558a0a680bab4c8d9b01fc6900_idx (type), INDEX object_id_7068a6558a0a680bab4c8d9b01fc6900_idx (object_id), INDEX discriminator_7068a6558a0a680bab4c8d9b01fc6900_idx (discriminator), INDEX transaction_hash_7068a6558a0a680bab4c8d9b01fc6900_idx (transaction_hash), INDEX blame_id_7068a6558a0a680bab4c8d9b01fc6900_idx (blame_id), INDEX created_at_7068a6558a0a680bab4c8d9b01fc6900_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid (id INT AUTO_INCREMENT NOT NULL, auction_id INT NOT NULL, user_id INT NOT NULL, amount INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_4AF2B3F3D17F50A6 (uuid), INDEX IDX_4AF2B3F357B8F0DE (auction_id), INDEX IDX_4AF2B3F3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bid_audit (id INT UNSIGNED AUTO_INCREMENT NOT NULL, type VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL, object_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL, discriminator VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, transaction_hash VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT NULL, diffs JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', blame_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user_fqdn VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL, blame_user_firewall VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL, ip VARCHAR(45) CHARACTER SET utf8mb4 DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX type_149db09a8a294b5e4596504ff019a19f_idx (type), INDEX object_id_149db09a8a294b5e4596504ff019a19f_idx (object_id), INDEX discriminator_149db09a8a294b5e4596504ff019a19f_idx (discriminator), INDEX transaction_hash_149db09a8a294b5e4596504ff019a19f_idx (transaction_hash), INDEX blame_id_149db09a8a294b5e4596504ff019a19f_idx (blame_id), INDEX created_at_149db09a8a294b5e4596504ff019a19f_idx (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE id_entity (entity_id VARCHAR(255) NOT NULL, id VARCHAR(255) NOT NULL, expiry DATETIME NOT NULL, PRIMARY KEY(entity_id, id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log (id INT AUTO_INCREMENT NOT NULL, channel VARCHAR(255) NOT NULL, level INT NOT NULL, message LONGTEXT NOT NULL, time DATETIME NOT NULL, details JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, `data` JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', UNIQUE INDEX UNIQ_9F74B8984E645A7E (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, idp_identifier BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', username VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, grade VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', uuid VARCHAR(36) NOT NULL, UNIQUE INDEX UNIQ_8D93D64966D2FA6C (idp_identifier), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649D17F50A6 (uuid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F357B8F0DE FOREIGN KEY (auction_id) REFERENCES auction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bid ADD CONSTRAINT FK_4AF2B3F3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F357B8F0DE');
        $this->addSql('ALTER TABLE bid DROP FOREIGN KEY FK_4AF2B3F3A76ED395');
        $this->addSql('DROP TABLE auction');
        $this->addSql('DROP TABLE auction_audit');
        $this->addSql('DROP TABLE bid');
        $this->addSql('DROP TABLE bid_audit');
        $this->addSql('DROP TABLE id_entity');
        $this->addSql('DROP TABLE log');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE user');
    }
}
