<?php

use yii\db\Migration;

/**
 * Handles the creation for table `item`.
 */
class m160917_142937_create_item_table extends Migration
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
        $this->createTable('item', [
            'id' => $this->primaryKey(),
            'code' => $this->string()->defaultValue('vacio')->notNull(),
            'name' => $this->string()->notNull(),
            'unit' => $this->string()->defaultValue('unidad')->notNull(),
            'quantity' => $this->integer()->defaultValue(0)->notNull(),
            'stock' => $this->integer()->defaultValue(0)->notNull(),
            'price' => $this->money()->defaultValue(0)->notNull(),
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
        $this->dropTable('item');
    }
}