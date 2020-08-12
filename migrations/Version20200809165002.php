<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200809165002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_competences_competence (group_competences_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_3DFBCE1B3EED782A (group_competences_id), INDEX IDX_3DFBCE1B15761DAB (competence_id), PRIMARY KEY(group_competences_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B3EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE group_competences_competence');
    }
}
