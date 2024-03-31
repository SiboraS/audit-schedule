<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329230632 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          assigned_job
        CHANGE
          job_id job_id INT NOT NULL,
        CHANGE
          auditor_id auditor_id INT NOT NULL');
        $this->addSql('ALTER TABLE auditor CHANGE location_id location_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE
          assigned_job
        CHANGE
          job_id job_id INT DEFAULT NULL,
        CHANGE
          auditor_id auditor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE auditor CHANGE location_id location_id INT DEFAULT NULL');
    }
}
