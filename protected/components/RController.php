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
        $this->renderPartial('application.views.system.output', [
            'type'       => (isset($params['type']) ? $params['type'] : null),
            'success'    => (isset($params['success']) ? $params['success'] : true),
            'message'    => (isset($params['message']) ? $params['message'] : ''),
            'totalCount' => (isset($params['totalCount']) ? $params['totalCount'] : 0),
            'modelName'  => (isset($params['modelName']) ? $params['modelName'] : 'model'),
            'data'       => (isset($params['data']) ? $params['data'] : null),
            'errorCode'  => (isset($params['errorCode']) ? (int)$params['errorCode'] : 500),
            'createdUrl' => (isset($params['createdUrl']) ? $params['createdUrl'] : null),
            ]);
        Yii::app()->end();
    }



//    jquery Требуется для yii debug toolbar
//    public function init()
//    {
//        $cs = Yii::app()->getClientScript();
//        $cs->scriptMap = array('jquery.min.js' => false);
//    }
}