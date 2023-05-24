<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524205355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE educations (id SERIAL NOT NULL, institution VARCHAR(175) NOT NULL, faculty VARCHAR(175) NOT NULL, specialization VARCHAR(175) NOT NULL, from_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, to_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE files (id SERIAL NOT NULL, name VARCHAR(200) NOT NULL, path VARCHAR(200) NOT NULL, mime_type VARCHAR(25) NOT NULL, size INT DEFAULT 0 NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE jobs (id SERIAL NOT NULL, name VARCHAR(175) NOT NULL, type VARCHAR(30) NOT NULL, description TEXT NOT NULL, from_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, to_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, company_name VARCHAR(255) NOT NULL, company_url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE links (id SERIAL NOT NULL, project_id INT NOT NULL, title VARCHAR(175) NOT NULL, url VARCHAR(175) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D182A118166D1F9C ON links (project_id)');
        $this->addSql('CREATE TABLE projects (id SERIAL NOT NULL, image_id INT DEFAULT NULL, name VARCHAR(175) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5C93B3A43DA5256D ON projects (image_id)');
        $this->addSql('CREATE TABLE project_tags (project_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(project_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_562D5C3E166D1F9C ON project_tags (project_id)');
        $this->addSql('CREATE INDEX IDX_562D5C3EBAD26311 ON project_tags (tag_id)');
        $this->addSql('CREATE TABLE properties (key VARCHAR(200) NOT NULL, value VARCHAR(200) NOT NULL, PRIMARY KEY(key))');
        $this->addSql('CREATE TABLE sections (id SERIAL NOT NULL, name VARCHAR(175) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE skills (id SERIAL NOT NULL, image_id INT DEFAULT NULL, section_id INT NOT NULL, name VARCHAR(175) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D53116703DA5256D ON skills (image_id)');
        $this->addSql('CREATE INDEX IDX_D5311670D823E37A ON skills (section_id)');
        $this->addSql('CREATE TABLE tags (id SERIAL NOT NULL, name VARCHAR(75) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, first_name VARCHAR(75) NOT NULL, last_name VARCHAR(75) NOT NULL, email VARCHAR(175) NOT NULL, password VARCHAR(175) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON users (email)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A43DA5256D FOREIGN KEY (image_id) REFERENCES files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_tags ADD CONSTRAINT FK_562D5C3E166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project_tags ADD CONSTRAINT FK_562D5C3EBAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D53116703DA5256D FOREIGN KEY (image_id) REFERENCES files (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE skills ADD CONSTRAINT FK_D5311670D823E37A FOREIGN KEY (section_id) REFERENCES sections (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE links DROP CONSTRAINT FK_D182A118166D1F9C');
        $this->addSql('ALTER TABLE projects DROP CONSTRAINT FK_5C93B3A43DA5256D');
        $this->addSql('ALTER TABLE project_tags DROP CONSTRAINT FK_562D5C3E166D1F9C');
        $this->addSql('ALTER TABLE project_tags DROP CONSTRAINT FK_562D5C3EBAD26311');
        $this->addSql('ALTER TABLE skills DROP CONSTRAINT FK_D53116703DA5256D');
        $this->addSql('ALTER TABLE skills DROP CONSTRAINT FK_D5311670D823E37A');
        $this->addSql('DROP TABLE educations');
        $this->addSql('DROP TABLE files');
        $this->addSql('DROP TABLE jobs');
        $this->addSql('DROP TABLE links');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE project_tags');
        $this->addSql('DROP TABLE properties');
        $this->addSql('DROP TABLE sections');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE users');
    }
}
