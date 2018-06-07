<?php

namespace app\controllers;

use Yii;
use app\models\Person;
use app\models\PersonSearch;
use app\models\Relationship;
use app\models\RelationshipSearch;
use app\models\Model;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PatientController implements the CRUD actions for Patient model.
 */
class PatientController extends Controller
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
     * Lists all Patient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 1);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Patient model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModelRelationship = new RelationshipSearch();
        $dataProviderRelationship = $searchModelRelationship->searchByPatient(Yii::$app->request->queryParams, $id);
        $dataProviderRelationship->pagination->pageParam = "page-relationship";
        $dataProviderRelationship->sort->sortParam = "sort-relationship";

        // $searchModelSale = new SaleDetailSearch();
        // $dataProviderSale = $searchModelSale->searchByPatient(Yii::$app->request->queryParams, $id);
        // $dataProviderSale->pagination->pageParam = "page-sale";
        // $dataProviderSale->sort->sortParam = "sort-sale";

        return $this->render('view', [
            'model' => $this->findModel($id),
            'searchModelRelationship' => $searchModelRelationship,
            'dataProviderRelationship' => $dataProviderRelationship,
            // 'searchModelSale' => $searchModelSale,
            // 'dataProviderSale' => $dataProviderSale,
        ]);
    }

    /**
     * Creates a new Patient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Person(['scenario' => Person::SCENARIO_PATIENT]);
        $modelRelationships = null;

        if ($model->load(Yii::$app->request->post())) {
            $modelRelationships = Model::createMultiple(Relationship::classname());
            Model::loadMultiple($modelRelationships, Yii::$app->request->post());

            $model->role = 1;

            // validate all models
            $valid = $model->validate() && Model::validateMultiple($modelRelationships);

            if ($valid) {
                foreach ($modelRelationships as $index => $modelRelationship) {                    
                    foreach ($modelRelationships as $_index => $_modelRelationship) {
                        if ($modelRelationship->person_id == $_modelRelationship->person_id &&
                            $index != $_index
                        ) {
                            $valid = false;
                            Yii::$app->getSession()->setFlash('error', 'El representante "' . $modelRelationship->person->fullname . '" <b>se encuentra repetido</b>.');
                            break 2;
                        }
                    }
                }
                
                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();
                    
                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                    
                    try {
                        if ($model->uploadImage()) {
                            if ($flag = $model->save(false)) {
                                foreach ($modelRelationships as $modelRelationship) {
                                    $modelRelationship->patient_id = $model->id;
                                    $flag = $modelRelationship->save();
                                    if (!$flag) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
                            
                            if ($flag) {
                                $transaction->commit();
                                Yii::$app->getSession()->setFlash('success', 'Paciente registrado <b>exitosamente</b>.');
                                return $this->redirect(['view', 'id' => $model->id]);
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelRelationships' => empty($modelRelationships) ? [new Relationship] : $modelRelationships,
        ]);
    }

    /**
     * Updates an existing Patient model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $modelRelationships = $model->patientRelationships;

        if ($model->load(Yii::$app->request->post())) {
            $oldmodelRelationships = array();
            foreach ($modelRelationships as $index => $modelRelationship) {
                $oldmodelRelationships[$index] = [
                    'id' => $modelRelationship->id
                ];
            }
            $oldIDs = ArrayHelper::map($modelRelationships, 'id', 'id');

            $modelRelationships = Model::createMultiple(Relationship::classname(), $modelRelationships);
            Model::loadMultiple($modelRelationships, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelRelationships, 'id', 'id')));

            // validate all models
            $valid = $model->validate() && Model::validateMultiple($modelRelationships);

            if ($valid) {
                foreach ($modelRelationships as $index => $modelRelationship) {
                    foreach ($modelRelationships as $_index => $_modelRelationship) {
                        if ($modelRelationship->person_id == $_modelRelationship->person_id &&
                            $index != $_index
                        ) {
                            $valid = false;
                            Yii::$app->getSession()->setFlash('error', 'El representante "' . $modelRelationship->person->fullname . '" <b>se encuentra repetido</b>.');
                            break 2;
                        }
                    }
                }

                if ($valid) {
                    $transaction = \Yii::$app->db->beginTransaction();

                    $model->imageFile = UploadedFile::getInstance($model, 'imageFile');

                    try {
                        if (!empty($model->imageFile)) {
                            $model->deleteImage();
                            $valid = $model->uploadImage();
                        }
                        if ($valid) {
                            if ($flag = $model->save(false)) {
                                Relationship::deleteAll(['id' => $deletedIDs]);
                                foreach ($modelRelationships as $modelRelationship) {
                                    $modelRelationship->patient_id = $model->id;
                                    $flag = $modelRelationship->save();
                                    if (!$flag) {
                                        $transaction->rollBack();
                                        break;
                                    }
                                }
                            }
    
                            if ($flag) {
                                $transaction->commit();
                                Yii::$app->getSession()->setFlash('success', 'Paciente actualizado <b>exitosamente</b>.');
                                return $this->redirect(['view', 'id' => $model->id]);
                            }
                        }
                    } catch (Exception $e) {
                        $transaction->rollBack();
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelRelationships' => empty($modelRelationships) ? [new Relationship] : $modelRelationships,
        ]);
    }

    /**
     * Deletes an existing Patient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $modelRelationships = $model->patientRelationships;
       
        // if (empty($model->purchases) && empty($model->sales)) {
            $transaction = \Yii::$app->db->beginTransaction();
            try {
                $model->deleteImage();
                if ($model->delete()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', 'Paciente eliminado <b>exitosamente</b>.');
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                }
            } catch (Exception $e) {
                $transaction->rollBack();
            }
        // }

        // Yii::$app->getSession()->setFlash('error', 'Este paciente tiene asociado entrada o salida de items, por lo tanto <b>no se puede eliminar</b>.');
        // return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Patient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Person the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Person::findOne($id)) !== null) {
            $model->scenario = Person::SCENARIO_PATIENT;
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }
}
