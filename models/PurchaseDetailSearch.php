<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PurchaseDetail;

/**
 * PurchaseDetailSearch represents the model behind the search form of `app\models\PurchaseDetail`.
 */
class PurchaseDetailSearch extends PurchaseDetail
{
    public $reason;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'quantity', 'reason'], 'integer'],
            [['purchase_id', 'item_id', 'expiration', 'currency', 'description'], 'safe'],
            [['price'], 'number'],
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
    public function search($params, $purchaseID)
    {
        $query = PurchaseDetail::find();

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
            'purchase_id' => $purchaseID,
            // 'item_id' => $this->item_id,
            'expiration' => $this->expiration,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ]);

        $query->andFilterWhere(['like', 'item.name', $this->item_id])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'purchase_detail.description', $this->description]);

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
        $query = PurchaseDetail::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['attributes' => ['purchase_id', 'expiration', 'quantity', 'reason']]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('purchase');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            // 'purchase_id' => $this->purchase_id,
            'item_id' => $itemID,
            'expiration' => $this->expiration,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'purchase.reason' => $this->reason,
        ]);

        $query->andFilterWhere(['like', 'purchase.date', $this->purchase_id])
            ->andFilterWhere(['like', 'currency', $this->currency])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
