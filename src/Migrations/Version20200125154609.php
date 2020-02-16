<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200125154609 extends AbstractMigration
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
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB22699E6F5DF');
        $this->addSql('DROP INDEX UNIQ_10ACB22699E6F5DF ON player_character_info');
        $this->addSql('ALTER TABLE player_character_info ADD user_id INT DEFAULT NULL, DROP player_id');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB226A76ED395 FOREIGN KEY (user_id) REFERENCES player_properties (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10ACB226A76ED395 ON player_character_info (user_id)');
        $this->addSql('ALTER TABLE player_properties CHANGE courage courage INT NOT NULL, CHANGE intelligence intelligence INT NOT NULL, CHANGE intuition intuition INT NOT NULL, CHANGE charisma charisma INT NOT NULL, CHANGE finger_dex finger_dex INT NOT NULL, CHANGE general_dex general_dex INT NOT NULL, CHANGE strength strength INT NOT NULL, CHANGE lp lp INT NOT NULL, CHANGE as_p as_p INT NOT NULL, CHANGE kp kp INT NOT NULL, CHANGE mr mr INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE content CHANGE author_id author_id INT DEFAULT NULL, CHANGE headword_id headword_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player_character_info DROP FOREIGN KEY FK_10ACB226A76ED395');
        $this->addSql('DROP INDEX UNIQ_10ACB226A76ED395 ON player_character_info');
        $this->addSql('ALTER TABLE player_character_info ADD player_id INT DEFAULT NULL, DROP user_id');
        $this->addSql('ALTER TABLE player_character_info ADD CONSTRAINT FK_10ACB22699E6F5DF FOREIGN KEY (player_id) REFERENCES player_properties (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10ACB22699E6F5DF ON player_character_info (player_id)');
        $this->addSql('ALTER TABLE player_properties CHANGE courage courage VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE intelligence intelligence VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE intuition intuition VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE charisma charisma VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE finger_dex finger_dex VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE general_dex general_dex VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE strength strength VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE lp lp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE as_p as_p VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE kp kp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE mr mr VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
