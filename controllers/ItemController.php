<?php

namespace app\controllers;

use Yii;
use app\models\Item;
use app\models\ItemSearch;
use app\models\Purchase;
use app\models\PurchaseDetailSearch;
use app\models\Sale;
use app\models\SaleDetailSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
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
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModelPurchase = new PurchaseDetailSearch();
        $dataProviderPurchase = $searchModelPurchase->searchByItem(Yii::$app->request->queryParams, $id);
        $dataProviderPurchase->pagination->pageParam = "page-purchase";
        $dataProviderPurchase->sort->sortParam = "sort-purchase";

        $searchModelSale = new SaleDetailSearch();
        $dataProviderSale = $searchModelSale->searchByItem(Yii::$app->request->queryParams, $id);
        $dataProviderSale->pagination->pageParam = "page-sale";
        $dataProviderSale->sort->sortParam = "sort-sale";

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModelPurchase' => $searchModelPurchase,
            'dataProviderPurchase' => $dataProviderPurchase,
            'searchModelSale' => $searchModelSale,
            'dataProviderSale' => $dataProviderSale,
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post())) {
            $model->stock = 0;
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->uploadImage()) {
                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash('success', 'Item creado <b>exitosamente</b>.');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $valid = true;
            if (!empty($model->imageFile)) {
                $model->deleteImage();
                $valid = $model->uploadImage();
            }
            if ($valid) {
                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash('success', 'Item actualizado <b>exitosamente</b>.');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelPurchases = Purchase::find()
            ->join('INNER JOIN', 'purchase_detail', 'purchase.id = purchase_detail.purchase_id')
            ->where('purchase.id IN (SELECT purchase_detail.purchase_id
                                     FROM purchase_detail
                                     WHERE purchase_detail.item_id = ' . $id . ')'
            )
            ->groupBy('purchase.id')
            ->having(['COUNT(*)' => 1])
            ->all();
        $modelSales = Sale::find()
            ->join('INNER JOIN', 'sale_detail', 'sale.id = sale_detail.sale_id')
            ->where('sale.id IN (SELECT sale_detail.sale_id
                                 FROM sale_detail
                                 WHERE sale_detail.item_id = ' . $id . ')'
            )
            ->groupBy('sale.id')
            ->having(['COUNT(*)' => 1])
            ->all();

        foreach ($modelPurchases as $modelPurchase) {
            $modelPurchase->delete();
        }
        foreach ($modelSales as $modelSale) {
            $modelSale->delete();
        }
        $model->deleteImage();
        $model->delete();
        Yii::$app->getSession()->setFlash('success', 'Item eliminado <b>exitosamente</b>.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
