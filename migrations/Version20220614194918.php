<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614194918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_4C62E638A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, user_id, email, date, sujet, description FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, email VARCHAR(255) NOT NULL, date DATETIME NOT NULL, sujet VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, CONSTRAINT FK_4C62E638A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contact (id, user_id, email, date, sujet, description) SELECT id, user_id, email, date, sujet, description FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
        $this->addSql('CREATE INDEX IDX_4C62E638A76ED395 ON contact (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal AS SELECT id, restaurant_id, name, user_id FROM meal');
        $this->addSql('DROP TABLE meal');
        $this->addSql('CREATE TABLE meal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, restaurant_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, CONSTRAINT FK_9EF68E9CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO meal (id, restaurant_id, name, user_id) SELECT id, restaurant_id, name, user_id FROM __temp__meal');
        $this->addSql('DROP TABLE __temp__meal');
        $this->addSql('CREATE INDEX IDX_9EF68E9CA76ED395 ON meal (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name, email, password FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, name, email, password) SELECT id, name, email, password FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_4C62E638A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__contact AS SELECT id, user_id, email, date, sujet, description FROM contact');
        $this->addSql('DROP TABLE contact');
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, email VARCHAR(255) NOT NULL, date DATETIME NOT NULL, sujet VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO contact (id, user_id, email, date, sujet, description) SELECT id, user_id, email, date, sujet, description FROM __temp__contact');
        $this->addSql('DROP TABLE __temp__contact');
        $this->addSql('CREATE INDEX IDX_4C62E638A76ED395 ON contact (user_id)');
        $this->addSql('DROP INDEX IDX_9EF68E9CA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__meal AS SELECT id, user_id, restaurant_id, name FROM meal');
        $this->addSql('DROP TABLE meal');
        $this->addSql('CREATE TABLE meal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, restaurant_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO meal (id, user_id, restaurant_id, name) SELECT id, user_id, restaurant_id, name FROM __temp__meal');
        $this->addSql('DROP TABLE __temp__meal');
        $this->addSql('ALTER TABLE user ADD COLUMN meal_id INTEGER DEFAULT NULL');
    }
}
