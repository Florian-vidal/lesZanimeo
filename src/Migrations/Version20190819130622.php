<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190819130622 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE responses ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_315F9F94BCB134CE FOREIGN KEY (questions_id) REFERENCES questions (id)');
        $this->addSql('ALTER TABLE responses ADD CONSTRAINT FK_315F9F9412469DE2 FOREIGN KEY (category_id) REFERENCES questions (id)');
        $this->addSql('CREATE INDEX IDX_315F9F94BCB134CE ON responses (questions_id)');
        $this->addSql('CREATE INDEX IDX_315F9F9412469DE2 ON responses (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_315F9F94BCB134CE');
        $this->addSql('ALTER TABLE responses DROP FOREIGN KEY FK_315F9F9412469DE2');
        $this->addSql('DROP INDEX IDX_315F9F94BCB134CE ON responses');
        $this->addSql('DROP INDEX IDX_315F9F9412469DE2 ON responses');
        $this->addSql('ALTER TABLE responses DROP category_id');
    }
}
