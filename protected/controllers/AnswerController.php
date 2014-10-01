<?php
/**
 * Class CommentController
 */
class AnswerController extends RController
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

    public function actionNew()
    {
        $data = CJSON::decode(Yii::app()->request->rawBody);
        $message = new PfAnswers;
        $post = PfComment::model()->findByPk($data['id']);
        $message->comment_id = $post->id;
        $message->content = $data['content'];
        $message->author = Yii::app()->user->id;
        $message->time_create = date('Y-m-d H:i:s');
        if ($message->validate()) {
            if ($message->save()) {
                $result = PfAnswers::model()->loadAnswer($message->id);
                $this->renderJson('data', $result);
            }
        } else {
            $this->renderJson('error', $message->getErrors());
        }
    }
}