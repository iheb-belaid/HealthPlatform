<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217164416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, patient_id INT NOT NULL, docteur_id INT NOT NULL, date DATETIME NOT NULL, motif VARCHAR(255) NOT NULL, diagnostic VARCHAR(255) NOT NULL, traitement VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, INDEX IDX_964685A66B899279 (patient_id), INDEX IDX_964685A6CF22540A (docteur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rendez_vous (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, heure TIME NOT NULL, statut VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66B899279 FOREIGN KEY (patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6CF22540A FOREIGN KEY (docteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE donation_argent CHANGE date date DATETIME NOT NULL');
        $this->addSql('ALTER TABLE donation_sang CHANGE date_donation date_donation DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66B899279');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6CF22540A');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE rendez_vous');
        $this->addSql('ALTER TABLE donation_argent CHANGE date date DATE NOT NULL');
        $this->addSql('ALTER TABLE donation_sang CHANGE date_donation date_donation DATE NOT NULL');
    }
}
