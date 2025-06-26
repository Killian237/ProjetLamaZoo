<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250626122009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE animaux (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, descrption LONGTEXT NOT NULL, parrainage INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ateliers (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, heure_debut DATETIME NOT NULL, heure_fin DATETIME NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE contenir (id INT AUTO_INCREMENT NOT NULL, panier_id INT DEFAULT NULL, ateliers_id INT DEFAULT NULL, INDEX IDX_3C914DFDF77D927C (panier_id), INDEX IDX_3C914DFDB1409BC9 (ateliers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mettre (id INT AUTO_INCREMENT NOT NULL, animaux_id INT DEFAULT NULL, panier_id INT DEFAULT NULL, INDEX IDX_C30E3CFEA9DAECAA (animaux_id), INDEX IDX_C30E3CFEF77D927C (panier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, date_creation DATETIME NOT NULL, regler TINYINT(1) NOT NULL, INDEX IDX_24CC0DF21C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE parrainer (id INT AUTO_INCREMENT NOT NULL, personnel_id INT DEFAULT NULL, animaux_id INT DEFAULT NULL, date_parrainage DATE NOT NULL, montant INT NOT NULL, INDEX IDX_3142953A1C109075 (personnel_id), INDEX IDX_3142953AA9DAECAA (animaux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE participer (id INT AUTO_INCREMENT NOT NULL, atelier_id INT DEFAULT NULL, personnel_id INT DEFAULT NULL, heure_debut DATETIME NOT NULL, heure_fin DATETIME NOT NULL, INDEX IDX_EDBE16F882E2CF35 (atelier_id), INDEX IDX_EDBE16F81C109075 (personnel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contenir ADD CONSTRAINT FK_3C914DFDB1409BC9 FOREIGN KEY (ateliers_id) REFERENCES ateliers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mettre ADD CONSTRAINT FK_C30E3CFEA9DAECAA FOREIGN KEY (animaux_id) REFERENCES animaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mettre ADD CONSTRAINT FK_C30E3CFEF77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF21C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parrainer ADD CONSTRAINT FK_3142953A1C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parrainer ADD CONSTRAINT FK_3142953AA9DAECAA FOREIGN KEY (animaux_id) REFERENCES animaux (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F882E2CF35 FOREIGN KEY (atelier_id) REFERENCES ateliers (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participer ADD CONSTRAINT FK_EDBE16F81C109075 FOREIGN KEY (personnel_id) REFERENCES personnel (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE personnel ADD parrainage INT DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFDF77D927C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contenir DROP FOREIGN KEY FK_3C914DFDB1409BC9
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mettre DROP FOREIGN KEY FK_C30E3CFEA9DAECAA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE mettre DROP FOREIGN KEY FK_C30E3CFEF77D927C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF21C109075
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parrainer DROP FOREIGN KEY FK_3142953A1C109075
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE parrainer DROP FOREIGN KEY FK_3142953AA9DAECAA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F882E2CF35
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE participer DROP FOREIGN KEY FK_EDBE16F81C109075
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE animaux
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ateliers
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contenir
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mettre
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE panier
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE parrainer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE participer
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE personnel DROP parrainage
        SQL);
    }
}
