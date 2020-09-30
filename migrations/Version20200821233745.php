<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200821233745 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64917138157');
        $this->addSql('DROP INDEX IDX_8D93D64917138157 ON user');
        $this->addSql('ALTER TABLE user DROP chat_general_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE livrable_attendu_livrables_partiels');
        $this->addSql('ALTER TABLE user ADD chat_general_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64917138157 FOREIGN KEY (chat_general_id) REFERENCES chat_general (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D64917138157 ON user (chat_general_id)');
    }
}
