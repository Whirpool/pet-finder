<?php

/**
 * Class MainController
 */
class PetController extends RController
{
    /**
     * Отображение главной страницы
     */
    public function actionIndex()
    {
        $this->render('index');
    }

    /**
     * Возвращает данные для фильтрации на клиенте
     */
    public function actionRelation()
    {
        $result = (new Pet)->getLookup();
        if (is_null($result)) {
            $this->renderJson([
                'type' => 'error',
                'message' => 'empty relation data',
                'errorCode' => 500
            ]);
        } else {
            $this->renderJson([
                'type' => 'data',
                'data' => $result,
                'success' => true,
            ]);
        }
    }


    public function actionSearch()
    {
        try {
            $request = CJSON::decode(Yii::app()->request->rawBody);
            $pet = (new Pet)->create($request['init']);
            if ($pet->isZoomValid($request['data']['location']['radius'])) {
                $result = $pet->findPetByLocation($request['data']);
                if (is_null($result)) {
                    $this->renderJson(['type' => 'empty']);
                } else {
                    $this->renderJson(['data' => $result]);
                }
            } else {
                $this->renderJson([
                    'type' => 'error',
                    'errorCode' => 400,
                    'message' => 'Слишком большой зум'

                ]);
            }
        } catch (CException $e) {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 400,
                'message' => $e->getMessage()

            ]);
        }

    }

    public function actionNew()
    {
        $request = CJSON::decode(Yii::app()->request->rawBody);
        try {
            $pet = (new Pet)->create($request['init']);
            $pet->setPetAttributes($request['data']);
            if ($pet->validate()) {
                $pet->breeds = $request['data']['breeds'];
                $transaction = $pet->dbConnection->beginTransaction();
                try {
                    $pet->save(false);
                    $transaction->commit();
                } catch (CException $e) {
                    $transaction->rollback();
                    $this->renderJson([
                        'type' => 'error',
                        'errorCode' => 500,
                        'message' => $e->getMessage()
                    ]);
                }
                $url = $this->createAbsoluteUrl('/#!/', ['detail' => $pet->getPrimaryKey()]);
                $this->renderJson([
                    'type' => 'created',
                    'success' => true,
                    'createdUrl' => $url
                ]);
            } else {
                $this->renderJson([
                    'type' => 'error',
                    'errorCode' => 422,
                    'message' => $pet->getErrors()
                ]);
            }
        } catch (CException $e) {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 400,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Поиск по первичному ключу
     *
     * @param $id
     * @param $status
     * @param $type
     */
    public function actionView($type, $status, $id)
    {
        try {
            $pet = (new Pet)->create(['type' => $type, 'status' => $status], true);
            $result = $pet->findPetById($id);
            if (is_null($result)) {
                $this->renderJson(['type' => 'empty']);
            } else {
                $this->renderJson(['data' => $result]);
            }
        } catch (CException $e) {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }


}