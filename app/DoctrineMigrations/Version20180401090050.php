<?php declare(strict_types = 1);

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180401090050 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, game_state_id INT DEFAULT NULL, game_winner_id INT DEFAULT NULL, winning_points INT NOT NULL, created_at DATETIME NOT NULL, finished_at DATETIME DEFAULT NULL, INDEX IDX_232B318CAE9CC3E7 (game_state_id), INDEX IDX_232B318C8F5A15BA (game_winner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_teams (game_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_A4F15BD7E48FD905 (game_id), INDEX IDX_A4F15BD7FE54D947 (group_id), PRIMARY KEY(game_id, group_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, teamname VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team_users (team_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D385ECA9296CD8AE (team_id), INDEX IDX_D385ECA9A76ED395 (user_id), PRIMARY KEY(team_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE points (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, team_id INT DEFAULT NULL, points INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_27BA8E29E48FD905 (game_id), INDEX IDX_27BA8E29296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_state (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CAE9CC3E7 FOREIGN KEY (game_state_id) REFERENCES game_state (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C8F5A15BA FOREIGN KEY (game_winner_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE game_teams ADD CONSTRAINT FK_A4F15BD7E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_teams ADD CONSTRAINT FK_A4F15BD7FE54D947 FOREIGN KEY (group_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team_users ADD CONSTRAINT FK_D385ECA9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team_users ADD CONSTRAINT FK_D385ECA9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE points ADD CONSTRAINT FK_27BA8E29E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE points ADD CONSTRAINT FK_27BA8E29296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE game_teams DROP FOREIGN KEY FK_A4F15BD7E48FD905');
        $this->addSql('ALTER TABLE points DROP FOREIGN KEY FK_27BA8E29E48FD905');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C8F5A15BA');
        $this->addSql('ALTER TABLE game_teams DROP FOREIGN KEY FK_A4F15BD7FE54D947');
        $this->addSql('ALTER TABLE team_users DROP FOREIGN KEY FK_D385ECA9296CD8AE');
        $this->addSql('ALTER TABLE points DROP FOREIGN KEY FK_27BA8E29296CD8AE');
        $this->addSql('ALTER TABLE team_users DROP FOREIGN KEY FK_D385ECA9A76ED395');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CAE9CC3E7');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_teams');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE team_users');
        $this->addSql('DROP TABLE points');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE game_state');
    }
}
