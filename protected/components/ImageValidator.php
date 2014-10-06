<?php

/**
 * Class ImageValidator
 * Расширеие CFileValidator с дополнительной
 * валидацией через GMagick
 *
 * @var $maxSize  int 10 Mb
 * @var $mimTypes array только изображения
 */
class ImageValidator extends CFileValidator
{

    public $maxSize = 10485760;

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
            } catch (CHttpException $e) {
                $message = "Файл {$file->getName()} поврежден";
                $this->addError($object, $attribute, $message);
            }
        }
    }
}