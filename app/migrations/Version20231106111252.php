<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231106111252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_C74F2195C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, module VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, name_ru VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_role_group (role_id INT UNSIGNED NOT NULL, role_group_id INT UNSIGNED NOT NULL, INDEX IDX_1528C6C6D60322AC (role_id), INDEX IDX_1528C6C6D4873F76 (role_group_id), PRIMARY KEY(role_id, role_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_group (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, deleted TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role_group_user (role_group_id INT UNSIGNED NOT NULL, user_id INT UNSIGNED NOT NULL, INDEX IDX_2BFDE715D4873F76 (role_group_id), INDEX IDX_2BFDE715A76ED395 (user_id), PRIMARY KEY(role_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT UNSIGNED AUTO_INCREMENT NOT NULL, price_type_id INT DEFAULT NULL, manager_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) DEFAULT NULL, middle_name VARCHAR(255) DEFAULT NULL, birthday DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', sex TINYINT(1) DEFAULT NULL, email VARCHAR(180) NOT NULL, approve_email_code VARCHAR(255) DEFAULT NULL, approve_email_code_received DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', confirm_email TINYINT(1) DEFAULT 0 NOT NULL, phone VARCHAR(35) NOT NULL COMMENT \'(DC2Type:phone_number)\', password VARCHAR(255) NOT NULL, exchange_rate TINYINT(1) NOT NULL, deleted TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', invited_by INT DEFAULT NULL, password_recovery_hash_value VARCHAR(255) DEFAULT NULL, password_recovery_hash_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), INDEX IDX_8D93D649AE6A44CF (price_type_id), INDEX IDX_8D93D649783E3463 (manager_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_manager (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(35) DEFAULT NULL COMMENT \'(DC2Type:phone_number)\', deleted TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_A2293BB3444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_price_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE role_role_group ADD CONSTRAINT FK_1528C6C6D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_role_group ADD CONSTRAINT FK_1528C6C6D4873F76 FOREIGN KEY (role_group_id) REFERENCES role_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_group_user ADD CONSTRAINT FK_2BFDE715D4873F76 FOREIGN KEY (role_group_id) REFERENCES role_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_group_user ADD CONSTRAINT FK_2BFDE715A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649AE6A44CF FOREIGN KEY (price_type_id) REFERENCES user_price_type (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649783E3463 FOREIGN KEY (manager_id) REFERENCES user_manager (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_role_group DROP FOREIGN KEY FK_1528C6C6D60322AC');
        $this->addSql('ALTER TABLE role_role_group DROP FOREIGN KEY FK_1528C6C6D4873F76');
        $this->addSql('ALTER TABLE role_group_user DROP FOREIGN KEY FK_2BFDE715D4873F76');
        $this->addSql('ALTER TABLE role_group_user DROP FOREIGN KEY FK_2BFDE715A76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AE6A44CF');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649783E3463');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE role_role_group');
        $this->addSql('DROP TABLE role_group');
        $this->addSql('DROP TABLE role_group_user');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_manager');
        $this->addSql('DROP TABLE user_price_type');
    }
}
