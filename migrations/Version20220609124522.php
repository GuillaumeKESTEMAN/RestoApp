<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220609124522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal ADD COLUMN user_id INTEGER DEFAULT NULL');
        $this->addSql('ALTER TABLE restaurant ADD COLUMN user_id INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal AS SELECT id, restaurant_id, name FROM meal');
        $this->addSql('DROP TABLE meal');
        $this->addSql('CREATE TABLE meal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, restaurant_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO meal (id, restaurant_id, name) SELECT id, restaurant_id, name FROM __temp__meal');
        $this->addSql('DROP TABLE __temp__meal');
        $this->addSql('CREATE TEMPORARY TABLE __temp__restaurant AS SELECT id, name FROM restaurant');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('CREATE TABLE restaurant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO restaurant (id, name) SELECT id, name FROM __temp__restaurant');
        $this->addSql('DROP TABLE __temp__restaurant');
    }
}
