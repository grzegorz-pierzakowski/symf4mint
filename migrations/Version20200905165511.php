<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200905165511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        for ($i = 0; $i < 15; $i++) {
            $name = 'user' . $i;
            $this->addSql("INSERT INTO user (email, username, password, roles, is_enabled, created_at, updated_at) 
VALUES ('$name@mint-net.pl','$name', '\$2y\$13\$3V1JUgh2aEXhpZSiG4wKz.PMMU8/JZPtem7IhC7awSwzkBPnuNrD6', 'a:1:{i:0;s:9:\"ROLE_USER\";}', 1, now(), now())");
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM user;');
    }
}
