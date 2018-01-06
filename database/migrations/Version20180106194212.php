<?php declare(strict_types=1);

namespace Choredo\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180106194212 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql(
            'CREATE TABLE assignments (id UUID NOT NULL, child_id UUID NOT NULL, chore_id UUID NOT NULL, family_id UUID NOT NULL, day_of_week SMALLINT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_308A50DDDD62C21B ON assignments (child_id)');
        $this->addSql('CREATE INDEX IDX_308A50DD6C576F80 ON assignments (chore_id)');
        $this->addSql('CREATE INDEX IDX_308A50DDC35E566A ON assignments (family_id)');
        $this->addSql('COMMENT ON COLUMN assignments.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignments.child_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignments.chore_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignments.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE assignments ADD CONSTRAINT FK_308A50DDDD62C21B FOREIGN KEY (child_id) REFERENCES children (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignments ADD CONSTRAINT FK_308A50DD6C576F80 FOREIGN KEY (chore_id) REFERENCES chores (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignments ADD CONSTRAINT FK_308A50DDC35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE assignments');
    }
}
