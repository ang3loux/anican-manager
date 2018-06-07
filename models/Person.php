<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "person".
 *
 * @property int $id
 * @property int $role
 * @property string $fullname
 * @property string $birthdate
 * @property string $birthplace
 * @property string $document
 * @property string $email
 * @property string $phone1
 * @property string $phone2
 * @property string $address
 * @property string $diagnosis
 * @property int $decease
 * @property string $deathdate
 * @property string $description
 * @property string $date
 * @property string $image
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 *
 * @property Relationship[] $patientRelationships
 * @property Relationship[] $personRelationships
 * @property Purchase[] $purchases
 * @property Sale[] $sales
 */
class Person extends \yii\db\ActiveRecord
{
    const SCENARIO_PERSON = 'person';
    const SCENARIO_PATIENT = 'patient';

    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'person';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['role', 'fullname', 'birthdate', 'document', 'email', 'phone1', 'address', 'date'], 'required', 'on' => self::SCENARIO_PERSON],
            [['role', 'fullname', 'birthdate', 'birthplace', 'email', 'phone1', 'address', 'diagnosis', 'decease', 'date'], 'required', 'on' => self::SCENARIO_PATIENT],
            [['role', 'decease', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['birthdate', 'deathdate', 'date'], 'safe'],
            [['birthplace', 'address', 'diagnosis', 'description'], 'string'],
            [['fullname', 'document', 'email', 'phone1', 'phone2', 'image'], 'string', 'max' => 255],
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
            'role' => 'Rol',
            'fullname' => 'Nombre completo',
            'birthdate' => 'Fecha de nacimiento',
            'birthplace' => 'Lugar de nacimiento',
            'document' => 'C.I.',
            'email' => 'Correo electrónico',
            'phone1' => 'Teléfono 1',
            'phone2' => 'Teléfono 2',
            'address' => 'Dirección',
            'diagnosis' => 'Diagnóstico',
            'decease' => '¿Paciente falleció?',
            'deathdate' => 'Fecha de fallecimiento',
            'description' => 'Descripción',
            'date' => 'Fecha de ingreso',
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
    public function getPatientRelationships()
    {
        return $this->hasMany(Relationship::className(), ['patient_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPersonRelationships()
    {
        return $this->hasMany(Relationship::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchases()
    {
        return $this->hasMany(Purchase::className(), ['person_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::className(), ['person_id' => 'id']);
    }

    public function uploadImage()
    {
        if ($this->validate()) {
            $path = Yii::$app->params['uploadPath']['persons'] . time() . '/';
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
        $path = substr($this->image, 0, 32);

        FileHelper::removeDirectory($path);
    }
}
