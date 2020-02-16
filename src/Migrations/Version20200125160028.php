<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200125160028 extends AbstractMigration
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
        $this->addSql('ALTER TABLE player_character_info DROP INDEX UNIQ_10ACB226A76ED395, ADD INDEX IDX_10ACB226A76ED395 (user_id)');
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB226A76ED395');
        $this->addSql('ALTER TABLE player_character_info ADD player_property_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB226CA5FA79 FOREIGN KEY (player_property_id) REFERENCES player_properties (id)');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB226A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10ACB226CA5FA79 ON player_character_info (player_property_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL, CHANGE headword_id headword_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info DROP INDEX IDX_10ACB226A76ED395, ADD UNIQUE INDEX UNIQ_10ACB226A76ED395 (user_id)');
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB226CA5FA79');
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB226A76ED395');
        $this->addSql('DROP INDEX UNIQ_10ACB226CA5FA79 ON player_character_info');
        $this->addSql('ALTER TABLE player_character_info DROP player_property_id, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB226A76ED395 FOREIGN KEY (user_id) REFERENCES player_properties (id)');
    }
}
