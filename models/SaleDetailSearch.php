<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\SaleDetail;

/**
 * SaleDetailSearch represents the model behind the search form of `app\models\SaleDetail`.
 */
class SaleDetailSearch extends SaleDetail
{
    public $reason;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'reason'], 'integer'],
            [['sale_id', 'item_id', 'description'], 'safe'],
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
    public function search($params, $saleID)
    {
        $query = SaleDetail::find();

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

        $query->joinWith('item');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sale_id' => $saleID,
            // 'item_id' => $this->item_id,
            'quantity' => $this->quantity,
        ]);

        $query->andFilterWhere(['like', 'item.name', $this->item_id])
            ->andFilterWhere(['like', 'sale_detail.description', $this->description]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchByItem($params, $itemID)
    {
        $query = SaleDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['attributes' => ['sale_id', 'quantity', 'reason']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('sale');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'sale_id' => $this->sale_id,
            'item_id' => $itemID,
            'quantity' => $this->quantity,
            'sale.reason' => $this->reason,
        ]);

        $query->andFilterWhere(['like', 'sale.date', $this->sale_id])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
