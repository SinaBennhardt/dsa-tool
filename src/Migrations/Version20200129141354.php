<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129141354 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content CHANGE headword_id headword_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties ADD CONSTRAINT FK_D866B5FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D866B5FAA76ED395 ON player_properties (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL, CHANGE headword_id headword_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties DROP FOREIGN KEY FK_D866B5FAA76ED395');
        $this->addSql('DROP INDEX IDX_D866B5FAA76ED395 ON player_properties');
        $this->addSql('ALTER TABLE player_properties DROP user_id');
    }
}
