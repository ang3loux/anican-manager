<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "purchase_detail".
 *
 * @property int $id
 * @property int $purchase_id
 * @property int $item_id
 * @property string $expiration
 * @property int $quantity
 * @property string $price
 * @property string $currency
 * @property string $description
 *
 * @property Item $item
 * @property Purchase $purchase
 */
class PurchaseDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase_detail';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'quantity', 'price', 'currency'], 'required'],
            [['purchase_id', 'item_id', 'quantity'], 'integer'],
            [['expiration'], 'safe'],
            [['price'], 'number'],            
            [['currency'], 'string', 'max' => 3],
            [['description'], 'string'], 
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => Item::className(), 'targetAttribute' => ['item_id' => 'id']],
            [['purchase_id'], 'exist', 'skipOnError' => true, 'targetClass' => Purchase::className(), 'targetAttribute' => ['purchase_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_id' => 'Entrada',
            'item_id' => 'Item',
            'expiration' => 'Fecha de vencimiento',
            'quantity' => 'Cantidad',
            'price' => 'Precio',
            'currency' => 'Moneda',
            'description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::className(), ['id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase()
    {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }
}
