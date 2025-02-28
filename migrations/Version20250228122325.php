<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250228122325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revenue (id INT AUTO_INCREMENT NOT NULL, revenue_category_id INT NOT NULL, account_id INT NOT NULL, name VARCHAR(50) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, operation_date DATE NOT NULL, checked_date DATE DEFAULT NULL, INDEX IDX_E9116C85B0A8EE75 (revenue_category_id), INDEX IDX_E9116C859B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE revenue_category (id INT AUTO_INCREMENT NOT NULL, revenue_category_id INT DEFAULT NULL, INDEX IDX_73E87165B0A8EE75 (revenue_category_id), name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE revenue ADD CONSTRAINT FK_E9116C85B0A8EE75 FOREIGN KEY (revenue_category_id) REFERENCES revenue_category (id)');
        $this->addSql('ALTER TABLE revenue ADD CONSTRAINT FK_E9116C859B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
        $this->addSql('ALTER TABLE revenue_category ADD CONSTRAINT FK_73E87165B0A8EE75 FOREIGN KEY (revenue_category_id) REFERENCES revenue_category (id)');
        $this->addSql("INSERT INTO `finper`.`account` (`id`, `name`) VALUES 
			(1, 'Bank 1'),
			(2, 'Bank 2');");
	    $this->addSql("INSERT INTO `finper`.`revenue_category` (`id`, `revenue_category_id`, `name`) VALUES 
			(1, NULL, 'Habituales'),
			(2, NULL, 'Puntuales'),
			(3, 1, 'Nómina'),
			(4, 2, 'IRPF'),
			(5, 1, 'Renta');");
	    $this->addSql("INSERT INTO `finper`.`revenue` (`id`, `revenue_category_id`, `account_id`, `name`, `amount`, `operation_date`, `checked_date`) VALUES 
			(1, 5, 2, 'Enero', 50, '2025-01-02', '2025-01-02'),
			(2, 3, 1, 'Enero', 980, '2025-01-31', '2025-01-31'),
			(3, 5, 2, 'Febrero', 50, '2025-02-02', '2025-02-02'),
			(4, 4, 1, 'Devolución', 15, '2025-02-15', '2025-02-15'),
			(5, 3, 1, 'Febrero', 950, '2025-02-02', '2025-02-02');");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE revenue DROP FOREIGN KEY FK_E9116C85B0A8EE75');
        $this->addSql('ALTER TABLE revenue DROP FOREIGN KEY FK_E9116C859B6B5FBA');
        $this->addSql('ALTER TABLE revenue_category DROP FOREIGN KEY FK_73E87165B0A8EE75');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE revenue');
        $this->addSql('DROP TABLE revenue_category');
    }
}
