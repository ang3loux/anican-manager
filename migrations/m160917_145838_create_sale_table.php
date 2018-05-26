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
            'customer' => $this->string()->defaultValue('')->notNull(),
            'date' => $this->date()->notNull(),
            'total' => $this->money()->defaultValue(0)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'updated_by' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('sale');
    }
}
