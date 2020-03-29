<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200324113109 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE adventure (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, campaign_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, blurb VARCHAR(255) NOT NULL, INDEX IDX_9E858E0FF675F31B (author_id), INDEX IDX_9E858E0FF639F774 (campaign_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE campaign (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, blurb VARCHAR(255) NOT NULL, INDEX IDX_1F1512DDF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content_adventure (content_id INT NOT NULL, adventure_id INT NOT NULL, INDEX IDX_AE62C60C84A0A3ED (content_id), INDEX IDX_AE62C60C55CF40F9 (adventure_id), PRIMARY KEY(content_id, adventure_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0FF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE adventure ADD CONSTRAINT FK_9E858E0FF639F774 FOREIGN KEY (campaign_id) REFERENCES campaign (id)');
        $this->addSql('ALTER TABLE campaign ADD CONSTRAINT FK_1F1512DDF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE content_adventure ADD CONSTRAINT FK_AE62C60C84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content_adventure ADD CONSTRAINT FK_AE62C60C55CF40F9 FOREIGN KEY (adventure_id) REFERENCES adventure (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content_adventure DROP FOREIGN KEY FK_AE62C60C55CF40F9');
        $this->addSql('ALTER TABLE adventure DROP FOREIGN KEY FK_9E858E0FF639F774');
        $this->addSql('DROP TABLE adventure');
        $this->addSql('DROP TABLE campaign');
        $this->addSql('DROP TABLE content_adventure');
        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info CHANGE user_id user_id INT DEFAULT NULL, CHANGE player_property_id player_property_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_properties CHANGE user_id user_id INT DEFAULT NULL');
    }
}
