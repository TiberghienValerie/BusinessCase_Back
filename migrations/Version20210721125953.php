<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210721125953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce ADD carburant_id INT NOT NULL, ADD modele_id INT NOT NULL, ADD garage_id INT NOT NULL');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E532DAAD24 FOREIGN KEY (carburant_id) REFERENCES carburant (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5AC14B70A FOREIGN KEY (modele_id) REFERENCES modele (id)');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5C4FFF555 FOREIGN KEY (garage_id) REFERENCES garage (id)');
        $this->addSql('CREATE INDEX IDX_F65593E532DAAD24 ON annonce (carburant_id)');
        $this->addSql('CREATE INDEX IDX_F65593E5AC14B70A ON annonce (modele_id)');
        $this->addSql('CREATE INDEX IDX_F65593E5C4FFF555 ON annonce (garage_id)');
        $this->addSql('ALTER TABLE garage ADD ville_id INT NOT NULL');
        $this->addSql('ALTER TABLE garage ADD CONSTRAINT FK_9F26610BA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('CREATE INDEX IDX_9F26610BA73F0036 ON garage (ville_id)');
        $this->addSql('ALTER TABLE modele ADD marque_id INT NOT NULL');
        $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_100285584827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        $this->addSql('CREATE INDEX IDX_100285584827B9B2 ON modele (marque_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E532DAAD24');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5AC14B70A');
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5C4FFF555');
        $this->addSql('DROP INDEX IDX_F65593E532DAAD24 ON annonce');
        $this->addSql('DROP INDEX IDX_F65593E5AC14B70A ON annonce');
        $this->addSql('DROP INDEX IDX_F65593E5C4FFF555 ON annonce');
        $this->addSql('ALTER TABLE annonce DROP carburant_id, DROP modele_id, DROP garage_id');
        $this->addSql('ALTER TABLE garage DROP FOREIGN KEY FK_9F26610BA73F0036');
        $this->addSql('DROP INDEX IDX_9F26610BA73F0036 ON garage');
        $this->addSql('ALTER TABLE garage DROP ville_id');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_100285584827B9B2');
        $this->addSql('DROP INDEX IDX_100285584827B9B2 ON modele');
        $this->addSql('ALTER TABLE modele DROP marque_id');
    }
}
