<?php

namespace app\controllers;

use Yii;
use app\models\Sale;
use app\models\SaleSearch;
use app\models\SaleDetail;
use app\models\SaleDetailSearch;
use app\models\Item;
use app\models\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * SaleController implements the CRUD actions for Sale model.
 */
class SaleController extends Controller
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
     * Lists all Sale models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SaleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sale model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new SaleDetailSearch($id);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Sale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Sale();
        $modelDetails = null;

        if ($model->load(Yii::$app->request->post())) {
            $modelDetails = Model::createMultiple(SaleDetail::classname());
            Model::loadMultiple($modelDetails, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate() & Model::validateMultiple($modelDetails);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();

                try {
                    if ($flag = $model->save()) {
                        foreach ($modelDetails as $modelDetail) {
                            $modelDetail->sale_id = $model->id;
                            if ($flag = $modelDetail->save()) {
                                $item = Item::findOne($modelDetail->item_id);
                                $item->stock -= $modelDetail->quantity;
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
                        Yii::$app->getSession()->setFlash('success', 'Salida registrada <b>exitosamente</b>.');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelDetails' => empty($modelDetails) ? [new SaleDetail] : $modelDetails,
        ]);
    }

    /**
     * Updates an existing Sale model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelDetails = $model->getSaleDetails()->all();

        if ($model->load(Yii::$app->request->post())) {
            $oldModelDetails = array();
            foreach ($modelDetails as $modelDetail) {
                $oldModelDetails[$modelDetail->id] = [
                    'item_id' => $modelDetail->item_id,
                    'quantity' => $modelDetail->quantity
                ];
            }
            $oldIDs = ArrayHelper::map($modelDetails, 'id', 'id');
            $modelDetails = Model::createMultiple(SaleDetail::classname(), $modelDetails);
            Model::loadMultiple($modelDetails, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'id', 'id')));

            // validate all models
            $valid = $model->validate() & Model::validateMultiple($modelDetails);

            if ($valid) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save()) {
                        if (!empty($deletedIDs)) {
                            if ($flag = SaleDetail::deleteAll(['id' => $deletedIDs]) > 0) {
                                foreach ($deletedIDs as $id) {
                                    $item = Item::findOne($oldModelDetails[$id]['item_id']);
                                    $item->stock += $oldModelDetails[$id]['quantity'];
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
                                $modelDetail->sale_id = $model->id;
                                if (($flag = $modelDetail->save()) && $quantity !== 0) {
                                    $item = Item::findOne($modelDetail->item_id);
                                    $item->stock -= $quantity;
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
                        Yii::$app->getSession()->setFlash('success', 'Salida actualizada <b>exitosamente</b>.');
                        return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelDetails' => empty($modelDetails) ? [new SaleDetail] : $modelDetails,
        ]);
    }

    /**
     * Deletes an existing Sale model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelDetails = $model->getSaleDetails()->all();
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
                    Yii::$app->getSession()->setFlash('danger', 'Salida <b>eliminada</b>.');
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
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sale::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
