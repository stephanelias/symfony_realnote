<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825225600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover ADD album_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cover ADD CONSTRAINT FK_8D0886C51137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D0886C51137ABCF ON cover (album_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cover DROP FOREIGN KEY FK_8D0886C51137ABCF');
        $this->addSql('DROP INDEX UNIQ_8D0886C51137ABCF ON cover');
        $this->addSql('ALTER TABLE cover DROP album_id');
    }
}
