<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241028104351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('DROP INDEX IDX_23A0E66A76ED395 ON article');
        $this->addSql('ALTER TABLE article ADD user_id_id INT UNSIGNED NOT NULL, DROP user_id, CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE article_date_create article_date_create DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE published published TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E669D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_23A0E669D86650F ON article (user_id_id)');
        $this->addSql('ALTER TABLE article_section CHANGE article_id article_id INT UNSIGNED NOT NULL, CHANGE section_id section_id INT UNSIGNED NOT NULL');
        $this->addSql('ALTER TABLE section CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2D737AEF1D237769 ON section (section_slug)');
        $this->addSql('ALTER TABLE user CHANGE id id INT UNSIGNED AUTO_INCREMENT NOT NULL, CHANGE activate activate TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E669D86650F');
        $this->addSql('DROP INDEX IDX_23A0E669D86650F ON article');
        $this->addSql('ALTER TABLE article ADD user_id INT NOT NULL, DROP user_id_id, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE article_date_create article_date_create DATETIME NOT NULL, CHANGE published published TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_23A0E66A76ED395 ON article (user_id)');
        $this->addSql('ALTER TABLE article_section CHANGE article_id article_id INT NOT NULL, CHANGE section_id section_id INT NOT NULL');
        $this->addSql('DROP INDEX UNIQ_2D737AEF1D237769 ON section');
        $this->addSql('ALTER TABLE section CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE activate activate TINYINT(1) NOT NULL');
    }
}
