<?php

/**
 * Created by PhpStorm.
 * User: Артур
 * Date: 03.09.14
 * Time: 15:00
 */
class ImageValidator extends CFileValidator
{

    public $maxSize = 10485760; //10MB

    public $mimeTypes = [
            'image/gif',
            'image/jpeg',
            'image/png',
            'image/pjpeg',
        ];


    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);

        if (is_null($object->getError('file'))) {
            $file = $object->$attribute;
            try {
                $image = UploadedImage::getInstanceGmagick($file->getTempName());
            } catch(CHttpException $e) {
                $message = "Файл {$file->getName()} поврежден";
                $this->addError($object,$attribute,$message);
            }
        }
    }
}