<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220520222418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quantity_burger (quantity_id INT NOT NULL, burger_id INT NOT NULL, INDEX IDX_E25B171F7E8B4AFC (quantity_id), INDEX IDX_E25B171F17CE5090 (burger_id), PRIMARY KEY(quantity_id, burger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quantity_commande (quantity_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_1E73E63B7E8B4AFC (quantity_id), INDEX IDX_1E73E63B82EA2E54 (commande_id), PRIMARY KEY(quantity_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quantity_burger ADD CONSTRAINT FK_E25B171F7E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_burger ADD CONSTRAINT FK_E25B171F17CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_commande ADD CONSTRAINT FK_1E73E63B7E8B4AFC FOREIGN KEY (quantity_id) REFERENCES quantity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantity_commande ADD CONSTRAINT FK_1E73E63B82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE quantity_burger');
        $this->addSql('DROP TABLE quantity_commande');
    }
}
