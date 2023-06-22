<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230622211054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE worker_skills (worker_id INT NOT NULL, skills_id INT NOT NULL, INDEX IDX_5493A7196B20BA36 (worker_id), INDEX IDX_5493A7197FF61858 (skills_id), PRIMARY KEY(worker_id, skills_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE worker_skills ADD CONSTRAINT FK_5493A7196B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE worker_skills ADD CONSTRAINT FK_5493A7197FF61858 FOREIGN KEY (skills_id) REFERENCES skills (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE worker_skills DROP FOREIGN KEY FK_5493A7196B20BA36');
        $this->addSql('ALTER TABLE worker_skills DROP FOREIGN KEY FK_5493A7197FF61858');
        $this->addSql('DROP TABLE worker_skills');
    }
}
