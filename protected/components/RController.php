<?php
class RController extends CController
{
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu=array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs=array();

    public $layout = 'pfmain';

    protected function renderJson($type, $data = null)
    {
        $status = 200;
        switch($type) {
            case 'success':
                $success = true;
                $this->renderPartial('application.views.system.output', [
                        'status' => $status,
                        'output' =>  $success
                    ]);
                break;
            case 'data':
                $this->renderPartial('application.views.system.output', [
                        'status'  => $status,
                        'output'  => $data
                    ]);
                break;
            case 'empty':
                $status = 204;
                $this->renderPartial('application.views.system.output', [
                        'status'  => $status,
                        'output'  => []
                    ]);
                break;
            case 'error':
                $status = 400;
                $this->renderPartial('application.views.system.error', [
                        'status'  => $status,
                        'output'  => $data
                    ]);
                break;
            default:
                echo CJSON::encode($data);
                break;

        }


    }



//    jquery Требуется для yii debug toolbar
//    public function init()
//    {
//        $cs = Yii::app()->getClientScript();
//        $cs->scriptMap = array('jquery.min.js' => false);
//    }
}