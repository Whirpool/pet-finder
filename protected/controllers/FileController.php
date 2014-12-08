<?php

class FileController extends RController
{

    /**
     * @return array
     */
    public function filters()
    {

    }




    /**
     * Валидация и загрузка изображения.
     * Генерация преаью изображения.
     * При успешной загрузке данные сохраняются в сессии
     * для дальнейшей записи в модель.
     */
    public function actionLoad()
    {
        $files = [];
        $model = new Images('upload');
        try {
            $model->file = UploadedImage::getInstanceByName('file');
        } catch (CException $e) {
           $this->renderJson([
               'type' => 'error',
               'errorCode' => 400,
               'message' => $e->getMessage()
           ]);
        }
        if ($model->validate()) {
            $model->file->resize();
            $model->file->createThumbnail();
            $model->file->saveAs(Yii::app()->params['images']['tmp'] . $model->file->getNameOriginalSize());
            $image = [
                'nameDefault' => $model->file->getName(),
                'nameOriginal' => $model->file->getNameOriginalSize(),
                'nameSmall' => $model->file->getNameSmallSize(),
                'mime' => $model->file->getType(),
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
                'nameDefault' => $model->file->getName(),
                'nameOriginal' => $model->file->getNameOriginalSize(),
                'nameSmall' => $model->file->getNameSmallSize(),
            ];
            $this->renderJson([
                'type' => 'data',
                'success' => true,
                'totalCount' => 1,
                'data' => $data,
            ]);
        } else {
            $this->renderJson([
                'type' => 'error',
                'errorCode' => 422,
                'message' => $model->getError('file')
            ]);
        }
    }

    /**
     * Удаление изображения из файловой системы и сессии
     */
    public function actionDelete()
    {
        $image = CJSON::decode(Yii::app()->request->rawBody);
        $files = Yii::app()->user->getState('image');

        foreach ($files as $key => $file) {
            if ($file['nameOriginal'] === $image['nameOriginal'] && $file['nameSmall'] === $image['nameSmall']) {
                if (is_file(Yii::app()->params['images']['path']['tmp'] . $file['nameOriginal'])
                    && is_file(Yii::app()->params['images']['path']['tmp'] . $file['nameSmall'])
                ) {
                    unlink(Yii::app()->params['images']['path']['tmp'] . $file['nameOriginal']);
                    unlink(Yii::app()->params['images']['path']['tmp'] . $file['nameSmall']);
                    unset($files[$key]);
                    Yii::app()->user->setState('image', $files);
                    $this->renderJson(['type' => 'empty']);
                }
            }
        }
        $this->renderJson([
            'type' => 'error',
            'errorCode' => 404,
            'message' => 'File not found'
        ]);
    }


}