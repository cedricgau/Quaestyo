<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210526110534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player (id_player VARCHAR(30) NOT NULL, mail VARCHAR(100) NOT NULL, pseudo VARCHAR(100) NOT NULL, date_creation DATE NOT NULL, city VARCHAR(50) DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, first_purchase DATE DEFAULT NULL, state VARCHAR(30) DEFAULT NULL, currency1 INT DEFAULT NULL, currency2 INT DEFAULT NULL, currency3 INT DEFAULT NULL, currency4 INT DEFAULT NULL, currency5 INT DEFAULT NULL, currency6 INT DEFAULT NULL, phone VARCHAR(13) DEFAULT NULL, last_seen DATE DEFAULT NULL, PRIMARY KEY(id_player)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE player');
    }
}
