<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191230152301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, quiz_id INT NOT NULL, right_answer INT NOT NULL, total_answer INT NOT NULL, INDEX IDX_3299375199E6F5DF (player_id), INDEX IDX_32993751853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_3299375199E6F5DF FOREIGN KEY (player_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD amount INT NOT NULL, CHANGE resultDisplay resultDisplay enum(\'ONLYSCORE\', \'HIDEANSWER\', \'SHOWANSWER\')');
        $this->addSql('ALTER TABLE answer ADD answered_right INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE score');
        $this->addSql('ALTER TABLE answer DROP answered_right');
        $this->addSql('ALTER TABLE quiz DROP amount, CHANGE resultDisplay resultDisplay VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
