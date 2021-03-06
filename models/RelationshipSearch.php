<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Relationship;

/**
 * RelationshipSearch represents the model behind the search form of `app\models\Relationship`.
 */
class RelationshipSearch extends Relationship
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'relationship'], 'integer'],
            [['patient_id', 'person_id', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByPerson($params, $personID)
    {
        $query = Relationship::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('person');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'patient_id' => $this->patient_id,
            'person_id' => $personID,
            'relationship' => $this->relationship,
        ]);

        $query->andFilterWhere(['like', 'person.fullname', $this->patient_id])
            ->andFilterWhere(['like', 'relationship.description', $this->description]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByPatient($params, $patientID)
    {
        $query = Relationship::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('person');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'patient_id' => $patientID,
            // 'person_id' => $this->person_id,
            'relationship' => $this->relationship,
        ]);

        $query->andFilterWhere(['like', 'person.fullname', $this->person_id])
            ->andFilterWhere(['like', 'relationship.description', $this->description]);

        return $dataProvider;
    }
}
