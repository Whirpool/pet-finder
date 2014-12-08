<?php

class PetTest extends CDbTestCase
{
    public function testCreate()
    {
        $this->pet = new Pet();
        $init = [
            'type' => 2,
            'status' => 2
        ];

        $model = $this->pet->create($init);

        $this->assertTrue($model instanceof DogLost);

        $init = [
            'type' => 1,
            'status' => 2
        ];

        $model = $this->pet->create($init);

        $this->assertTrue($model instanceof CatLost);

        $init = [
            'type' => 1,
            'status' => 1
        ];

        $model = $this->pet->create($init);

        $this->assertTrue($model instanceof CatFind);

        $init = [
            'type' => 2,
            'status' => 1
        ];

        $model = $this->pet->create($init);

        $this->assertTrue($model instanceof DogFind);

        $init = [
            'type' => 'DoG',
            'status' => 'lO sT'
        ];

        $model = $this->pet->create($init, true);

        $this->assertTrue($model instanceof DogLost);
    }

}
