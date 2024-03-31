<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329225802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assigned_job (assignment_id INT AUTO_INCREMENT NOT NULL, completion_status INT NOT NULL, assessment LONGTEXT NOT NULL, job_id INT DEFAULT NULL, auditor_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E0872CFEBE04EA9 (job_id), INDEX IDX_E0872CFEFEF3FDAB (auditor_id), PRIMARY KEY(assignment_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE auditor (auditor_id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, location_id INT DEFAULT NULL, INDEX IDX_CE48CAAD64D218E (location_id), PRIMARY KEY(auditor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE job (job_id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(job_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE location (location_id INT AUTO_INCREMENT NOT NULL, name LONGTEXT NOT NULL, PRIMARY KEY(location_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE assigned_job ADD CONSTRAINT FK_E0872CFEBE04EA9 FOREIGN KEY (job_id) REFERENCES job (job_id)');
        $this->addSql('ALTER TABLE assigned_job ADD CONSTRAINT FK_E0872CFEFEF3FDAB FOREIGN KEY (auditor_id) REFERENCES auditor (auditor_id)');
        $this->addSql('ALTER TABLE auditor ADD CONSTRAINT FK_CE48CAAD64D218E FOREIGN KEY (location_id) REFERENCES location (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assigned_job DROP FOREIGN KEY FK_E0872CFEBE04EA9');
        $this->addSql('ALTER TABLE assigned_job DROP FOREIGN KEY FK_E0872CFEFEF3FDAB');
        $this->addSql('ALTER TABLE auditor DROP FOREIGN KEY FK_CE48CAAD64D218E');
        $this->addSql('DROP TABLE assigned_job');
        $this->addSql('DROP TABLE auditor');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE location');
    }
}
