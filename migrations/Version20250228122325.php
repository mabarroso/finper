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
		$this->addSql('ALTER TABLE account ADD iban VARCHAR(24) DEFAULT NULL');
		$this->addSql('CREATE TABLE revenue (id INT AUTO_INCREMENT NOT NULL, revenue_category_id INT NOT NULL, account_id INT NOT NULL, name VARCHAR(50) DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, operation_date DATE NOT NULL, checked_date DATE DEFAULT NULL, INDEX IDX_E9116C85B0A8EE75 (revenue_category_id), INDEX IDX_E9116C859B6B5FBA (account_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('CREATE TABLE revenue_category (id INT AUTO_INCREMENT NOT NULL, revenue_category_id INT DEFAULT NULL, INDEX IDX_73E87165B0A8EE75 (revenue_category_id), name VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
		$this->addSql('ALTER TABLE revenue ADD CONSTRAINT FK_E9116C85B0A8EE75 FOREIGN KEY (revenue_category_id) REFERENCES revenue_category (id)');
		$this->addSql('ALTER TABLE revenue ADD CONSTRAINT FK_E9116C859B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id)');
		$this->addSql('ALTER TABLE revenue_category ADD CONSTRAINT FK_73E87165B0A8EE75 FOREIGN KEY (revenue_category_id) REFERENCES revenue_category (id)');
		$this->addSql('ALTER TABLE revenue_category CHANGE name name VARCHAR(50) DEFAULT NULL');
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
