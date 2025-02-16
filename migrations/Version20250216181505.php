<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216181505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chirurgie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, nom_operation VARCHAR(255) NOT NULL, date_chirurgie DATETIME NOT NULL, nom_etablissement VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, nom_docteur VARCHAR(255) NOT NULL, INDEX IDX_EC5628096B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation_argent (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, methode_paiment VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE donation_sang (id INT AUTO_INCREMENT NOT NULL, hospital_id INT DEFAULT NULL, type_sang VARCHAR(255) NOT NULL, date_donation DATE NOT NULL, email_user VARCHAR(255) NOT NULL, cin INT NOT NULL, INDEX IDX_28E0E88163DBB69 (hospital_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hospital (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_medical (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, docteur_id INT NOT NULL, type_suivi VARCHAR(255) DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, frequence VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_F21CF3FF6B899279 (patient_id), INDEX IDX_F21CF3FFCF22540A (docteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chirurgie ADD CONSTRAINT FK_EC5628096B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation_sang ADD CONSTRAINT FK_28E0E88163DBB69 FOREIGN KEY (hospital_id) REFERENCES hospital (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FF6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FFCF22540A FOREIGN KEY (docteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie DROP FOREIGN KEY FK_EC5628096B899279');
        $this->addSql('ALTER TABLE donation_sang DROP FOREIGN KEY FK_28E0E88163DBB69');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FF6B899279');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FFCF22540A');
        $this->addSql('DROP TABLE chirurgie');
        $this->addSql('DROP TABLE donation_argent');
        $this->addSql('DROP TABLE donation_sang');
        $this->addSql('DROP TABLE hospital');
        $this->addSql('DROP TABLE suivi_medical');
    }
}
