<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821205826 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendu_livrables_partiels');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livrable_attendu_livrables_partiels (livrable_attendu_id INT NOT NULL, livrables_partiels_id INT NOT NULL, INDEX IDX_782043932BE153F2 (livrables_partiels_id), INDEX IDX_7820439375180ACC (livrable_attendu_id), PRIMARY KEY(livrable_attendu_id, livrables_partiels_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE livrable_attendu_livrables_partiels ADD CONSTRAINT FK_34BEE3926612BC8D FOREIGN KEY (livrables_partiels_id) REFERENCES livrables_partiels (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE livrable_attendu_livrables_partiels ADD CONSTRAINT FK_34BEE39275180ACC FOREIGN KEY (livrable_attendu_id) REFERENCES livrable_attendu (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
