<?php

class RController extends CController
{
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();

    public $layout = 'pfmain';

    /**
     * Отправляет неа клиент данные в json формате
     *
     * @param      $params
     *
     * @throws CException
     */
    protected function renderJson($params)
    {
        $this->renderPartial('application.views.system.output', ['params' => $params]);
    }

    protected function getPetRequest()
    {
        if (Yii::app()->request->getRequestType() === 'GET' && !empty($_GET)) {
            if (!isset($_GET['pet']) || !is_numeric($_GET['pet'])) {
                $errorMsg[] = 'Поле тип питомца обязательно для заполнения. Тип поля: целое число.';
            }
            if (!isset($_GET['status']) || !is_numeric($_GET['status'])) {
                $errorMsg[] = 'Поле статус питомца обязательно для заполнения. Тип поля: целое число.';
            }
            if (!isset($_GET['lat']) || !is_numeric($_GET['lat'])) {
                $errorMsg[] = 'Поле широта обязательно для заполнения. Тип поля: вещественное число.';
            }
            if (!isset($_GET['lng']) || !is_numeric($_GET['lng'])) {
                $errorMsg[] = 'Поле долгота обязательно для заполнения. Тип поля: вещественное число.';
            }
            if (!isset($_GET['radius']) || !is_numeric($_GET['radius'])) {
                $errorMsg[] = 'Поле радиус обязательно для заполнения. Тип поля: целое число.';
            }
            if (!isset($_GET['date']) || !is_string($_GET['date'])) {
                $errorMsg[] = 'Поле дата обязательно для заполнения. Тип поля: строка формата дд-мм-гггг.';
            }
            if (!isset($_GET['sex']) || !is_string($_GET['sex'])) {
                $errorMsg[] = 'Поле пол питомца обязательно для заполнения. Тип поля: строка(мужской/женский).';
            }
            if (isset($_GET['age']) && !is_numeric($_GET['age'])) {
                $errorMsg[] = 'Тип поля возраст: вещественное число.';
            }
            if (isset($_GET['ageMax']) && !is_numeric($_GET['ageMax'])) {
                $errorMsg[] = 'Тип поля максимальный возраст: вещественное число.';
            }
            if (isset($_GET['ageMin']) && !is_numeric($_GET['ageMin'])) {
                $errorMsg[] = 'Тип поля минимальный возраст: вещественное число.';
            }
            if (isset($_GET['breeds'])) {
                foreach ($_GET['breeds'] as $breed) {
                    if (!is_string($breed)) {
                        $errorMsg[] = 'Тип поля порода: строка.';
                    }
                }
            }
        } else {
            $errorMsg[] = 'Заполните параметры запроса.';
        }
        if (isset($errorMsg)) {
            throw new CException($errorMsg);
        }
        return $_GET;
    }



//    jquery Требуется для yii debug toolbar
//    public function init()
//    {
//        $cs = Yii::app()->getClientScript();
//        $cs->scriptMap = array('jquery.min.js' => false);
//    }
}