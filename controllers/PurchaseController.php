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
        $searchModel = new PurchaseDetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

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
            $valid = $model->validate() && Model::validateMultiple($modelDetails);

            if ($valid) {
                foreach ($modelDetails as $index => $modelDetail) {                    
                    foreach ($modelDetails as $_index => $_modelDetail) {
                        if ($modelDetail->item_id == $_modelDetail->item_id &&
                            $index != $_index
                        ) {
                            $valid = false;
                            Yii::$app->getSession()->setFlash('error', 'El item "' . $modelDetail->item->name . '" <b>se encuentra repetido</b>.');
                            break 2;
                        }
                    }
                }
                
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
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
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
            foreach ($modelDetails as $index => $modelDetail) {
                $oldModelDetails[$index] = [
                    'id' => $modelDetail->id,
                    'item_id' => $modelDetail->item_id,
                    'quantity' => $modelDetail->quantity
                ];
            }
            $oldIDs = ArrayHelper::map($modelDetails, 'id', 'id');

            $modelDetails = Model::createMultiple(PurchaseDetail::classname(), $modelDetails);
            Model::loadMultiple($modelDetails, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelDetails, 'id', 'id')));

            // validate all models
            $valid = $model->validate() && Model::validateMultiple($modelDetails);

            if ($valid) {
                foreach ($modelDetails as $index => $modelDetail) {
                    foreach ($modelDetails as $_index => $_modelDetail) {
                        if ($modelDetail->item_id == $_modelDetail->item_id &&
                            $index != $_index
                        ) {
                            $valid = false;
                            Yii::$app->getSession()->setFlash('error', 'El item "' . $modelDetail->item->name . '" <b>se encuentra repetido</b>.');
                            break 2;
                        }
                    }
                }

                $modelItems = array();
                if ($valid) {
                    foreach ($oldModelDetails as $oldModelDetail) {
                        $item = Item::findOne($oldModelDetail['item_id']);
                        $item->stock -= $oldModelDetail['quantity'];
                        array_push($modelItems, $item);
                    }
                    foreach ($modelDetails as $modelDetail) {
                        $item = null;
                        foreach ($modelItems as $modelItem) {
                            if ($modelItem->id == $modelDetail->item_id) {
                                $item = $modelItem;
                                $item->stock += $modelDetail->quantity;
                                break;
                            }
                        }
                        if (empty($item)) {
                            $item = Item::findOne($modelDetail->item_id);
                            $item->stock += $modelDetail->quantity;
                            array_push($modelItems, $item);
                        }
                    }
                    foreach ($modelItems as $modelItem) {
                        if ($modelItem->stock < 0) {
                            $valid = false;
                            Yii::$app->getSession()->setFlash('error', 'Stock del item "' . $modelItem->name . '" <b>no puede ser negativo</b>.');
                            break;
                        }
                    }
                }

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    try {
                        if ($flag = $model->save()) {
                            PurchaseDetail::deleteAll(['id' => $deletedIDs]);
                            foreach ($modelDetails as $modelDetail) {
                                $modelDetail->purchase_id = $model->id;
                                $flag = $modelDetail->save();
                                if (!$flag) {
                                    $transaction->rollBack();
                                    break;
                                }
                            }
                            
                            if ($flag) {
                                foreach ($modelItems as $modelItem) {
                                    $flag = $modelItem->save();
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
                            return $this->redirect(['view', 'id' => $model->id]);
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
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
        $modelItems = array();
        $valid = true;

        foreach ($modelDetails as $modelDetail) {
            $item = Item::findOne($modelDetail->item_id);
            $item->stock -= $modelDetail->quantity;
            if ($item->stock < 0) {
                $valid = false;
                Yii::$app->getSession()->setFlash('error', 'Stock del item "' . $item->name . '" <b>no puede ser negativo</b>.');
                break;
            }
            array_push($modelItems, $item);
        }

        if ($valid) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                foreach ($modelItems as $modelItem) {
                    if (!($flag = $modelItem->save())) {
                        $transaction->rollBack();
                        break;
                    }
                }
                if ($flag) {
                    if ($model->delete()) {
                        $transaction->commit();
                        Yii::$app->getSession()->setFlash('success', 'Entrada eliminada <b>exitosamente</b>.');
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->redirect(['view', 'id' => $id]);
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
