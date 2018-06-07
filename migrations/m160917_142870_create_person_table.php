<?php

use yii\db\Migration;

/**
 * Handles the creation for table `person`.
 */
class m160917_142870_create_person_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('person', [
            'id' => $this->primaryKey(),
            'role' => $this->integer(1)->defaultValue(0)->notNull(),
            'fullname' => $this->string()->notNull(),
            'birthdate' => $this->date()->notNull(),
            'birthplace' => $this->text(),
            'document' => $this->string(),
            'email' => $this->string(),            
            'phone1' => $this->string(),
            'phone2' => $this->string(),
            'address' => $this->text()->notNull(),            
            'diagnosis' => $this->text(),
            'decease' => $this->integer(1)->defaultValue(0)->notNull(),
            'deathdate' => $this->date(),
            'description' => $this->text(),
            'date' => $this->date()->notNull(),
            'image' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->batchInsert('person',
            ['role', 'fullname', 'birthdate', 'document', 'email', 'phone1', 'address', 'decease', 'date', 'image', 'created_at', 'created_by', 'updated_at', 'updated_by'], 
            [['2', 'Anónimo', '2018-01-01', '-', '-', '-', '-', '0', '2018-01-01', 'images/anonym.png', '1514764800', '1', '1514764800', '1']]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('person');
    }
}
