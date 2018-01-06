<?php declare(strict_types=1);

namespace Choredo\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180106220555 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql(
            'CREATE TABLE assignment_approvals (id UUID NOT NULL, completion_id UUID NOT NULL, account_id UUID NOT NULL, family_id UUID NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7AB7D239C7995787 ON assignment_approvals (completion_id)');
        $this->addSql('CREATE INDEX IDX_7AB7D2399B6B5FBA ON assignment_approvals (account_id)');
        $this->addSql('CREATE INDEX IDX_7AB7D239C35E566A ON assignment_approvals (family_id)');
        $this->addSql('COMMENT ON COLUMN assignment_approvals.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_approvals.completion_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_approvals.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN assignment_approvals.family_id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE assignment_approvals ADD CONSTRAINT FK_7AB7D239C7995787 FOREIGN KEY (completion_id) REFERENCES assignment_completions (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignment_approvals ADD CONSTRAINT FK_7AB7D2399B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql(
            'ALTER TABLE assignment_approvals ADD CONSTRAINT FK_7AB7D239C35E566A FOREIGN KEY (family_id) REFERENCES families (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('ALTER TABLE assignment_completions ADD approval_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN assignment_completions.approval_id IS \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE assignment_completions ADD CONSTRAINT FK_BAD92926FE65F000 FOREIGN KEY (approval_id) REFERENCES assignment_approvals (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BAD92926FE65F000 ON assignment_completions (approval_id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE assignment_completions DROP CONSTRAINT FK_BAD92926FE65F000');
        $this->addSql('DROP TABLE assignment_approvals');
        $this->addSql('DROP INDEX UNIQ_BAD92926FE65F000');
        $this->addSql('ALTER TABLE assignment_completions DROP approval_id');
    }
}
