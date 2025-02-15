<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211205146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chirurgie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, nom_operation VARCHAR(255) NOT NULL, date_chirurgie DATETIME NOT NULL, nom_etablissement VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, INDEX IDX_EC5628096B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_medical (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, docteur_id INT NOT NULL, type_suivi VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, frequence VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_F21CF3FF6B899279 (patient_id), INDEX IDX_F21CF3FFCF22540A (docteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chirurgie ADD CONSTRAINT FK_EC5628096B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FF6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FFCF22540A FOREIGN KEY (docteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie DROP FOREIGN KEY FK_EC5628096B899279');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FF6B899279');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FFCF22540A');
        $this->addSql('DROP TABLE chirurgie');
        $this->addSql('DROP TABLE suivi_medical');
    }
}
