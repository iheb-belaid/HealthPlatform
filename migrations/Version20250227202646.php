<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250227202646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie ADD rapport_chirurgie_name VARCHAR(255) DEFAULT NULL, CHANGE date_chirurgie date_chirurgie DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE suivi_medical ADD rapport_medical_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie DROP rapport_chirurgie_name, CHANGE date_chirurgie date_chirurgie DATETIME NOT NULL');
        $this->addSql('ALTER TABLE suivi_medical DROP rapport_medical_name');
    }
}
