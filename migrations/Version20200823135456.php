<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823135456 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_general DROP FOREIGN KEY FK_8227FA23D0C07AFF');
        $this->addSql('DROP INDEX UNIQ_8227FA23D0C07AFF ON chat_general');
        $this->addSql('ALTER TABLE chat_general DROP promo_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_general ADD promo_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chat_general ADD CONSTRAINT FK_8227FA23D0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8227FA23D0C07AFF ON chat_general (promo_id)');
    }
}
