<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200812180015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promos (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin_provisoire DATE NOT NULL, date_fin_reelle DATE DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, etat TINYINT(1) NOT NULL, avatar LONGBLOB DEFAULT NULL, INDEX IDX_31D1F705805DB139 (referentiel_id), INDEX IDX_31D1F705642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promos_formateur (promos_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_70F76221CAA392D2 (promos_id), INDEX IDX_70F76221155D8F51 (formateur_id), PRIMARY KEY(promos_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel_group_competences (referentiel_id INT NOT NULL, group_competences_id INT NOT NULL, INDEX IDX_3E994CF7805DB139 (referentiel_id), INDEX IDX_3E994CF73EED782A (group_competences_id), PRIMARY KEY(referentiel_id, group_competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promos_formateur ADD CONSTRAINT FK_70F76221CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promos_formateur ADD CONSTRAINT FK_70F76221155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_group_competences ADD CONSTRAINT FK_3E994CF7805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_group_competences ADD CONSTRAINT FK_3E994CF73EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE promos_formateur DROP FOREIGN KEY FK_70F76221CAA392D2');
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705805DB139');
        $this->addSql('ALTER TABLE referentiel_group_competences DROP FOREIGN KEY FK_3E994CF7805DB139');
        $this->addSql('DROP TABLE promos');
        $this->addSql('DROP TABLE promos_formateur');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_group_competences');
    }
}
