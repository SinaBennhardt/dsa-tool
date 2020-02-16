<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191223131730 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content ADD headword_id INT DEFAULT NULL, DROP headword');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9D7DCAEF0 FOREIGN KEY (headword_id) REFERENCES headword (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9D7DCAEF0 ON content (headword_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9D7DCAEF0');
        $this->addSql('DROP INDEX IDX_FEC530A9D7DCAEF0 ON content');
        $this->addSql('ALTER TABLE content ADD headword VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, DROP headword_id');
    }
}
