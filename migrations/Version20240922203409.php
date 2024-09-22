<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240922203409 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE module_entity (id_module INT AUTO_INCREMENT NOT NULL, nom_module VARCHAR(255) NOT NULL, id_unite INT DEFAULT NULL, UNIQUE INDEX UNIQ_A42B04478CEBB97F (nom_module), INDEX IDX_A42B0447F3E18028 (id_unite), PRIMARY KEY(id_module)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE monitoring_entity (id_monitoring_module INT AUTO_INCREMENT NOT NULL, id_module INT DEFAULT NULL, INDEX IDX_1D166C012A1393C5 (id_module), PRIMARY KEY(id_monitoring_module)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE releve_entity (id_releve INT AUTO_INCREMENT NOT NULL, valeur DOUBLE PRECISION DEFAULT NULL, etat TINYINT(1) NOT NULL, date DATETIME NOT NULL, id_module INT DEFAULT NULL, INDEX IDX_79E8378B2A1393C5 (id_module), PRIMARY KEY(id_releve)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE unite_entity (id_unite INT AUTO_INCREMENT NOT NULL, nom_unite VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B75FE29CB79C5D8D (nom_unite), PRIMARY KEY(id_unite)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE module_entity ADD CONSTRAINT FK_A42B0447F3E18028 FOREIGN KEY (id_unite) REFERENCES unite_entity (id_unite)');
        $this->addSql('ALTER TABLE monitoring_entity ADD CONSTRAINT FK_1D166C012A1393C5 FOREIGN KEY (id_module) REFERENCES module_entity (id_module)');
        $this->addSql('ALTER TABLE releve_entity ADD CONSTRAINT FK_79E8378B2A1393C5 FOREIGN KEY (id_module) REFERENCES module_entity (id_module)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE module_entity DROP FOREIGN KEY FK_A42B0447F3E18028');
        $this->addSql('ALTER TABLE monitoring_entity DROP FOREIGN KEY FK_1D166C012A1393C5');
        $this->addSql('ALTER TABLE releve_entity DROP FOREIGN KEY FK_79E8378B2A1393C5');
        $this->addSql('DROP TABLE module_entity');
        $this->addSql('DROP TABLE monitoring_entity');
        $this->addSql('DROP TABLE releve_entity');
        $this->addSql('DROP TABLE unite_entity');
    }
}
