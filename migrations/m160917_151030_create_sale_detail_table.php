<?php

use yii\db\Migration;

/**
 * Handles the creation for table `sale_detail`.
 */
class m160917_151030_create_sale_detail_table extends Migration
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
        $this->createTable('sale_detail', [
            'id' => $this->primaryKey(),
            'sale_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'description' => $this->text(),
        ], $tableOptions);

        $this->createIndex(
            'idx-sale_detail-sale_id',
            'sale_detail',
            'sale_id'
        );
        $this->addForeignKey(
            'fk-sale_detail-sale_id',
            'sale_detail',
            'sale_id',
            'sale',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-sale_detail-item_id',
            'sale_detail',
            'item_id'
        );
        $this->addForeignKey(
            'fk-sale_detail-item_id',
            'sale_detail',
            'item_id',
            'item',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('sale_detail');
    }
}
