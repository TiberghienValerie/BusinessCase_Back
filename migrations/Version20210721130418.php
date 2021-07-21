<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721130418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F65593E53241C035 ON annonce (ref_annonce)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A6F91CEDAA00E99 ON marque (nom_marque)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_F65593E53241C035 ON annonce');
        $this->addSql('DROP INDEX UNIQ_5A6F91CEDAA00E99 ON marque');
    }
}
