<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813141817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE group_competences_competence');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_competences_competence (group_competences_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_3DFBCE1B15761DAB (competence_id), INDEX IDX_3DFBCE1B3EED782A (group_competences_id), PRIMARY KEY(group_competences_id, competence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B3EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
