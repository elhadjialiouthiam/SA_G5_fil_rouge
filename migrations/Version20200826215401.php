<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200826215401 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livrable_attendu DROP FOREIGN KEY FK_BA983CC9A919ADED');
        $this->addSql('DROP TABLE statut_livrable');
        $this->addSql('DROP INDEX IDX_BA983CC9A919ADED ON livrable_attendu');
        $this->addSql('ALTER TABLE livrable_attendu DROP statut_livrable_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE statut_livrable (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livrable_attendu ADD statut_livrable_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livrable_attendu ADD CONSTRAINT FK_BA983CC9A919ADED FOREIGN KEY (statut_livrable_id) REFERENCES statut_livrable (id)');
        $this->addSql('CREATE INDEX IDX_BA983CC9A919ADED ON livrable_attendu (statut_livrable_id)');
    }
}
