<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220919184304 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruit (id INT AUTO_INCREMENT NOT NULL, nutrients_id INT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A00BD297DC38AADE (nutrients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nutrients (id INT AUTO_INCREMENT NOT NULL, carbohydrates DOUBLE PRECISION NOT NULL, protein DOUBLE PRECISION NOT NULL, fat DOUBLE PRECISION NOT NULL, calories DOUBLE PRECISION NOT NULL, sugar DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fruit ADD CONSTRAINT FK_A00BD297DC38AADE FOREIGN KEY (nutrients_id) REFERENCES nutrients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit DROP FOREIGN KEY FK_A00BD297DC38AADE');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE nutrients');
    }
}
