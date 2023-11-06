<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231018091158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD price_rub_value NUMERIC(10, 0) DEFAULT NULL, ADD price_usd_value NUMERIC(10, 0) DEFAULT NULL, ADD price_rub_transit_value NUMERIC(10, 0) DEFAULT NULL, ADD price_usd_transit_value NUMERIC(10, 0) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP price_rub_value, DROP price_usd_value, DROP price_rub_transit_value, DROP price_usd_transit_value');
    }
}
