<?php
class PfImagesTest extends CDbTestCase
{
    protected function setUp()
    {
        parent::setUp();
        $this->pfImages = new Images;
    }


    public function testValidateFile()
    {
        $_FILES = [
            'file' => [
                'name' => 'default.png',
                'type' => 'image/jpeg',
                'tmp_name' => '../../images/default.png',
                'error' => 0,
                'size' => 549888
            ]
        ];
        $this->pfImageUpload = new Images('upload');
        $this->pfImageUpload->file = UploadedImage::getInstanceByName('file');
        $this->assertTrue($this->pfImageUpload->validate(array('file')));
    }


    public function testValidateName()
    {
        $this->pfImages->name_original = '';
        $this->assertFalse($this->pfImages->validate(array('name_original')));

        $this->pfImages->name_original = 'fc1031aadd2e5f0d85e7d2b9432a3943.jpg';
        $this->assertTrue($this->pfImages->validate(array('name_original')));
    }

    public function testSave()
    {
        $this->pfImages->setAttributes(array(
                'pet_id' => 6000005,
                'name_original' => 'fc1031aadd2e5f0d85e7d2b9432a3943.jpg',
                'name_small' => '1031aadd2e5f0d85e7d2b9432a3943.jpg',
                'source_original' => 'images/original/1',
                'source_small' => 'images/small/1',
                'mime' => 'jpeg'
            ),false);
        $this->assertTrue($this->pfImages->save(false));
    }
}

