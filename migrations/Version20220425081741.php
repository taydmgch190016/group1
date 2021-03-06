<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425081741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classroom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom_student (classroom_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_3DD26E1B6278D5A8 (classroom_id), INDEX IDX_3DD26E1BCB944F1A (student_id), PRIMARY KEY(classroom_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classroom_course (classroom_id INT NOT NULL, course_id INT NOT NULL, INDEX IDX_8296ABA26278D5A8 (classroom_id), INDEX IDX_8296ABA2591CC992 (course_id), PRIMARY KEY(classroom_id, course_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, birthday DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE classroom_student ADD CONSTRAINT FK_3DD26E1B6278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classroom_student ADD CONSTRAINT FK_3DD26E1BCB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classroom_course ADD CONSTRAINT FK_8296ABA26278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classroom_course ADD CONSTRAINT FK_8296ABA2591CC992 FOREIGN KEY (course_id) REFERENCES course (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE classroom_student DROP FOREIGN KEY FK_3DD26E1B6278D5A8');
        $this->addSql('ALTER TABLE classroom_course DROP FOREIGN KEY FK_8296ABA26278D5A8');
        $this->addSql('ALTER TABLE classroom_student DROP FOREIGN KEY FK_3DD26E1BCB944F1A');
        $this->addSql('DROP TABLE classroom');
        $this->addSql('DROP TABLE classroom_student');
        $this->addSql('DROP TABLE classroom_course');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE user');
    }
}
