<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820000311 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apprenant_livrablepratielle (id INT AUTO_INCREMENT NOT NULL, livrable_partielle_id INT DEFAULT NULL, appranant_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, delai DATETIME NOT NULL, date_rendu DATETIME NOT NULL, INDEX IDX_537BA9CFCA18AA1A (livrable_partielle_id), INDEX IDX_537BA9CF41237397 (appranant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_aprennant (id INT AUTO_INCREMENT NOT NULL, statut VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brief_promo (id INT AUTO_INCREMENT NOT NULL, briefs_id INT DEFAULT NULL, promo_id INT DEFAULT NULL, brief_aprennant_id INT DEFAULT NULL, INDEX IDX_9AC7FC8BCA062D03 (briefs_id), INDEX IDX_9AC7FC8BD0C07AFF (promo_id), INDEX IDX_9AC7FC8BD5683411 (brief_aprennant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE briefs (id INT AUTO_INCREMENT NOT NULL, formateur_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, enonce VARCHAR(255) NOT NULL, context VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, date_echeance DATE NOT NULL, etats VARCHAR(255) NOT NULL, INDEX IDX_8575E1B8155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE briefs_niveau (briefs_id INT NOT NULL, niveau_id INT NOT NULL, INDEX IDX_97DBF4BECA062D03 (briefs_id), INDEX IDX_97DBF4BEB3E9C81 (niveau_id), PRIMARY KEY(briefs_id, niveau_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE briefs_tag (briefs_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_F19E8887CA062D03 (briefs_id), INDEX IDX_F19E8887BAD26311 (tag_id), PRIMARY KEY(briefs_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE briefs_groupe (briefs_id INT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_D8BD8BF4CA062D03 (briefs_id), INDEX IDX_D8BD8BF47A45358C (groupe_id), PRIMARY KEY(briefs_id, groupe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE briefs_apprenant (briefs_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_785E3F7CA062D03 (briefs_id), INDEX IDX_785E3F7C5697D6D (apprenant_id), PRIMARY KEY(briefs_id, apprenant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_general (id INT AUTO_INCREMENT NOT NULL, promo_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, date DATETIME NOT NULL, pj LONGBLOB NOT NULL, UNIQUE INDEX UNIQ_8227FA23D0C07AFF (promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, apprenant_livrablepratielle_id INT DEFAULT NULL, formateur_id INT DEFAULT NULL, contenu VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_67F068BC44A0BB0A (apprenant_livrablepratielle_id), INDEX IDX_67F068BC155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE competences_valide (id INT AUTO_INCREMENT NOT NULL, apprenant_id INT DEFAULT NULL, competence_id INT DEFAULT NULL, referentiel_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_81F6BD52C5697D6D (apprenant_id), INDEX IDX_81F6BD5215761DAB (competence_id), INDEX IDX_81F6BD52805DB139 (referentiel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, date_livraison DATETIME NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_briefs (livrable_attendu_id INT NOT NULL, briefs_id INT NOT NULL, INDEX IDX_C5C2D99A75180ACC (livrable_attendu_id), INDEX IDX_C5C2D99ACA062D03 (briefs_id), PRIMARY KEY(livrable_attendu_id, briefs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrable_attendu_livrablespartiels (livrable_attendu_id INT NOT NULL, livrablespartiels_id INT NOT NULL, INDEX IDX_34BEE39275180ACC (livrable_attendu_id), INDEX IDX_34BEE3926612BC8D (livrablespartiels_id), PRIMARY KEY(livrable_attendu_id, livrablespartiels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrables_aprennant (id INT AUTO_INCREMENT NOT NULL, livrable_attendu_id INT DEFAULT NULL, apprenant_id INT DEFAULT NULL, lien VARCHAR(255) NOT NULL, fichier LONGBLOB NOT NULL, INDEX IDX_94AC781675180ACC (livrable_attendu_id), INDEX IDX_94AC7816C5697D6D (apprenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livrables_partiels (id INT AUTO_INCREMENT NOT NULL, brief_promo_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, lien VARCHAR(255) NOT NULL, fichier LONGBLOB NOT NULL, created_at DATETIME NOT NULL, date_livraison DATE NOT NULL, INDEX IDX_AC3B3FEA3628C869 (brief_promo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressources (id INT AUTO_INCREMENT NOT NULL, briefs_id INT DEFAULT NULL, lien VARCHAR(255) NOT NULL, fichier LONGBLOB NOT NULL, INDEX IDX_6A2CD5C7CA062D03 (briefs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apprenant_livrablepratielle ADD CONSTRAINT FK_537BA9CFCA18AA1A FOREIGN KEY (livrable_partielle_id) REFERENCES livrables_partiels (id)');
        $this->addSql('ALTER TABLE apprenant_livrablepratielle ADD CONSTRAINT FK_537BA9CF41237397 FOREIGN KEY (appranant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE brief_promo ADD CONSTRAINT FK_9AC7FC8BCA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id)');
        $this->addSql('ALTER TABLE brief_promo ADD CONSTRAINT FK_9AC7FC8BD0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE brief_promo ADD CONSTRAINT FK_9AC7FC8BD5683411 FOREIGN KEY (brief_aprennant_id) REFERENCES brief_aprennant (id)');
        $this->addSql('ALTER TABLE briefs ADD CONSTRAINT FK_8575E1B8155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE briefs_niveau ADD CONSTRAINT FK_97DBF4BECA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_niveau ADD CONSTRAINT FK_97DBF4BEB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_tag ADD CONSTRAINT FK_F19E8887CA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_tag ADD CONSTRAINT FK_F19E8887BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_groupe ADD CONSTRAINT FK_D8BD8BF4CA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_groupe ADD CONSTRAINT FK_D8BD8BF47A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_apprenant ADD CONSTRAINT FK_785E3F7CA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_apprenant ADD CONSTRAINT FK_785E3F7C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_general ADD CONSTRAINT FK_8227FA23D0C07AFF FOREIGN KEY (promo_id) REFERENCES promos (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC44A0BB0A FOREIGN KEY (apprenant_livrablepratielle_id) REFERENCES apprenant_livrablepratielle (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC155D8F51 FOREIGN KEY (formateur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valide ADD CONSTRAINT FK_81F6BD52C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE competences_valide ADD CONSTRAINT FK_81F6BD5215761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE competences_valide ADD CONSTRAINT FK_81F6BD52805DB139 FOREIGN KEY (referentiel_id) REFERENCES referentiel (id)');
        $this->addSql('ALTER TABLE livrable_attendu_briefs ADD CONSTRAINT FK_C5C2D99A75180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_briefs ADD CONSTRAINT FK_C5C2D99ACA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels ADD CONSTRAINT FK_34BEE39275180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels ADD CONSTRAINT FK_34BEE3926612BC8D FOREIGN KEY (livrablespartiels_id) REFERENCES livrables_partiels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrables_aprennant ADD CONSTRAINT FK_94AC781675180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id)');
        $this->addSql('ALTER TABLE livrables_aprennant ADD CONSTRAINT FK_94AC7816C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE livrables_partiels ADD CONSTRAINT FK_AC3B3FEA3628C869 FOREIGN KEY (brief_promo_id) REFERENCES brief_promo (id)');
        $this->addSql('ALTER TABLE ressources ADD CONSTRAINT FK_6A2CD5C7CA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4B98C216C6E55B5 ON groupe (nom)');
        $this->addSql('ALTER TABLE user ADD chat_general_id INT DEFAULT NULL, ADD brief_aprennant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64917138157 FOREIGN KEY (chat_general_id) REFERENCES chat_general (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D5683411 FOREIGN KEY (brief_aprennant_id) REFERENCES brief_aprennant (id)');
        $this->addSql('CREATE INDEX IDX_8D93D64917138157 ON user (chat_general_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D5683411 ON user (brief_aprennant_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC44A0BB0A');
        $this->addSql('ALTER TABLE brief_promo DROP FOREIGN KEY FK_9AC7FC8BD5683411');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D5683411');
        $this->addSql('ALTER TABLE livrables_partiels DROP FOREIGN KEY FK_AC3B3FEA3628C869');
        $this->addSql('ALTER TABLE brief_promo DROP FOREIGN KEY FK_9AC7FC8BCA062D03');
        $this->addSql('ALTER TABLE briefs_niveau DROP FOREIGN KEY FK_97DBF4BECA062D03');
        $this->addSql('ALTER TABLE briefs_tag DROP FOREIGN KEY FK_F19E8887CA062D03');
        $this->addSql('ALTER TABLE briefs_groupe DROP FOREIGN KEY FK_D8BD8BF4CA062D03');
        $this->addSql('ALTER TABLE briefs_apprenant DROP FOREIGN KEY FK_785E3F7CA062D03');
        $this->addSql('ALTER TABLE livrable_attendu_briefs DROP FOREIGN KEY FK_C5C2D99ACA062D03');
        $this->addSql('ALTER TABLE ressources DROP FOREIGN KEY FK_6A2CD5C7CA062D03');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64917138157');
        $this->addSql('ALTER TABLE livrable_attendu_briefs DROP FOREIGN KEY FK_C5C2D99A75180ACC');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels DROP FOREIGN KEY FK_34BEE39275180ACC');
        $this->addSql('ALTER TABLE livrables_aprennant DROP FOREIGN KEY FK_94AC781675180ACC');
        $this->addSql('ALTER TABLE apprenant_livrablepratielle DROP FOREIGN KEY FK_537BA9CFCA18AA1A');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels DROP FOREIGN KEY FK_34BEE3926612BC8D');
        $this->addSql('DROP TABLE apprenant_livrablepratielle');
        $this->addSql('DROP TABLE brief_aprennant');
        $this->addSql('DROP TABLE brief_promo');
        $this->addSql('DROP TABLE briefs');
        $this->addSql('DROP TABLE briefs_niveau');
        $this->addSql('DROP TABLE briefs_tag');
        $this->addSql('DROP TABLE briefs_groupe');
        $this->addSql('DROP TABLE briefs_apprenant');
        $this->addSql('DROP TABLE chat_general');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE competences_valide');
        $this->addSql('DROP TABLE livrable_attendu');
        $this->addSql('DROP TABLE livrable_attendu_briefs');
        $this->addSql('DROP TABLE livrable_attendu_livrablespartiels');
        $this->addSql('DROP TABLE livrables_aprennant');
        $this->addSql('DROP TABLE livrables_partiels');
        $this->addSql('DROP TABLE ressources');
        $this->addSql('DROP INDEX UNIQ_4B98C216C6E55B5 ON groupe');
        $this->addSql('DROP INDEX IDX_8D93D64917138157 ON user');
        $this->addSql('DROP INDEX IDX_8D93D649D5683411 ON user');
        $this->addSql('ALTER TABLE user DROP chat_general_id, DROP brief_aprennant_id');
    }
}
