<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102084234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_address ADD country_id INT DEFAULT NULL, ADD region VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD street VARCHAR(255) NOT NULL, ADD house VARCHAR(255) NOT NULL, ADD apartment VARCHAR(255) DEFAULT NULL, DROP address');
        $this->addSql('ALTER TABLE organization_address ADD CONSTRAINT FK_C09CE7C2F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('CREATE INDEX IDX_C09CE7C2F92F3E70 ON organization_address (country_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_address DROP FOREIGN KEY FK_C09CE7C2F92F3E70');
        $this->addSql('DROP INDEX IDX_C09CE7C2F92F3E70 ON organization_address');
        $this->addSql('ALTER TABLE organization_address ADD address LONGTEXT NOT NULL, DROP country_id, DROP region, DROP city, DROP street, DROP house, DROP apartment');
    }
}
