<?php

use yii\db\Migration;

/**
 * Handles the creation for table `relationship`.
 */
class m160917_142906_create_relationship_table extends Migration
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
        $this->createTable('relationship', [
            'id' => $this->primaryKey(),
            'patient_id' => $this->integer()->notNull(),
            'person_id' => $this->integer()->notNull(),
            'relationship' => $this->integer(1)->defaultValue(0)->notNull(),
            'description' => $this->text(),

        ], $tableOptions);

        $this->createIndex(
            'idx-relationship-patient_id',
            'relationship',
            'patient_id'
        );
        $this->addForeignKey(
            'fk-relationship-patient_id',
            'relationship',
            'patient_id',
            'person',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-relationship-person_id',
            'relationship',
            'person_id'
        );
        $this->addForeignKey(
            'fk-relationship-person_id',
            'relationship',
            'person_id',
            'person',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('relationship');
    }
}
