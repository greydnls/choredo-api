<?php declare(strict_types=1);

namespace Choredo\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180106210936 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql(
            'CREATE TABLE assignment_completions (id UUID NOT NULL, assignment_id UUID NOT NULL, child_id UUID NOT NULL, family_id UUID NOT NULL, chore_description VARCHAR(255) NOT NULL, chore_value INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE INDEX IDX_BAD92926D19302F8 ON assignment_completions (assignment_id)');
        $this->addSql('CREATE INDEX IDX_BAD92926DD62C21B ON assignment_completions (child_id)');
        $this->addSql('CREATE INDEX IDX_BAD92926C35E566A ON assignment_completions (family_id)');
        $this->addSql('COMMENT ON COLUMN assignment_completions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_completions.assignment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_completions.child_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_completions.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE assignment_completions ADD CONSTRAINT FK_BAD92926D19302F8 FOREIGN KEY (assignment_id) REFERENCES assignments (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignment_completions ADD CONSTRAINT FK_BAD92926DD62C21B FOREIGN KEY (child_id) REFERENCES children (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignment_completions ADD CONSTRAINT FK_BAD92926C35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
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
        $this->addSql('DROP TABLE assignment_completions');
    }
}
