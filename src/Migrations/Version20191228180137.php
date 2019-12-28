<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191228180137 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE quiz CHANGE id id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE question ADD response1 VARCHAR(255) NOT NULL, ADD response2 VARCHAR(255) NOT NULL, ADD response3 VARCHAR(255) DEFAULT NULL, ADD response4 VARCHAR(255) DEFAULT NULL, ADD good_response INT NOT NULL, CHANGE quiz_id quiz_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question DROP response1, DROP response2, DROP response3, DROP response4, DROP good_response, CHANGE quiz_id quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
