<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200504102112 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adventure CHANGE author_id author_id INT DEFAULT NULL, CHANGE campaign_id campaign_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD campaign_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content ADD CONSTRAINT FK_FEC530A9F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('CREATE INDEX IDX_FEC530A9F639F774 ON content (campaign_id)');
        $this->addSql('ALTER TABLE headword ADD campaign_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE headword ADD CONSTRAINT FK_F20DA05AF639F774 FOREIGN KEY (campaign_id) REFERENCES content (id)');
        $this->addSql('CREATE INDEX IDX_F20DA05AF639F774 ON headword (campaign_id)');
        $this->addSql('ALTER TABLE player_character_info ADD campaign_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB226F639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('CREATE INDEX IDX_10ACB226F639F774 ON player_character_info (campaign_id)');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE adventure CHANGE author_id author_id INT DEFAULT NULL, CHANGE campaign_id campaign_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE campaign CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE content DROP FOREIGN KEY FK_FEC530A9F639F774');
        $this->addSql('DROP INDEX IDX_FEC530A9F639F774 ON content');
        $this->addSql('ALTER TABLE content DROP campaign_id, CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE headword DROP FOREIGN KEY FK_F20DA05AF639F774');
        $this->addSql('DROP INDEX IDX_F20DA05AF639F774 ON headword');
        $this->addSql('ALTER TABLE headword DROP campaign_id');
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB226F639F774');
        $this->addSql('DROP INDEX IDX_10ACB226F639F774 ON player_character_info');
        $this->addSql('ALTER TABLE player_character_info DROP campaign_id, CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }
}
