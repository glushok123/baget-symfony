<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231101135103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appeal (id INT AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, category_id INT DEFAULT NULL, status_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_96794351A76ED395 (user_id), INDEX IDX_9679435112469DE2 (category_id), INDEX IDX_967943516BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appeal_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appeal_message (id INT AUTO_INCREMENT NOT NULL, appeal_id INT DEFAULT NULL, sender_id INT UNSIGNED DEFAULT NULL, addressee_id INT UNSIGNED DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5CF1C307F9B2547F (appeal_id), INDEX IDX_5CF1C307F624B39D (sender_id), INDEX IDX_5CF1C3072261B4C3 (addressee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appeal_message_file (id INT AUTO_INCREMENT NOT NULL, appeal_message_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A56AD04C496395EE (appeal_message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE appeal_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE appeal ADD CONSTRAINT FK_96794351A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appeal ADD CONSTRAINT FK_9679435112469DE2 FOREIGN KEY (category_id) REFERENCES appeal_category (id)');
        $this->addSql('ALTER TABLE appeal ADD CONSTRAINT FK_967943516BF700BD FOREIGN KEY (status_id) REFERENCES appeal_status (id)');
        $this->addSql('ALTER TABLE appeal_message ADD CONSTRAINT FK_5CF1C307F9B2547F FOREIGN KEY (appeal_id) REFERENCES appeal (id)');
        $this->addSql('ALTER TABLE appeal_message ADD CONSTRAINT FK_5CF1C307F624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appeal_message ADD CONSTRAINT FK_5CF1C3072261B4C3 FOREIGN KEY (addressee_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE appeal_message_file ADD CONSTRAINT FK_A56AD04C496395EE FOREIGN KEY (appeal_message_id) REFERENCES appeal_message (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appeal DROP FOREIGN KEY FK_96794351A76ED395');
        $this->addSql('ALTER TABLE appeal DROP FOREIGN KEY FK_9679435112469DE2');
        $this->addSql('ALTER TABLE appeal DROP FOREIGN KEY FK_967943516BF700BD');
        $this->addSql('ALTER TABLE appeal_message DROP FOREIGN KEY FK_5CF1C307F9B2547F');
        $this->addSql('ALTER TABLE appeal_message DROP FOREIGN KEY FK_5CF1C307F624B39D');
        $this->addSql('ALTER TABLE appeal_message DROP FOREIGN KEY FK_5CF1C3072261B4C3');
        $this->addSql('ALTER TABLE appeal_message_file DROP FOREIGN KEY FK_A56AD04C496395EE');
        $this->addSql('DROP TABLE appeal');
        $this->addSql('DROP TABLE appeal_category');
        $this->addSql('DROP TABLE appeal_message');
        $this->addSql('DROP TABLE appeal_message_file');
        $this->addSql('DROP TABLE appeal_status');
    }
}
