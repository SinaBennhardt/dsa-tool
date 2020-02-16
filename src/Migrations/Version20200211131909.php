<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211131909 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE content_headword (content_id INT NOT NULL, headword_id INT NOT NULL, INDEX IDX_A0BA4AB584A0A3ED (content_id), INDEX IDX_A0BA4AB5D7DCAEF0 (headword_id), PRIMARY KEY(content_id, headword_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE content_headword ADD CONSTRAINT FK_A0BA4AB584A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_headword ADD CONSTRAINT FK_A0BA4AB5D7DCAEF0 FOREIGN KEY (headword_id) REFERENCES headword (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9D7DCAEF0');
        $this->addSql('DROP INDEX IDX_FEC530A9D7DCAEF0 ON content');
        $this->addSql('ALTER TABLE content DROP headword_id, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE content_headword');
        $this->addSql('ALTER TABLE content ADD headword_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9D7DCAEF0 FOREIGN KEY (headword_id) REFERENCES headword (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9D7DCAEF0 ON content (headword_id)');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }
}
