<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821151725 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendu_livrables_partiels (livrable_attendu_id INT NOT NULL, livrables_partiels_id INT NOT NULL, INDEX IDX_7820439375180ACC (livrable_attendu_id), INDEX IDX_782043932BE153F2 (livrables_partiels_id), PRIMARY KEY(livrable_attendu_id, livrables_partiels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livrable_attendu_livrables_partiels ADD CONSTRAINT FK_7820439375180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrables_partiels ADD CONSTRAINT FK_782043932BE153F2 FOREIGN KEY (livrables_partiels_id) REFERENCES livrables_partiels (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE briefs_apprenant');
        $this->addSql('DROP TABLE livrable_attendu_livrablespartiels');
        $this->addSql('ALTER TABLE ressources CHANGE fichier fichier LONGBLOB DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE briefs_apprenant (briefs_id INT NOT NULL, apprenant_id INT NOT NULL, INDEX IDX_785E3F7C5697D6D (apprenant_id), INDEX IDX_785E3F7CA062D03 (briefs_id), PRIMARY KEY(briefs_id, apprenant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livrable_attendu_livrablespartiels (livrable_attendu_id INT NOT NULL, livrablespartiels_id INT NOT NULL, INDEX IDX_34BEE3926612BC8D (livrablespartiels_id), INDEX IDX_34BEE39275180ACC (livrable_attendu_id), PRIMARY KEY(livrable_attendu_id, livrablespartiels_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE briefs_apprenant ADD CONSTRAINT FK_785E3F7C5697D6D FOREIGN KEY (apprenant_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE briefs_apprenant ADD CONSTRAINT FK_785E3F7CA062D03 FOREIGN KEY (briefs_id) REFERENCES briefs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels ADD CONSTRAINT FK_34BEE3926612BC8D FOREIGN KEY (livrablespartiels_id) REFERENCES livrables_partiels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrablespartiels ADD CONSTRAINT FK_34BEE39275180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE livrable_attendu_livrables_partiels');
        $this->addSql('ALTER TABLE ressources CHANGE fichier fichier LONGBLOB NOT NULL');
    }
}
