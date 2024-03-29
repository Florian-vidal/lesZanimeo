<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190814114452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE utilisateurs CHANGE animal_prefere animal_prefere VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE familles CHANGE name name VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE quiz CHANGE type type VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE familles CHANGE name name VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE quiz CHANGE type type VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE utilisateurs CHANGE animal_prefere animal_prefere VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
