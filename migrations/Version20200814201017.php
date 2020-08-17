<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200814201017 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif LONGTEXT NOT NULL, etat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_competences (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_competences_competence (group_competences_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_3DFBCE1B3EED782A (group_competences_id), INDEX IDX_3DFBCE1B15761DAB (competence_id), PRIMARY KEY(group_competences_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, promos_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, datecreation DATETIME DEFAULT NULL, statut VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, etat VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_4B98C216C6E55B5 (nom), INDEX IDX_4B98C21CAA392D2 (promos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_apprenant (groupe_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_947F95197A45358C (groupe_id), INDEX IDX_947F9519C5697D6D (apprenant_id), PRIMARY KEY(groupe_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_formateur (groupe_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_BDE2AD787A45358C (groupe_id), INDEX IDX_BDE2AD78155D8F51 (formateur_id), PRIMARY KEY(groupe_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe_tag_tag (groupe_tag_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_C430CACFD1EC9F2B (groupe_tag_id), INDEX IDX_C430CACFBAD26311 (tag_id), PRIMARY KEY(groupe_tag_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, competence_id INT DEFAULT NULL, critere_evaluation LONGTEXT NOT NULL, groupe_action LONGTEXT NOT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_4BDFF36B15761DAB (competence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, etat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil_de_sortie (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, etat VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promos (id INT AUTO_INCREMENT NOT NULL, referentiel_id INT DEFAULT NULL, admin_id INT DEFAULT NULL, langue VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date_debut DATE NOT NULL, date_fin_provisoire DATE NOT NULL, date_fin_reelle DATE DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, etat TINYINT(1) NOT NULL, avatar LONGBLOB DEFAULT NULL, INDEX IDX_31D1F705805DB139 (referentiel_id), INDEX IDX_31D1F705642B8210 (admin_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promos_formateur (promos_id INT NOT NULL, formateur_id INT NOT NULL, INDEX IDX_70F76221CAA392D2 (promos_id), INDEX IDX_70F76221155D8F51 (formateur_id), PRIMARY KEY(promos_id, formateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, presentation VARCHAR(255) NOT NULL, programme VARCHAR(255) NOT NULL, critere_admission VARCHAR(255) NOT NULL, critere_evaluation VARCHAR(255) NOT NULL, is_deleted TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referentiel_group_competences (referentiel_id INT NOT NULL, group_competences_id INT NOT NULL, INDEX IDX_3E994CF7805DB139 (referentiel_id), INDEX IDX_3E994CF73EED782A (group_competences_id), PRIMARY KEY(referentiel_id, group_competences_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, descriptif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT NOT NULL, profil_de_sortie_id INT DEFAULT NULL, promos_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, avatar LONGBLOB DEFAULT NULL, etat VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649275ED078 (profil_id), INDEX IDX_8D93D64965E0C4D3 (profil_de_sortie_id), INDEX IDX_8D93D649CAA392D2 (promos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B3EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_competences_competence ADD CONSTRAINT FK_3DFBCE1B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F95197A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_apprenant ADD CONSTRAINT FK_947F9519C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD787A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_formateur ADD CONSTRAINT FK_BDE2AD78155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFD1EC9F2B FOREIGN KEY (groupe_tag_id) REFERENCES groupe_tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE groupe_tag_tag ADD CONSTRAINT FK_C430CACFBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE niveau ADD CONSTRAINT FK_4BDFF36B15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE promos ADD CONSTRAINT FK_31D1F705642B8210 FOREIGN KEY (admin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE promos_formateur ADD CONSTRAINT FK_70F76221CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promos_formateur ADD CONSTRAINT FK_70F76221155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_group_competences ADD CONSTRAINT FK_3E994CF7805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE referentiel_group_competences ADD CONSTRAINT FK_3E994CF73EED782A FOREIGN KEY (group_competences_id) REFERENCES group_competences (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64965E0C4D3 FOREIGN KEY (profil_de_sortie_id) REFERENCES profil_de_sortie (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CAA392D2 FOREIGN KEY (promos_id) REFERENCES promos (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_competences_competence DROP FOREIGN KEY FK_3DFBCE1B15761DAB');
        $this->addSql('ALTER TABLE niveau DROP FOREIGN KEY FK_4BDFF36B15761DAB');
        $this->addSql('ALTER TABLE group_competences_competence DROP FOREIGN KEY FK_3DFBCE1B3EED782A');
        $this->addSql('ALTER TABLE referentiel_group_competences DROP FOREIGN KEY FK_3E994CF73EED782A');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F95197A45358C');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD787A45358C');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFD1EC9F2B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64965E0C4D3');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21CAA392D2');
        $this->addSql('ALTER TABLE promos_formateur DROP FOREIGN KEY FK_70F76221CAA392D2');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CAA392D2');
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705805DB139');
        $this->addSql('ALTER TABLE referentiel_group_competences DROP FOREIGN KEY FK_3E994CF7805DB139');
        $this->addSql('ALTER TABLE groupe_tag_tag DROP FOREIGN KEY FK_C430CACFBAD26311');
        $this->addSql('ALTER TABLE groupe_apprenant DROP FOREIGN KEY FK_947F9519C5697D6D');
        $this->addSql('ALTER TABLE groupe_formateur DROP FOREIGN KEY FK_BDE2AD78155D8F51');
        $this->addSql('ALTER TABLE promos DROP FOREIGN KEY FK_31D1F705642B8210');
        $this->addSql('ALTER TABLE promos_formateur DROP FOREIGN KEY FK_70F76221155D8F51');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE group_competences');
        $this->addSql('DROP TABLE group_competences_competence');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE groupe_apprenant');
        $this->addSql('DROP TABLE groupe_formateur');
        $this->addSql('DROP TABLE groupe_tag');
        $this->addSql('DROP TABLE groupe_tag_tag');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE profil_de_sortie');
        $this->addSql('DROP TABLE promos');
        $this->addSql('DROP TABLE promos_formateur');
        $this->addSql('DROP TABLE referentiel');
        $this->addSql('DROP TABLE referentiel_group_competences');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
