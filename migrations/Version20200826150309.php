<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826150309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE briefs ADD referentiel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE briefs ADD CONSTRAINT FK_8575E1B8805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('CREATE INDEX IDX_8575E1B8805DB139 ON briefs (referentiel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE briefs DROP FOREIGN KEY FK_8575E1B8805DB139');
        $this->addSql('DROP INDEX IDX_8575E1B8805DB139 ON briefs');
        $this->addSql('ALTER TABLE briefs DROP referentiel_id');
    }
}
