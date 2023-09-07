<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907065804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD province_name_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA65F390A FOREIGN KEY (province_name_id) REFERENCES province (id)');
        $this->addSql('CREATE INDEX IDX_4FBF094FA65F390A ON company (province_name_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6496B20BA36');
        $this->addSql('DROP INDEX UNIQ_8D93D6496B20BA36 ON user');
        $this->addSql('ALTER TABLE user DROP worker_id');
        $this->addSql('ALTER TABLE worker ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE worker ADD CONSTRAINT FK_9FB2BF62A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9FB2BF62A76ED395 ON worker (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA65F390A');
        $this->addSql('DROP TABLE province');
        $this->addSql('ALTER TABLE worker DROP FOREIGN KEY FK_9FB2BF62A76ED395');
        $this->addSql('DROP INDEX IDX_9FB2BF62A76ED395 ON worker');
        $this->addSql('ALTER TABLE worker DROP user_id');
        $this->addSql('DROP INDEX IDX_4FBF094FA65F390A ON company');
        $this->addSql('ALTER TABLE company DROP province_name_id');
        $this->addSql('ALTER TABLE user ADD worker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6496B20BA36 FOREIGN KEY (worker_id) REFERENCES worker (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B20BA36 ON user (worker_id)');
    }
}
