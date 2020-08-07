<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200805115957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_competences_competence (group_competences_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_3DFBCE1B3EED782A (group_competences_id), INDEX IDX_3DFBCE1B15761DAB (competence_id), PRIMARY KEY(group_competences_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, competence_id INT NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, group_actions LONGTEXT NOT NULL, INDEX IDX_4BDFF36B15761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B3EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_competences_competence DROP FOREIGN KEY FK_3DFBCE1B15761DAB');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36B15761DAB');
        $this->addSql('ALTER TABLE group_competences_competence DROP FOREIGN KEY FK_3DFBCE1B3EED782A');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE group_competences');
        $this->addSql('DROP TABLE group_competences_competence');
        $this->addSql('DROP TABLE niveau');
    }
}
