<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616110144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company_sector (company_id INT NOT NULL, sector_id INT NOT NULL, INDEX IDX_763CBD9D979B1AD6 (company_id), INDEX IDX_763CBD9DDE95C867 (sector_id), PRIMARY KEY(company_id, sector_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company_sector ADD CONSTRAINT FK_763CBD9D979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE company_sector ADD CONSTRAINT FK_763CBD9DDE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company_sector DROP FOREIGN KEY FK_763CBD9D979B1AD6');
        $this->addSql('ALTER TABLE company_sector DROP FOREIGN KEY FK_763CBD9DDE95C867');
        $this->addSql('DROP TABLE company_sector');
        $this->addSql('DROP TABLE sector');
    }
}
