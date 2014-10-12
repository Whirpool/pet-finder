<?php

class ImageValidator extends CFileValidator
{

    public $maxSize = 10485760;

    public $fileLimit = 5;


    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);

        if (is_null($object->getError('file')) && Yii::app()->user->hasState('image')) {
            $files = Yii::app()->user->getState('image');
            if (count($files) > $this->fileLimit) {
                $message = 'Превышен лимит загруженных файлов. Максимальное количество загруженных файлов: '. $this->fileLimit .'.';
                $this->addError($object, $attribute, $message);
            }
        }
    }
}