<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221113152128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place ADD COLUMN tips CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD COLUMN recommandations CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__place AS SELECT id, city_id, name, price_range, security_level, charo_rate, has_cocktails, has_beers, has_wines, has_softs, description, image_filename FROM place');
        $this->addSql('DROP TABLE place');
        $this->addSql('CREATE TABLE place (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, city_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, price_range INTEGER NOT NULL, security_level INTEGER NOT NULL, charo_rate INTEGER DEFAULT NULL, has_cocktails BOOLEAN NOT NULL, has_beers BOOLEAN NOT NULL, has_wines BOOLEAN NOT NULL, has_softs BOOLEAN NOT NULL, description CLOB NOT NULL, image_filename VARCHAR(255) NOT NULL, CONSTRAINT FK_741D53CD8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO place (id, city_id, name, price_range, security_level, charo_rate, has_cocktails, has_beers, has_wines, has_softs, description, image_filename) SELECT id, city_id, name, price_range, security_level, charo_rate, has_cocktails, has_beers, has_wines, has_softs, description, image_filename FROM __temp__place');
        $this->addSql('DROP TABLE __temp__place');
        $this->addSql('CREATE INDEX IDX_741D53CD8BAC62AF ON place (city_id)');
    }
}
