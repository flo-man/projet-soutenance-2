<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201014082655 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE type CHANGE description description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE produit ADD type_id INT NOT NULL, DROP type');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27C54C8C93 ON produit (type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27C54C8C93');
        $this->addSql('DROP INDEX IDX_29A5EC27C54C8C93 ON produit');
        $this->addSql('ALTER TABLE produit ADD type VARCHAR(100) NOT NULL COLLATE utf8_unicode_ci, DROP type_id');
        $this->addSql('ALTER TABLE type CHANGE description description VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
