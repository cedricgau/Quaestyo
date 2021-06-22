<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210622174222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE extern_datas ADD download INT DEFAULT NULL, ADD uninstall INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY game_ibfk_1');
        $this->addSql('DROP INDEX id_player ON game');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE extern_datas DROP download, DROP uninstall');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT game_ibfk_1 FOREIGN KEY (id_player) REFERENCES player (id_player) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX id_player ON game (id_player)');
    }
}
