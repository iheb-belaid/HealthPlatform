<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250202224256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD last_name VARCHAR(255) DEFAULT NULL, ADD phone_number INT DEFAULT NULL, ADD gender VARCHAR(255) DEFAULT NULL, ADD specialty VARCHAR(255) DEFAULT NULL, ADD birth_date DATE DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, CHANGE specialite first_name VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD specialite VARCHAR(255) DEFAULT NULL, DROP first_name, DROP last_name, DROP phone_number, DROP gender, DROP specialty, DROP birth_date, DROP city');
    }
}
