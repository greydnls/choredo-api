<?php declare(strict_types = 1);

namespace Choredo\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171124045333 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE accounts (id UUID NOT NULL, family_id UUID NOT NULL, email_address VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, avatar_uri VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CAC89EACC35E566A ON accounts (family_id)');
        $this->addSql('COMMENT ON COLUMN accounts.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN accounts.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE chores (id UUID NOT NULL, family_id UUID NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, value INT DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_735604D4C35E566A ON chores (family_id)');
        $this->addSql('COMMENT ON COLUMN chores.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN chores.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EACC35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE chores ADD CONSTRAINT FK_735604D4C35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE accounts');
        $this->addSql('DROP TABLE chores');
    }
}
