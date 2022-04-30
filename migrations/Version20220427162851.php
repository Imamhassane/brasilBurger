<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220427162851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE burger (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, menus_id INT DEFAULT NULL, burger_id INT DEFAULT NULL, date DATE NOT NULL, etat VARCHAR(255) NOT NULL, montant INT NOT NULL, date_livraison DATE NOT NULL, INDEX IDX_6EEAA67D19EB6921 (client_id), INDEX IDX_6EEAA67D14041B84 (menus_id), INDEX IDX_6EEAA67D17CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE complement (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prix INT NOT NULL, UNIQUE INDEX UNIQ_F8A41E343DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, burger_id INT NOT NULL, nom VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C53D045F17CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, burger_id INT NOT NULL, nom VARCHAR(255) NOT NULL, prix INT NOT NULL, UNIQUE INDEX UNIQ_7D053A933DA5256D (image_id), INDEX IDX_7D053A9317CE5090 (burger_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_complement (menu_id INT NOT NULL, complement_id INT NOT NULL, INDEX IDX_D909AAE6CCD7E912 (menu_id), INDEX IDX_D909AAE640D9D0AA (complement_id), PRIMARY KEY(menu_id, complement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, commande_id INT DEFAULT NULL, montant VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B1DC7A1E82EA2E54 (commande_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D14041B84 FOREIGN KEY (menus_id) REFERENCES menu (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D17CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE complement ADD CONSTRAINT FK_F8A41E343DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F17CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A933DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9317CE5090 FOREIGN KEY (burger_id) REFERENCES burger (id)');
        $this->addSql('ALTER TABLE menu_complement ADD CONSTRAINT FK_D909AAE6CCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_complement ADD CONSTRAINT FK_D909AAE640D9D0AA FOREIGN KEY (complement_id) REFERENCES complement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E82EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D17CE5090');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F17CE5090');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9317CE5090');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E82EA2E54');
        $this->addSql('ALTER TABLE menu_complement DROP FOREIGN KEY FK_D909AAE640D9D0AA');
        $this->addSql('ALTER TABLE complement DROP FOREIGN KEY FK_F8A41E343DA5256D');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A933DA5256D');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D14041B84');
        $this->addSql('ALTER TABLE menu_complement DROP FOREIGN KEY FK_D909AAE6CCD7E912');
        $this->addSql('DROP TABLE burger');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE complement');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_complement');
        $this->addSql('DROP TABLE paiement');
    }
}
