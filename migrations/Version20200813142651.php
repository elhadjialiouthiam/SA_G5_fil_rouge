<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200813142651 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE competence_group_competences');
        $this->addSql('ALTER TABLE competence ADD etat VARCHAR(255) DEFAULT NULL, DROP actif');
        $this->addSql('ALTER TABLE group_competences ADD etat VARCHAR(255) DEFAULT NULL, DROP actif, CHANGE descriptif descriptif VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence_group_competences (competence_id INT NOT NULL, group_competences_id INT NOT NULL, INDEX IDX_58D395DF15761DAB (competence_id), INDEX IDX_58D395DF3EED782A (group_competences_id), PRIMARY KEY(competence_id, group_competences_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE competence_group_competences ADD CONSTRAINT FK_58D395DF15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competence_group_competences ADD CONSTRAINT FK_58D395DF3EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE competence ADD actif TINYINT(1) NOT NULL, DROP etat');
        $this->addSql('ALTER TABLE group_competences ADD actif TINYINT(1) NOT NULL, DROP etat, CHANGE descriptif descriptif LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
