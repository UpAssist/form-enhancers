<?php
namespace Neos\Flow\Persistence\Doctrine\Migrations;

use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs! This block will be used as the migration description if getDescription() is not used.
 */
class Version20170815125509 extends AbstractMigration
{

    /**
     * @return string
     */
    public function getDescription(): string 
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void 
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('CREATE TABLE upassist_formenhancers_domain_model_formentry (persistence_object_identifier VARCHAR(40) NOT NULL, formidentifier VARCHAR(255) NOT NULL, formlabel VARCHAR(255) NOT NULL, creationdatetime DATETIME NOT NULL, formvalues LONGTEXT NOT NULL COMMENT \'(DC2Type:flow_json_array)\', PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void 
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on "mysql".');
        
        $this->addSql('DROP TABLE upassist_formenhancers_domain_model_formentry');
    }
}
