<?php
/**
 * Class CommentController
 * переделать
 * на клиенте должны бвть данные текущего пользователя
 * чтобы после сохранения комментария в бд
 * не делать запрос по pk а на клиенте использовать
 * данные комментария из формы и username из сервиса
 *
 */
class CommentController extends RController
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
        $message = new PfComment;
        $post = PetFinder::model()->findByPk($data['id']);
        $message->post_id = $post->id;
        $message->content = $data['content'];
        $message->author = Yii::app()->user->id;
        $message->time_create = date('Y-m-d H:i:s');
        if ($message->validate()) {
            if ($message->save()) {
                $result = PfComment::model()->loadComment($message->id);
                $this->renderJson('data', $result);
            }
        } else {
            $this->renderJson('error', $message->getErrors());
        }
    }
}