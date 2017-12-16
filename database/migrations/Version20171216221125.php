<?php declare(strict_types=1);

namespace Choredo\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20171216221125 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE children (id UUID NOT NULL, family_id UUID NOT NULL, name VARCHAR(255) NOT NULL, access_code VARCHAR(255) DEFAULT NULL, avatar_uri VARCHAR(255) DEFAULT NULL, color CHAR(7) DEFAULT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A197B1BAC35E566A ON children (family_id)');
        $this->addSql('COMMENT ON COLUMN children.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN children.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE children ADD CONSTRAINT FK_A197B1BAC35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE children');
    }
}
