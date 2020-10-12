<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201012130038 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D64986CC499D (pseudo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE membre MODIFY id_membre INT NOT NULL');
        $this->addSql('ALTER TABLE membre DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE membre ADD id INT AUTO_INCREMENT NOT NULL, CHANGE id_membre id_membre INT NOT NULL, CHANGE pseudo pseudo VARCHAR(20) NOT NULL, CHANGE mdp mdp VARCHAR(255) NOT NULL, CHANGE nom nom VARCHAR(20) NOT NULL, CHANGE civilite civilite VARCHAR(1) NOT NULL, CHANGE statut statut JSON NOT NULL');
        $this->addSql('ALTER TABLE membre ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE membre MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE membre DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE membre DROP id, CHANGE id_membre id_membre INT AUTO_INCREMENT NOT NULL, CHANGE pseudo pseudo VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci, CHANGE mdp mdp VARCHAR(250) NOT NULL COLLATE utf8mb4_general_ci, CHANGE nom nom VARCHAR(50) NOT NULL COLLATE utf8mb4_general_ci, CHANGE civilite civilite VARCHAR(255) NOT NULL COLLATE utf8mb4_general_ci, CHANGE statut statut INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE membre ADD PRIMARY KEY (id_membre)');
    }
}
