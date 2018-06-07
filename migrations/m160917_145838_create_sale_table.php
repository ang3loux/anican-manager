<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sale`.
 */
class m160917_145838_create_sale_table extends Migration
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
        $this->createTable('sale', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->defaultValue('')->notNull(),
            'reason' => $this->integer(1)->defaultValue(0)->notNull(),
            'person_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-sale-person_id',
            'sale',
            'person_id'
        );
        $this->addForeignKey(
            'fk-sale-person_id',
            'sale',
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
        $this->dropTable('sale');
    }
}
