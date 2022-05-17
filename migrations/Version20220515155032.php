<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220515155032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE burger_commande (burger_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_A0D9FE9917CE5090 (burger_id), INDEX IDX_A0D9FE9982EA2E54 (commande_id), PRIMARY KEY(burger_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_commande (menu_id INT NOT NULL, commande_id INT NOT NULL, INDEX IDX_42BBE3EBCCD7E912 (menu_id), INDEX IDX_42BBE3EB82EA2E54 (commande_id), PRIMARY KEY(menu_id, commande_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE burger_commande ADD CONSTRAINT FK_A0D9FE9917CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE burger_commande ADD CONSTRAINT FK_A0D9FE9982EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_commande ADD CONSTRAINT FK_42BBE3EBCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_commande ADD CONSTRAINT FK_42BBE3EB82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE burger_commande');
        $this->addSql('DROP TABLE menu_commande');
    }
}
