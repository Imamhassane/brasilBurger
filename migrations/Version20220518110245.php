<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220518110245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quantite DROP FOREIGN KEY FK_8BF24A79E1B3E0AC');
        $this->addSql('DROP TABLE burger_commande');
        $this->addSql('DROP TABLE burger_commande_burger');
        $this->addSql('DROP TABLE quantite');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE burger_commande (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, INDEX IDX_A0D9FE9982EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE burger_commande_burger (burger_commande_id INT NOT NULL, burger_id INT NOT NULL, INDEX IDX_8A96F225E1B3E0AC (burger_commande_id), INDEX IDX_8A96F22517CE5090 (burger_id), PRIMARY KEY(burger_commande_id, burger_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quantite (id INT AUTO_INCREMENT NOT NULL, burger_id INT DEFAULT NULL, burger_commande_id INT DEFAULT NULL, quantite INT NOT NULL, INDEX IDX_8BF24A7917CE5090 (burger_id), INDEX IDX_8BF24A79E1B3E0AC (burger_commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE burger_commande ADD CONSTRAINT FK_A0D9FE9982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE burger_commande_burger ADD CONSTRAINT FK_8A96F22517CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A79E1B3E0AC FOREIGN KEY (burger_commande_id) REFERENCES burger_commande (id)');
        $this->addSql('ALTER TABLE quantite ADD CONSTRAINT FK_8BF24A7917CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
    }
}
