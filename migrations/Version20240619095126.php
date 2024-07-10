<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240619095126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, categorie VARCHAR(255) NOT NULL, video VARCHAR(255) NOT NULL, langue VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE episode (id INT AUTO_INCREMENT NOT NULL, content_id_id INT NOT NULL, saison_id INT NOT NULL, numero_episode INT NOT NULL, titre_episode VARCHAR(255) NOT NULL, duree INT NOT NULL, INDEX IDX_DDAA1CDA9487CA85 (content_id_id), INDEX IDX_DDAA1CDAF965414C (saison_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saison (id INT AUTO_INCREMENT NOT NULL, content_id_id INT NOT NULL, numero_saison INT NOT NULL, INDEX IDX_C0D0D5869487CA85 (content_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nickname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDA9487CA85 FOREIGN KEY (content_id_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE episode ADD CONSTRAINT FK_DDAA1CDAF965414C FOREIGN KEY (saison_id) REFERENCES saison (id)');
        $this->addSql('ALTER TABLE saison ADD CONSTRAINT FK_C0D0D5869487CA85 FOREIGN KEY (content_id_id) REFERENCES content (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDA9487CA85');
        $this->addSql('ALTER TABLE episode DROP FOREIGN KEY FK_DDAA1CDAF965414C');
        $this->addSql('ALTER TABLE saison DROP FOREIGN KEY FK_C0D0D5869487CA85');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE episode');
        $this->addSql('DROP TABLE saison');
        $this->addSql('DROP TABLE user');
    }
}
