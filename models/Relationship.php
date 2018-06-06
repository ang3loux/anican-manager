<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "relationship".
 *
 * @property int $id
 * @property int $patient_id
 * @property int $person_id
 * @property int $relationship
 * @property string $description
 *
 * @property Person $patient
 * @property Person $person
 */
class Relationship extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'relationship';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['person_id', 'relationship'], 'required'],
            [['patient_id', 'person_id', 'relationship'], 'integer'],
            [['description'], 'string'],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['patient_id' => 'id']],
            [['person_id'], 'exist', 'skipOnError' => true, 'targetClass' => Person::className(), 'targetAttribute' => ['person_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Paciente',
            'person_id' => 'Responsable',
            'relationship' => 'Relación',
            'description' => 'Descripción',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPatient()
    {
        return $this->hasOne(Person::className(), ['id' => 'patient_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPerson()
    {
        return $this->hasOne(Person::className(), ['id' => 'person_id']);
    }
}
