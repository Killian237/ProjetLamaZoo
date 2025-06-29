<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250627214222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // La colonne 'dure' existe déjà, on ne l'ajoute plus
        // $this->addSql(<<<'SQL'
        //     ALTER TABLE ateliers ADD dure TIME NOT NULL
        // SQL);
    }

    public function down(Schema $schema): void
    {
        // Cette ligne supprimera la colonne si tu fais un rollback
        $this->addSql(<<<'SQL'
            ALTER TABLE ateliers DROP dure
        SQL);
    }
}
