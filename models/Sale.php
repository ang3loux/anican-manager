<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property string $code
 * @property int $reason
 * @property int $person_id
 * @property string $date
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Person $person
 * @property SaleDetail[] $saleDetails
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'reason', 'person_id', 'date'], 'required'],
            [['code'], 'string', 'max' => 255],
            [['reason', 'person_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['date'], 'safe'],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            BlameableBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Código',
            'reason' => 'Razón',
            'person_id' => 'Responsable',
            'date' => 'Fecha de salida',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getPerson() 
    { 
        return $this->hasOne(Person::className(), ['id' => 'person_id']); 
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleDetails()
    {
        return $this->hasMany(SaleDetail::className(), ['sale_id' => 'id']);
    }
}
