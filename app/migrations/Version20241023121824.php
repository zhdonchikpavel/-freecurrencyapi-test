<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241023121824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL, decimal_digits SMALLINT NOT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6956883F77153098 ON currency (code)');
        $this->addSql('COMMENT ON COLUMN currency.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN currency.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE rate (id SERIAL NOT NULL, source_currency_id INT NOT NULL, target_currency_id INT NOT NULL, rate DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFEC3F3945BD1D6 ON rate (source_currency_id)');
        $this->addSql('CREATE INDEX IDX_DFEC3F39BF1ECE7C ON rate (target_currency_id)');
        $this->addSql('COMMENT ON COLUMN rate.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN rate.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F3945BD1D6 FOREIGN KEY (source_currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE rate ADD CONSTRAINT FK_DFEC3F39BF1ECE7C FOREIGN KEY (target_currency_id) REFERENCES currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT FK_DFEC3F3945BD1D6');
        $this->addSql('ALTER TABLE rate DROP CONSTRAINT FK_DFEC3F39BF1ECE7C');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE rate');
    }
}
