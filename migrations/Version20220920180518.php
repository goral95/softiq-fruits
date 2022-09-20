<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920180518 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruit_in_salad (id INT AUTO_INCREMENT NOT NULL, fruit_id INT NOT NULL, nutrients_id INT NOT NULL, fruit_salad_recipe_id INT NOT NULL, weight INT NOT NULL, INDEX IDX_19DC701BBAC115F0 (fruit_id), UNIQUE INDEX UNIQ_19DC701BDC38AADE (nutrients_id), INDEX IDX_19DC701BEF112C8E (fruit_salad_recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fruit_salad_recipe (id INT AUTO_INCREMENT NOT NULL, nutrients_id INT NOT NULL, weight INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_DA554BDBDC38AADE (nutrients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fruit_in_salad ADD CONSTRAINT FK_19DC701BBAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id)');
        $this->addSql('ALTER TABLE fruit_in_salad ADD CONSTRAINT FK_19DC701BDC38AADE FOREIGN KEY (nutrients_id) REFERENCES nutrients (id)');
        $this->addSql('ALTER TABLE fruit_in_salad ADD CONSTRAINT FK_19DC701BEF112C8E FOREIGN KEY (fruit_salad_recipe_id) REFERENCES fruit_salad_recipe (id)');
        $this->addSql('ALTER TABLE fruit_salad_recipe ADD CONSTRAINT FK_DA554BDBDC38AADE FOREIGN KEY (nutrients_id) REFERENCES nutrients (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fruit_in_salad DROP FOREIGN KEY FK_19DC701BBAC115F0');
        $this->addSql('ALTER TABLE fruit_in_salad DROP FOREIGN KEY FK_19DC701BDC38AADE');
        $this->addSql('ALTER TABLE fruit_in_salad DROP FOREIGN KEY FK_19DC701BEF112C8E');
        $this->addSql('ALTER TABLE fruit_salad_recipe DROP FOREIGN KEY FK_DA554BDBDC38AADE');
        $this->addSql('DROP TABLE fruit_in_salad');
        $this->addSql('DROP TABLE fruit_salad_recipe');
    }
}
