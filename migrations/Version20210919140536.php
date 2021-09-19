<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210919140536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id VARCHAR(255) NOT NULL, date DATE NOT NULL --(DC2Type:date_immutable)
        , text CLOB NOT NULL, link CLOB NOT NULL, placeholders CLOB NOT NULL --(DC2Type:array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE event_segments (event_id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, PRIMARY KEY(event_id, segment_id))');
        $this->addSql('CREATE INDEX IDX_602232F971F7E88B ON event_segments (event_id)');
        $this->addSql('CREATE INDEX IDX_602232F9DB296AAD ON event_segments (segment_id)');
        $this->addSql('CREATE TABLE segment (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE subscriber (id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE subscriber_segments (subscriber_id VARCHAR(255) NOT NULL, segment_id VARCHAR(255) NOT NULL, PRIMARY KEY(subscriber_id, segment_id))');
        $this->addSql('CREATE INDEX IDX_550044167808B1AD ON subscriber_segments (subscriber_id)');
        $this->addSql('CREATE INDEX IDX_55004416DB296AAD ON subscriber_segments (segment_id)');
        $this->addSql('CREATE TABLE subscriber_information (id VARCHAR(255) NOT NULL, subscriber_id VARCHAR(255) DEFAULT NULL, "key" VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C4BC33F7808B1AD ON subscriber_information (subscriber_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_segments');
        $this->addSql('DROP TABLE segment');
        $this->addSql('DROP TABLE subscriber');
        $this->addSql('DROP TABLE subscriber_segments');
        $this->addSql('DROP TABLE subscriber_information');
    }
}
