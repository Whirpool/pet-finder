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
     * На вход получает имя таблицы
     * возвращает данные для дальнейшей
     * фильтрации на клиенте
     */
    public function actionRelation()
    {
        $result = Lookup::model()->getRelationData();
        if (is_null($result)) {
            $this->renderJson('error', 'Empty relation data');
        } else {
            $this->renderJson('data', $result);
        }
    }

    /**
     * Поиск питомцев по заданным критериям
     */
    public function actionSearch()
    {
        $data = CJSON::decode(Yii::app()->request->rawBody);
        $model = new PetFinder;
        if($model->isZoomValid($data['location'])) {
            $result = $model->searchPets($data);
            if (is_null($result)) {
                $this->renderJson('empty');
            } else {
                $this->renderJson('data', $result);
            }
        } else {
            $message = ['zoom' => 'Слишком большой зум'];
            $this->renderJson('error', $message);
        }

    }

    /**
     * Сохранение новой записи.
     */
    public function actionNew()
    {
        $data  = CJSON::decode(Yii::app()->request->rawBody);

        $model = new PetFinder;
        $model->attributes = $data;
        $model->setUnixDate();
        if ($model->validate()) {
            $model->setPoint();
            if ($model->save()) {
                $this->renderJson('success');
            } else {
                $message = 'Не могу сохранить в базу';
                $this->renderJson('error', $message);
            }
        } else {
            $this->renderJson('error', $model->getErrors());
        }
    }

    public function actionView($id){

        $result = PetFinder::model()->searchPet($id);

        if(is_null($result)) {
            $this->renderJson('empty');
        } else {
            $this->renderJson('data', $result);
        }
    }

}