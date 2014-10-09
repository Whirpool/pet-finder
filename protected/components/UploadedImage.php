<?php

class UploadedImage extends CUploadedFile
{
    /**
     * Генерируемое имя изображения оригинального размера
     *
     * @var string
     */
    private $_nameOriginalSize;

    /**
     * Генерируемое имя уменьшенного размера изображения
     *
     * @var string
     */
    private $_nameSmallSize;

    /**
     * Сущность единственного обьъекта GMagick загружаемогог файла
     *
     * @var
     */
    private $_gmagick;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $tempName
     * @param string $type
     * @param int    $size
     * @param int    $error
     * @throws CHttpException
     */
    public function __construct($name, $tempName, $type, $size, $error)
    {
        parent::__construct($name, $tempName, $type, $size, $error);

        $this->_nameOriginalSize = md5($name . time()) . '.jpg';
        $this->_nameSmallSize = md5($name . time() . 'small') . '.jpg';

        try {
            $this->_gmagick = new Gmagick($tempName);
        } catch (GmagickException $e) {
            $message = "Файл $name поврежден";
            throw new CHttpException(400, $message);
        }
    }

    /**
     * @return string
     */
    public function getNameOriginalSize()
    {
        return $this->_nameOriginalSize;
    }

    /**
     * @return string
     */
    public function getNameSmallSize()
    {
        return $this->_nameSmallSize;
    }

    /**
     * Эмуляция технологии Philips Ambilight для изображения
     * В результате получается пропорциональне изображение
     * внутри бокса фиксированных размеров. Пустое пространство
     * заполняется заблюренной копией исходного изображения.
     *
     * @param bool $withCompress
     * @param int  $width
     * @param int  $height
     * @param int  $blur
     * @param int  $border
     *
     * @throws CHttpException
     */
    public function createThumbnail($withCompress = true, $width = 320, $height = 320, $blur = 20, $border = 20) {
        $resultFile = Yii::app()->params['images']['path']['tmp'] . $this->getNameSmallSize();
        $image = $this->_gmagick->getimage();
        $background = $image->getImage();
        $background->scaleImage($width, $height);
        $background->blurImage(10, $blur);
        $image->thumbnailImage($width - $width / $border, $height - $height / $border, true);
        $imageWidth = $image->getImageWidth();
        $imageHeight = $image->getImageHeight();
        $background->compositeImage($image, Gmagick::COMPOSITE_OVER, ($width - $imageWidth) / 2,
            ($height - $imageHeight) / 2);

        file_put_contents($resultFile, $background->getImageBlob());
        if ($withCompress) {
           $this->compressImage($resultFile);
        }
    }

    /**
     * Ресайз изображения
     *
     * @param bool $withCompress
     * @param int  $width
     * @param int  $height
     *
     * @throws CHttpException
     */
    public function resize($withCompress = true, $width = 600, $height = 600)
    {
        $this->_gmagick->scaleImage($width, $height, true);

        $this->_gmagick->writeImage($this->getTempName());
        if ($withCompress) {
            $this->compressImage($this->getTempName());
        }
    }

    /**
     * Compress image with jpegtran
     *
     * @param $file
     */
    private function compressImage($file)
    {
        $command = 'jpegtran -copy none -optimize -outfile '.$file.' '.$file;
        exec($command);
    }
}