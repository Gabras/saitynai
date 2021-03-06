<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201005121643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_cart DROP FOREIGN KEY FK_5F322CC01AD5CDBF');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE customer_cart');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INT AUTO_INCREMENT NOT NULL, ship_id INT DEFAULT NULL, date VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_BA388B7C256317D (ship_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE customer_cart (customer_id INT NOT NULL, cart_id INT NOT NULL, INDEX IDX_5F322CC01AD5CDBF (cart_id), INDEX IDX_5F322CC09395C3F3 (customer_id), PRIMARY KEY(customer_id, cart_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7C256317D FOREIGN KEY (ship_id) REFERENCES ship (id)');
        $this->addSql('ALTER TABLE customer_cart ADD CONSTRAINT FK_5F322CC01AD5CDBF FOREIGN KEY (cart_id) REFERENCES cart (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_cart ADD CONSTRAINT FK_5F322CC09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
    }
}
