<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925124524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491BEBE01B');
        $this->addSql('DROP INDEX IDX_8D93D6491BEBE01B ON user');
        $this->addSql('ALTER TABLE user CHANGE type_price_id price_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AE6A44CF FOREIGN KEY (price_type_id) REFERENCES user_price_type (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649AE6A44CF ON user (price_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AE6A44CF');
        $this->addSql('DROP INDEX IDX_8D93D649AE6A44CF ON `user`');
        $this->addSql('ALTER TABLE `user` CHANGE price_type_id type_price_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491BEBE01B FOREIGN KEY (type_price_id) REFERENCES user_price_type (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491BEBE01B ON `user` (type_price_id)');
    }
}
