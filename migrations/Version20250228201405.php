<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228201405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie ADD rapport_chirurgie_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donation_argent ADD hospital_id INT NOT NULL, DROP methode_paiment');
        $this->addSql('ALTER TABLE donation_argent ADD CONSTRAINT FK_7654DF7563DBB69 FOREIGN KEY (hospital_id) REFERENCES hospital (id)');
        $this->addSql('CREATE INDEX IDX_7654DF7563DBB69 ON donation_argent (hospital_id)');
        $this->addSql('ALTER TABLE suivi_medical ADD rapport_medical_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD is_approved TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie DROP rapport_chirurgie_name');
        $this->addSql('ALTER TABLE donation_argent DROP FOREIGN KEY FK_7654DF7563DBB69');
        $this->addSql('DROP INDEX IDX_7654DF7563DBB69 ON donation_argent');
        $this->addSql('ALTER TABLE donation_argent ADD methode_paiment VARCHAR(255) NOT NULL, DROP hospital_id');
        $this->addSql('ALTER TABLE suivi_medical DROP rapport_medical_name');
        $this->addSql('ALTER TABLE user DROP is_approved');
    }
}
