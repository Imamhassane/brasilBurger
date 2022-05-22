<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520222316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity DROP FOREIGN KEY FK_9FF3163682EA2E54');
        $this->addSql('ALTER TABLE quantity DROP FOREIGN KEY FK_9FF3163617CE5090');
        $this->addSql('DROP INDEX IDX_9FF3163682EA2E54 ON quantity');
        $this->addSql('DROP INDEX IDX_9FF3163617CE5090 ON quantity');
        $this->addSql('ALTER TABLE quantity DROP burger_id, DROP commande_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantity ADD burger_id INT DEFAULT NULL, ADD commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF3163682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE quantity ADD CONSTRAINT FK_9FF3163617CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('CREATE INDEX IDX_9FF3163682EA2E54 ON quantity (commande_id)');
        $this->addSql('CREATE INDEX IDX_9FF3163617CE5090 ON quantity (burger_id)');
    }
}
