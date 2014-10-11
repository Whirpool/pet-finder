<?php

/**
 * Class MainController
 */
class PetController extends RController
{
    /**
     * @return array
     */
    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    /**
     * Разрешиль любые действия только
     * зарегистрированным пользователям
     *
     * @return array
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'users' => array('@'),
            ),
            array(
                'deny',
                'users' => array('*'),
            ),
        );
    }

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
        $model = new Lookup;
        $result = $model->getRelationData();
        if (is_null($result)) {
            $this->renderJson([
                'type'      => 'error',
                'message'   => 'empty relation data',
                'errorCode' => 500
            ]);
        } else {
            $this->renderJson([
                'type'       => 'data',
                'data'       => $result,
                'success'    => true,
                'totalCount' => count($result),
                'modelName'  => $model->getClassName()
            ]);
        }
    }

    /**
     * Поиск питомцев по заданным критериям
     */
    public function actionSearch()
    {
        $data = CJSON::decode(Yii::app()->request->rawBody);
        $model = new PetFinder;
        if ($model->isZoomValid($data['location'])) {
            $result = $model->searchPets($data);
            if (is_null($result)) {
                $this->renderJson(['type' => 'empty']);
            } else {
                $this->renderJson([
                    'type' => 'data',
                    'data' => $result,
                    'success' => true,
                    'totalCount' => count($result),
                    'modelName' => $model->getClassName()
                ]);
            }
        } else {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 400,
                'message' => 'Слишком большой зум'
            ]);
        }

    }

    /**
     * Сохранение новой записи.
     */
    public function actionNew()
    {
        $data = CJSON::decode(Yii::app()->request->rawBody);

        $model = new PetFinder;
        $model->attributes = $data;
        $model->setUnixDate();
        if ($model->validate()) {
            $model->setPoint();
            if ($model->save()) {
                $url = $this->createAbsoluteUrl('/#!/', ['detail' => $model->getPrimaryKey()]);
                $this->renderJson([
                    'type' => 'created',
                    'success' => true,
                    'createdUrl' => $url
                ]);
            } else {
                $this->renderJson([
                    'type' => 'error',
                    'errorCode' => 500,
                    'message' => 'Не могу сохранить в базу'
                ]);
            }
        } else {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 422,
                'message' => $model->getErrors()
            ]);
        }
    }

    /**
     * Поиск по первичному ключу
     *
     * @param $id
     */
    public function actionView($id)
    {

        $result = PetFinder::model()->searchPet($id);

        if (is_null($result)) {
            $this->renderJson(['type' => 'empty']);
        } else {
            $this->renderJson([
                'type' => 'data',
                'data' => $result,
                'success' => true,
                'totalCount' => count($result),
                'modelName' => PetFinder::model()->getClassName()
            ]);
        }
    }

}