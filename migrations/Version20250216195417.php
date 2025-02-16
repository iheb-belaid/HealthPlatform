<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250216195417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chirurgie (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, nom_operation VARCHAR(255) NOT NULL, date_chirurgie DATETIME NOT NULL, nom_etablissement VARCHAR(255) NOT NULL, notes LONGTEXT DEFAULT NULL, nom_docteur VARCHAR(255) NOT NULL, INDEX IDX_EC5628096B899279 (patient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, docteur_id INT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, motif VARCHAR(255) NOT NULL, diagnostic VARCHAR(255) DEFAULT NULL, traitement VARCHAR(255) DEFAULT NULL, prix NUMERIC(10, 0) NOT NULL, INDEX IDX_964685A66B899279 (patient_id), INDEX IDX_964685A6CF22540A (docteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE suivi_medical (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, docteur_id INT NOT NULL, type_suivi VARCHAR(255) DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, frequence VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_F21CF3FF6B899279 (patient_id), INDEX IDX_F21CF3FFCF22540A (docteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, phone_number INT DEFAULT NULL, gender VARCHAR(255) DEFAULT NULL, specialty VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, prename VARCHAR(255) DEFAULT NULL, num_phone INT DEFAULT NULL, sexe VARCHAR(255) DEFAULT NULL, date_de_naissance DATE DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chirurgie ADD CONSTRAINT FK_EC5628096B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6CF22540A FOREIGN KEY (docteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FF6B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE suivi_medical ADD CONSTRAINT FK_F21CF3FFCF22540A FOREIGN KEY (docteur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chirurgie DROP FOREIGN KEY FK_EC5628096B899279');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66B899279');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6CF22540A');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FF6B899279');
        $this->addSql('ALTER TABLE suivi_medical DROP FOREIGN KEY FK_F21CF3FFCF22540A');
        $this->addSql('DROP TABLE chirurgie');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('DROP TABLE suivi_medical');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
