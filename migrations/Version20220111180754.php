<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220111180754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add answer status';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE answer ADD status VARCHAR(15) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE answer DROP status');
    }
}
