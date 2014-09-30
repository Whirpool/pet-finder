<?php
/**
 * Created by PhpStorm.
 * User: Артур
 * Date: 05.09.14
 * Time: 23:16
 */
class UploadedImage extends CUploadedFile
{
    private $_nameOriginalSize;
    private $_nameSmallSize;

    private static $_gmagickFile;

    public function __construct($name,$tempName,$type,$size,$error)
    {
        parent::__construct($name,$tempName,$type,$size,$error);

        $this->_nameOriginalSize = md5($name . time()) . '.jpg';
        $this->_nameSmallSize    = md5($name . time() . 'small') . '.jpg';
    }

    public function getNameOriginalSize()
    {
        return $this->_nameOriginalSize;
    }

    public function getNameSmallSize()
    {
        return $this->_nameSmallSize;
    }

    public static function getInstanceGmagick($file)
    {
        if(is_null(self::$_gmagickFile)) {
            try {
                self::$_gmagickFile = new Gmagick($file);
            } catch(GmagickException $e) {
                throw new CHttpException(400);
            }

        }

        return self::$_gmagickFile;

    }

    public function ambilight($file, $resultFile, $withCompress = true, $width = 320, $height = 320, $blur = 20, $border = 20)
    {
        $gmagick = self::getInstanceGmagick($file);
        $image = $gmagick->getimage();
        $background = $image->getImage();
        $background->scaleImage($width, $height);
        $background->blurImage(10, $blur);
        $image->thumbnailImage($width - $width / $border, $height - $height / $border, true);
        $imageWidth  = $image->getImageWidth();
        $imageHeight = $image->getImageHeight();
        $background->compositeImage($image, Gmagick::COMPOSITE_OVER, ($width - $imageWidth) / 2, ($height - $imageHeight) / 2);
        if($withCompress) {
            $background->setimagecompression(GMAGICK::COMPRESSION_JPEG);
            $background->setCompressionQuality(75);
        }
        file_put_contents($resultFile, $background->getImageBlob());
    }

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