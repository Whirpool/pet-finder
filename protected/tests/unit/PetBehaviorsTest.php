<?php

class PetBehaviorsTest extends CDbTestCase
{
    public function testFindPetByLocation()
    {
        $init = [
            'type' => 2,
            'status' => 2
        ];

        $data = [
            'sex' => 'мужской',
            'date' => '10-01-2010',
            'age' => [
                'min' => 3.0,
                'max' => 4.0
            ],
            'location' => [
                'radius' => 2500,
                'lat' => 55.14,
                'lng' => 61.44,
            ]
        ];

        $pet = (new Pet)->create($init);
        $result = $pet->findPetByLocation($data);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);

    }

    public function testFindPetById()
    {
        $init = [
            'type' => 2,
            'status' => 2
        ];

        $pet = (new Pet)->create($init);
        $res = $pet->findPetById(6000005);
        $this->assertNotNull($res);
        $this->assertNotEmpty($res);

    }

    public function testIsZoomValid()
    {
        $init = [
            'type' => 2,
            'status' => 2
        ];

        $radius = 2000;
        $pet = (new Pet)->create($init);
        $this->assertTrue($pet->isZoomValid($radius));

        $radius = 4000;
        $this->assertFalse($pet->isZoomValid($radius));
    }
}