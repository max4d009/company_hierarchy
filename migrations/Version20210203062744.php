<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210203062744 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP CONSTRAINT fk_64c19c1a977936c');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT fk_64c19c1727aca70');
        $this->addSql('DROP INDEX idx_64c19c1727aca70');
        $this->addSql('DROP INDEX idx_64c19c1a977936c');
        $this->addSql('ALTER TABLE category DROP tree_root');
        $this->addSql('ALTER TABLE category DROP parent_id');
        $this->addSql('ALTER TABLE category DROP lft');
        $this->addSql('ALTER TABLE category DROP lvl');
        $this->addSql('ALTER TABLE category DROP rgt');
        $this->addSql('ALTER TABLE category DROP count_all_employees_cache');
        $this->addSql('ALTER TABLE employee ADD tree_root INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD lft INT NOT NULL');
        $this->addSql('ALTER TABLE employee ADD lvl INT NOT NULL');
        $this->addSql('ALTER TABLE employee ADD rgt INT NOT NULL');
        $this->addSql('ALTER TABLE employee ADD count_all_employees_cache INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1A977936C FOREIGN KEY (tree_root) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1727ACA70 FOREIGN KEY (parent_id) REFERENCES employee (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5D9F75A1A977936C ON employee (tree_root)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1727ACA70 ON employee (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1A977936C');
        $this->addSql('ALTER TABLE employee DROP CONSTRAINT FK_5D9F75A1727ACA70');
        $this->addSql('DROP INDEX IDX_5D9F75A1A977936C');
        $this->addSql('DROP INDEX IDX_5D9F75A1727ACA70');
        $this->addSql('ALTER TABLE employee DROP tree_root');
        $this->addSql('ALTER TABLE employee DROP parent_id');
        $this->addSql('ALTER TABLE employee DROP lft');
        $this->addSql('ALTER TABLE employee DROP lvl');
        $this->addSql('ALTER TABLE employee DROP rgt');
        $this->addSql('ALTER TABLE employee DROP count_all_employees_cache');
        $this->addSql('ALTER TABLE category ADD tree_root INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD lft INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD lvl INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD rgt INT NOT NULL');
        $this->addSql('ALTER TABLE category ADD count_all_employees_cache INT DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT fk_64c19c1a977936c FOREIGN KEY (tree_root) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT fk_64c19c1727aca70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_64c19c1727aca70 ON category (parent_id)');
        $this->addSql('CREATE INDEX idx_64c19c1a977936c ON category (tree_root)');
    }
}
