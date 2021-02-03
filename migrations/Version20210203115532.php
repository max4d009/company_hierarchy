<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203115532 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX email_index ON employee (email)');
        $this->addSql('ALTER INDEX idx_5d9f75a1a977936c RENAME TO tree_root_index');
        $this->addSql('ALTER INDEX idx_5d9f75a1727aca70 RENAME TO parent_id_index');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX email_index');
        $this->addSql('ALTER INDEX parent_id_index RENAME TO idx_5d9f75a1727aca70');
        $this->addSql('ALTER INDEX tree_root_index RENAME TO idx_5d9f75a1a977936c');
    }
}
