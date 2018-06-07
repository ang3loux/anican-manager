<?php

namespace app\controllers;

use Yii;
use app\models\Person;
use app\models\PersonSearch;
use app\models\RelationshipSearch;
use app\models\PurchaseSearch;
use app\models\SaleSearch;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PersonController implements the CRUD actions for Person model.
 */
class PersonController extends Controller
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
     * Lists all Person models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 0);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Person model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModelRelationship = new RelationshipSearch();
        $dataProviderRelationship = $searchModelRelationship->searchByPerson(Yii::$app->request->queryParams, $id);
        $dataProviderRelationship->pagination->pageParam = "page-relationship";
        $dataProviderRelationship->sort->sortParam = "sort-relationship";

        $searchModelPurchase = new PurchaseSearch();
        $dataProviderPurchase = $searchModelPurchase->searchByPerson(Yii::$app->request->queryParams, $id);
        $dataProviderPurchase->pagination->pageParam = "page-purchase";
        $dataProviderPurchase->sort->sortParam = "sort-purchase";

        $searchModelSale = new SaleSearch();
        $dataProviderSale = $searchModelSale->searchByPerson(Yii::$app->request->queryParams, $id);
        $dataProviderSale->pagination->pageParam = "page-sale";
        $dataProviderSale->sort->sortParam = "sort-sale";

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModelRelationship' => $searchModelRelationship,
            'dataProviderRelationship' => $dataProviderRelationship,
            'searchModelPurchase' => $searchModelPurchase,
            'dataProviderPurchase' => $dataProviderPurchase,
            'searchModelSale' => $searchModelSale,
            'dataProviderSale' => $dataProviderSale,
        ]);
    }

    /**
     * Creates a new Person model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Person(['scenario' => Person::SCENARIO_PERSON]);

        if ($model->load(Yii::$app->request->post())) {
            $model->role = 0;
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->uploadImage()) {
                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash('success', 'Persona registrada <b>exitosamente</b>.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Person model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->role != 2 && $model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            $valid = true;
            if (!empty($model->imageFile)) {
                $model->deleteImage();
                $valid = $model->uploadImage();
            }
            if ($valid) {
                if ($model->save(false)) {
                    Yii::$app->getSession()->setFlash('success', 'Persona actualizada <b>exitosamente</b>.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Person model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model->role != 2) {
            if (empty($model->personRelationships)) {
                if (empty($model->purchases) && empty($model->sales)) {
                    $model->deleteImage();
                    $model->delete();
                    Yii::$app->getSession()->setFlash('success', 'Persona eliminada <b>exitosamente</b>.');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->getSession()->setFlash('error', 'Esta persona tiene asociada entrada o salida de items, por lo tanto <b>no se puede eliminar</b>.');
                }
            } else {
                Yii::$app->getSession()->setFlash('error', 'Esta persona es representante de uno o más pacientes, por lo tanto <b>no se puede eliminar</b>.'); 
            }    
            
            return $this->redirect(['view', 'id' => $model->id]);
        }

        Yii::$app->getSession()->setFlash('error', 'Usuario anónimo <b>no se puede eliminar</b>.');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Person model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            $model->scenario = Person::SCENARIO_PERSON;
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
