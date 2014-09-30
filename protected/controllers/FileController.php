<?php

/**
 * Class MainController
 */
class FileController extends RController
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
     * Получение с клиента данных о файле
     * и его валидация. Если валидация успешно
     * пройдена, файл сохраняется на сервере
     */
    public function actionLoad()
    {
        $files = [];
        $model = new PfImages('upload');
        $model->file = UploadedImage::getInstanceByName('file');
        if ($model->validate()) {
            $model->file->resize($model->file->getTempName());
            $model->file->ambilight($model->file->getTempName(), Yii::app()->params['images']['path']['tmp']. $model->file->getNameSmallSize());
            $model->file->saveAs(Yii::app()->params['images']['path']['tmp'].$model->file->getNameOriginalSize());
            $image = [
                'nameDefault'  => $model->file->getName(),
                'nameOriginal' => $model->file->getNameOriginalSize(),
                'nameSmall'    => $model->file->getNameSmallSize(),
                'size'         => $model->file->getSize(),
                'mime'         => $model->file->getType(),
            ];
            if (Yii::app()->user->hasState('image')) {
                $files = Yii::app()->user->getState('image');
                $files[] = $image;
                Yii::app()->user->setState('image', $files);
            } else {
                $files[] = $image;
                Yii::app()->user->setState('image', $files);
            }
            $data = [
                'nameDefault'  => $model->file->getName(),
                'nameOriginal' => $model->file->getNameOriginalSize(),
                'nameSmall'    => $model->file->getNameSmallSize(),
            ];
            $this->renderJson('data', $data);
        } else {
            $this->renderJson('error', $model->getError('file'));
        }
    }

    public function actionDelete()
    {
        $image = CJSON::decode(Yii::app()->request->rawBody);
        $files = Yii::app()->user->getState('image');

        foreach ($files as $key => $file) {
            if($file['nameOriginal'] === $image['nameOriginal'] && $file['nameSmall'] === $image['nameSmall']) {
                if(is_file(Yii::app()->params['images']['path']['tmp'].$file['nameOriginal']) && is_file(Yii::app()->params['images']['path']['tmp'].$file['nameSmall'])) {
                    unlink(Yii::app()->params['images']['path']['tmp'].$file['nameOriginal']);
                    unlink(Yii::app()->params['images']['path']['tmp'].$file['nameSmall']);
                    unset($files[$key]);
                    Yii::app()->user->setState('image', $files);
                    $this->renderJson('success');
                    Yii::app()->end();
                }
            }
        }
        $this->renderJson('error', 'File not found');
    }


}