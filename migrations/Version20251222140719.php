<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222140719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tag (id INT UNSIGNED AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, slug VARCHAR(105) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE tag_article (tag_id INT UNSIGNED NOT NULL, article_id INT UNSIGNED NOT NULL, INDEX IDX_300B23CCBAD26311 (tag_id), INDEX IDX_300B23CC7294869C (article_id), PRIMARY KEY (tag_id, article_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CCBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CC7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CCBAD26311');
        $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CC7294869C');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_article');
    }
}
