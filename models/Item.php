<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $unit
 * @property int $quantity
 * @property int $stock
 * @property string $price
 * @property string $image
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property PurchaseDetail[] $purchaseDetails
 * @property SaleDetail[] $saleDetails
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'unit', 'quantity', 'stock', 'price'], 'required'],
            [['quantity', 'stock', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['price'], 'number'],
            [['code', 'name', 'unit','image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
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
            'name' => 'Nombre',
            'unit' => 'Unidad  de medida',
            'quantity' => 'Stock inicial',
            'stock' => 'Stock',
            'price' => 'Precio',
            'image' => 'Imagen',
            'imageFile' => 'Imagen',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::className(), ['item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSaleDetails()
    {
        return $this->hasMany(SaleDetail::className(), ['item_id' => 'id']);
    }

    public function uploadImage()
    {
        if ($this->validate()) {
            $path = 'uploads/item-images/' . time() . '/';
            $baseName = str_replace([' ', '.'], '', $this->imageFile->baseName);
            $extension = $this->imageFile->extension;
            $imagePath = $path . $baseName . '.' . $extension;

            $this->image = $imagePath;
            FileHelper::createDirectory($path);
            $this->imageFile->saveAs($imagePath);
            return true;
        } else {
            return false;
        }
    }

    public function deleteImage()
    {
        $path = substr($this->image, 0, 30);

        FileHelper::removeDirectory($path);
    }
}
