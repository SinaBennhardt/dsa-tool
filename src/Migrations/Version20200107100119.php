<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200107100119 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_character_info (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, character_name VARCHAR(255) NOT NULL, race VARCHAR(255) NOT NULL, culture VARCHAR(255) NOT NULL, profession VARCHAR(255) NOT NULL, social_status VARCHAR(255) NOT NULL, advantages VARCHAR(255) NOT NULL, disadvantages VARCHAR(255) NOT NULL, unique_skills VARCHAR(255) NOT NULL, lp VARCHAR(255) NOT NULL, as_p VARCHAR(255) NOT NULL, kp VARCHAR(255) NOT NULL, mr VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_10ACB22699E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player_properties (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, courage VARCHAR(255) NOT NULL, intelligence VARCHAR(255) NOT NULL, intuition VARCHAR(255) NOT NULL, charisma VARCHAR(255) NOT NULL, finger_dex VARCHAR(255) NOT NULL, general_dex VARCHAR(255) NOT NULL, constitution VARCHAR(255) NOT NULL, strength VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB22699E6F5DF FOREIGN KEY (player_id) REFERENCES player_properties (id)');
        $this->addSql('ALTER TABLE content CHANGE headword_id headword_id INT DEFAULT NULL, CHANGE author_id author_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB22699E6F5DF');
        $this->addSql('DROP TABLE player_character_info');
        $this->addSql('DROP TABLE player_properties');
        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL, CHANGE headword_id headword_id INT DEFAULT NULL');
    }
}
