<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250625125326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE application (id INT AUTO_INCREMENT NOT NULL, possede_id INT DEFAULT NULL, nom_appli VARCHAR(255) NOT NULL, INDEX IDX_A45BDDC1C835AB29 (possede_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE est_habilite (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, role_applicatif_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, INDEX IDX_410194C31C109075 (personnel_id), INDEX IDX_410194C342EA83D3 (role_applicatif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE histo_habilitation (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, date_heure DATETIME NOT NULL, action VARCHAR(255) NOT NULL, INDEX IDX_D786FA7A3E030ACD (application_id), INDEX IDX_D786FA7A1C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE personnel (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT '(DC2Type:json)', password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE role_applicatif (id INT AUTO_INCREMENT NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, nom_service VARCHAR(255) NOT NULL, INDEX IDX_E19D9AD21C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE application ADD CONSTRAINT FK_A45BDDC1C835AB29 FOREIGN KEY (possede_id) REFERENCES role_applicatif (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE est_habilite ADD CONSTRAINT FK_410194C31C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE est_habilite ADD CONSTRAINT FK_410194C342EA83D3 FOREIGN KEY (role_applicatif_id) REFERENCES role_applicatif (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE histo_habilitation ADD CONSTRAINT FK_D786FA7A3E030ACD FOREIGN KEY (application_id) REFERENCES application (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE histo_habilitation ADD CONSTRAINT FK_D786FA7A1C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service ADD CONSTRAINT FK_E19D9AD21C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE application DROP FOREIGN KEY FK_A45BDDC1C835AB29
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE est_habilite DROP FOREIGN KEY FK_410194C31C109075
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE est_habilite DROP FOREIGN KEY FK_410194C342EA83D3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE histo_habilitation DROP FOREIGN KEY FK_D786FA7A3E030ACD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE histo_habilitation DROP FOREIGN KEY FK_D786FA7A1C109075
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD21C109075
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE application
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE est_habilite
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE histo_habilitation
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE personnel
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE role_applicatif
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE service
        SQL);
    }
}
