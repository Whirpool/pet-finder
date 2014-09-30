<?php
class AccessCommand extends CConsoleCommand
{
    public function actionAddRules() {
        $auth=Yii::app()->authManager;

        $auth->createOperation('view','access to module');
        $auth->createOperation('search','search pets');
        $auth->createOperation('new','add pet');

        $role=$auth->createRole('guest');

        $role=$auth->createRole('user');
        $role->addChild('view');
        $role->addChild('search');
        $role->addChild('new');

        $role=$auth->createRole('admin');
        $role->addChild('user');
    }

    public function actionAddAdminUser() {
    }
}