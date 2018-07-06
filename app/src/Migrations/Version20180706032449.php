<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180706032449 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, name VARCHAR(255) NOT NULL, summary LONGTEXT DEFAULT NULL, discussion LONGTEXT DEFAULT NULL, INDEX IDX_C242628166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_images (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(512) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module_images_module (module_images_id INT NOT NULL, module_id INT NOT NULL, INDEX IDX_9D0521C68E2E1A2D (module_images_id), INDEX IDX_9D0521C6AFC2B591 (module_id), PRIMARY KEY(module_images_id, module_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE module_images_module ADD CONSTRAINT FK_9D0521C68E2E1A2D FOREIGN KEY (module_images_id) REFERENCES module_images (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE module_images_module ADD CONSTRAINT FK_9D0521C6AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE module_images_module DROP FOREIGN KEY FK_9D0521C6AFC2B591');
        $this->addSql('ALTER TABLE module_images_module DROP FOREIGN KEY FK_9D0521C68E2E1A2D');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628166D1F9C');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE module_images');
        $this->addSql('DROP TABLE module_images_module');
        $this->addSql('DROP TABLE project');
    }
}
