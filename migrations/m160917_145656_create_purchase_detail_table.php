<?php

use yii\db\Migration;

/**
 * Handles the creation for table `purchase_detail`.
 */
class m160917_145656_create_purchase_detail_table extends Migration
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
        $this->createTable('purchase_detail', [
            'id' => $this->primaryKey(),
            'purchase_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull(),
            'price' => $this->money()->defaultValue(0)->notNull()
        ], $tableOptions);

        $this->createIndex(
            'idx-purchase_detail-purchase_id',
            'purchase_detail',
            'purchase_id'
        );
        $this->addForeignKey(
            'fk-purchase_detail-purchase_id',
            'purchase_detail',
            'purchase_id',
            'purchase',
            'id',
            'CASCADE'
        );

        $this->createIndex(
            'idx-purchase_detail-item_id',
            'purchase_detail',
            'item_id'
        );
        $this->addForeignKey(
            'fk-purchase_detail-item_id',
            'purchase_detail',
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
        $this->dropTable('purchase_detail');
    }
}
