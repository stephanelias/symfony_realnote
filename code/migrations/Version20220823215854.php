<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823215854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo ADD artist_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B78418B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14B78418B7970CF8 ON photo (artist_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B78418B7970CF8');
        $this->addSql('DROP INDEX UNIQ_14B78418B7970CF8 ON photo');
        $this->addSql('ALTER TABLE photo DROP artist_id');
    }
}
