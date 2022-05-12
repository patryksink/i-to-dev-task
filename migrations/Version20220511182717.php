<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220511182717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Dropped user_login table add logged_at property for user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP TABLE user_login');
        $this->addSql('ALTER TABLE user ADD logged_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE user_login (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_48CA3048A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_login ADD CONSTRAINT FK_48CA3048A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `user` DROP logged_at');
    }
}
