<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Person;

/**
 * PersonSearch represents the model behind the search form of `app\models\Person`.
 */
class PersonSearch extends Person
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'role', 'decease', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['fullname', 'birthdate', 'birthplace', 'document', 'email', 'phone1', 'phone2', 'address', 'diagnosis', 'deathdate', 'description', 'date', 'image'], 'safe'],
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
    public function search($params, $personRole)
    {
        $query = Person::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'role' => [$personRole, $personRole == 0 ? 2 : $personRole],
            'birthdate' => $this->birthdate,
            'decease' => $this->decease,
            'deathdate' => $this->deathdate,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'fullname', $this->fullname])
            ->andFilterWhere(['like', 'birthplace', $this->birthplace])
            ->andFilterWhere(['like', 'document', $this->document])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone1', $this->phone1])
            ->andFilterWhere(['like', 'phone2', $this->phone2])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'diagnosis', $this->diagnosis])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image]);

        return $dataProvider;
    }
}
