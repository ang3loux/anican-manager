<?php

namespace app\controllers;

use Yii;
use app\models\Purchase;
use app\models\PurchaseSearch;
use app\models\PurchaseDetail;
use app\models\PurchaseDetailSearch;
use app\models\Item;
use app\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Purchase models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PurchaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Purchase model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new PurchaseDetailSearch($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Purchase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Purchase();
        $modelDetails = null;
        
        if ($model->load(Yii::$app->request->post())) {
            $modelDetails = Model::createMultiple(PurchaseDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate() & Model::validateMultiple($modelDetails);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save()) {
                        foreach ($modelDetails as $modelDetail) {
                            $modelDetail->purchase_id = $model->id;
                            if ($flag = $modelDetail->save()) {
                                $item = Item::findOne($modelDetail->item_id);
                                $item->stock += $modelDetail->quantity;
                                $flag = $item->save();
                            }
                            if (!$flag) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }

                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('success', 'Entrada registrada <b>exitosamente</b>.');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelDetails' => empty($modelDetails) ? [new PurchaseDetail] : $modelDetails,
        ]);
    }

    /**
     * Updates an existing Purchase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelDetails = $model->getPurchaseDetails()->all();

        if ($model->load(Yii::$app->request->post())) {
            $oldModelDetails = array();
            foreach ($modelDetails as $modelDetail) {
                $oldModelDetails[$modelDetail->id] = [
                    'item_id' => $modelDetail->item_id,
                    'quantity' => $modelDetail->quantity
                ];
            }
            $oldIDs = ArrayHelper::map($modelDetails, 'id', 'id');
            $modelDetails = Model::createMultiple(PurchaseDetail::classname(), $modelDetails);
            Model::loadMultiple($modelDetails, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'id', 'id')));

            // validate all models
            $valid = $model->validate() & Model::validateMultiple($modelDetails);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        if (!empty($deletedIDs)) {
                            $flag = PurchaseDetail::deleteAll(['id' => $deletedIDs]) > 0;
                            if ($flag) {
                                foreach ($deletedIDs as $id) {
                                    $item = Item::findOne($oldModelDetails[$id]['item_id']);
                                    $item->stock -= $oldModelDetails[$id]['quantity'];
                                    if (!($flag = $item->save())) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            } else {
                                $transaction->rollBack();
                            }
                        }
                        if ($flag) {
                            foreach ($modelDetails as $modelDetail) {
                                $quantity = $modelDetail->quantity;
                                if (!empty($modelDetail->id) && $modelDetail->item_id == $oldModelDetails[$modelDetail->id]['item_id']) {
                                    $quantity -= $oldModelDetails[$modelDetail->id]['quantity'];
                                }
                                $modelDetail->purchase_id = $model->id;
                                if (($flag = $modelDetail->save()) && $quantity !== 0) {
                                    $item = Item::findOne($modelDetail->item_id);
                                    $item->stock += $quantity;
                                    $flag = $item->save();
                                }
                                if (!$flag) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                        }
                    }
                    if ($flag) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('success', 'Entrada actualizada <b>exitosamente</b>.');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelDetails' => empty($modelDetails) ? [new PurchaseDetail] : $modelDetails,
        ]);
    }

    /**
     * Deletes an existing Purchase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelDetails = $model->getPurchaseDetails()->all();
        $transaction = \Yii::$app->db->beginTransaction();
        try {
            foreach ($modelDetails as $modelDetail) {
                $item = Item::findOne($modelDetail->item_id);
                $item->stock -= $modelDetail->quantity;
                if (!($flag = $item->save())) {
                    $transaction->rollBack();
                    break;
                }
            }
            if ($flag) {
                if ($model->delete()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('danger', 'Entrada <b>eliminada</b>.');
                } else {
                    $transaction->rollBack();
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Purchase model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Purchase the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Purchase::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
