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
    private static $_gmagickFile;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $tempName
     * @param string $type
     * @param int    $size
     * @param int    $error
     */
    public function __construct($name, $tempName, $type, $size, $error)
    {
        parent::__construct($name, $tempName, $type, $size, $error);

        $this->_nameOriginalSize = md5($name . time()) . '.jpg';
        $this->_nameSmallSize = md5($name . time() . 'small') . '.jpg';
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
     * Singleton объекта gmagick для загружаемого изображения
     * из за очень ресурсоёмкого вызова конструктора
     *
     * @param $file
     *
     * @return Gmagick
     * @throws CHttpException
     */
    public static function getInstanceGmagick($file)
    {
        if (is_null(self::$_gmagickFile)) {
            try {
                self::$_gmagickFile = new Gmagick($file);
            } catch (GmagickException $e) {
                throw new CHttpException(400);
            }

        }

        return self::$_gmagickFile;

    }

    /**
     * Эмуляция технологии Philips Ambilight для изображения
     * В результате получается пропорциональне изображение
     * внутри бокса фиксированных размеров. Пустое пространство
     * заполняется заблюренной копией исходного изображения.
     *
     * @param      $file
     * @param      $resultFile
     * @param bool $withCompress
     * @param int  $width
     * @param int  $height
     * @param int  $blur
     * @param int  $border
     *
     * @throws CHttpException
     */
    public function ambilight(
        $file,
        $resultFile,
        $withCompress = true,
        $width = 320,
        $height = 320,
        $blur = 20,
        $border = 20
    ) {
        $gmagick = self::getInstanceGmagick($file);
        $image = $gmagick->getimage();
        $background = $image->getImage();
        $background->scaleImage($width, $height);
        $background->blurImage(10, $blur);
        $image->thumbnailImage($width - $width / $border, $height - $height / $border, true);
        $imageWidth = $image->getImageWidth();
        $imageHeight = $image->getImageHeight();
        $background->compositeImage($image, Gmagick::COMPOSITE_OVER, ($width - $imageWidth) / 2,
            ($height - $imageHeight) / 2);
        if ($withCompress) {
            $background->setimagecompression(GMAGICK::COMPRESSION_JPEG);
            $background->setCompressionQuality(75);
        }
        file_put_contents($resultFile, $background->getImageBlob());
    }

    /**
     * Ресайз изображения
     *
     * @param      $file
     * @param bool $withCompress
     * @param int  $width
     * @param int  $height
     *
     * @throws CHttpException
     */
    public function resize($file, $withCompress = true, $width = 600, $height = 600)
    {
        $gmagick = self::getInstanceGmagick($file);
        $gmagick->scaleImage($width, $height, true);
        if ($withCompress) {
            $gmagick->setimagecompression(GMAGICK::COMPRESSION_JPEG);
            $gmagick->setCompressionQuality(75);
        }
        $gmagick->writeImage($file);
    }
}