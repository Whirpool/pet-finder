<?php
class PetFinderTest extends CDbTestCase
{
    public $fixtures=array(
        'finder' => 'PetFinder'
    );

    protected function setUp()
    {
        parent::setUp();
        $this->petFinder = new PetFinder();
    }

    public function testSearchPetOne()
    {
        $data = [
            'search_type' => 1,
            'date' => '27-02-2014',
            'breed' => 'сибир',
            'location' => [
                'upLeft' => [
                    'lat' => 61.33488802399125,
                    'lng' => 55.146234138901754
                ],
                'downRight' => [
                    'lat' => 61.553398461002965,
                    'lng' => 55.164247843391835
                ]
            ],
        ];
        $result = $this->petFinder->searchPets($data);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertEquals($result[0]['breed'], $this->finder[1]['breed']);
    }



    public function testValidateBreed()
    {
        $this->petFinder->breed = '';
        $this->assertFalse($this->petFinder->validate(array('breed')));
        $this->petFinder->breed = 'си';
        $this->assertFalse($this->petFinder->validate(array('breed')));
        $this->petFinder->breed = 123;
        $this->assertFalse($this->petFinder->validate(array('breed')));
        $this->petFinder->breed = 'сибирская';
        $this->assertTrue($this->petFinder->validate(array('breed')));
    }

    public function testValidatePet()
    {
        $petFinder = new PetFinder;
        $petFinder->pet_id = 'text';
        $this->assertFalse($petFinder->validate(array('pet_id')));
        $petFinder->pet_id = 1;
        $this->assertTrue($petFinder->validate(array('pet_id')));
    }

    public function testValidateAge()
    {
        $this->petFinder->age_id = 'text';
        $this->assertFalse($this->petFinder->validate(array('age_id')));
        $this->petFinder->age_id = 1;
        $this->assertTrue($this->petFinder->validate(array('age_id')));
    }

    public function testValidateName()
    {
        $this->petFinder->name = '';
        $this->assertTrue($this->petFinder->validate(array('name')));
        $this->petFinder->name = 'пе';
        $this->assertFalse($this->petFinder->validate(array('name')));
        $this->petFinder->name = 'dsadsa2132131';
        $this->assertFalse($this->petFinder->validate(array('name')));
        $this->petFinder->name = 'Петр';
        $this->assertTrue($this->petFinder->validate(array('name')));
    }

    public function testValidateDate()
    {
        $this->petFinder->date = '';
        $this->assertFalse($this->petFinder->validate(array('date')));
        $this->petFinder->date = 'date';
        $this->assertFalse($this->petFinder->validate(array('date')));
        $this->petFinder->date = 1232132.432;
        $this->assertFalse($this->petFinder->validate(array('date')));
        $this->petFinder->date = 1232132;
        $this->assertTrue($this->petFinder->validate(array('date')));
    }


    public function testValidateSex()
    {
        $this->petFinder->sex = 'text';
        $this->assertFalse($this->petFinder->validate(array('sex')));
        $this->petFinder->sex = 4;
        $this->assertFalse($this->petFinder->validate(array('sex')));
        $this->petFinder->sex = 1;
        $this->assertTrue($this->petFinder->validate(array('sex')));
    }

    public function testValidateCoords()
    {
        $this->petFinder->lat = 'text';
        $this->petFinder->lng = 'text';
        $this->assertFalse($this->petFinder->validate(array('lat')));
        $this->assertFalse($this->petFinder->validate(array('lng')));
        $this->petFinder->lng = 11;
        $this->petFinder->lat = 22;
        $this->assertTrue($this->petFinder->validate(array('lng')));
        $this->assertTrue($this->petFinder->validate(array('lat')));
    }


    public function testSave()
    {
        $lat = 61.45;
        $lng = 55.15;
        $this->petFinder->setAttributes(array(
                'pet_id' => 1,
                'breed' => 'перс',
                'age_id' => 3,
                'sex' => 1,
                'date' => 1390240800,
                'date_create' => 1390260843,
                'date_update' => 1390260843,
                'search_type' => 1,
                'search_status' => 1,
                'author' => 1,
                'lng' => $lat,
                'lat' => $lng,
                'coords' => new CDbExpression('POINT(:lat, :lng)', array(':lat' => $lat, ':lng' => $lng)),
            ),false);
        $this->assertTrue($this->petFinder->save(false));
    }

    public function testIsZoomValid()
    {
        $data = [
            'location' => [
                'upLeft' => [
                    'lat' => 61.33488802399125,
                    'lng' => 55.146234138901754
                ],
                'downRight' => [
                    'lat' => 61.553398461002965,
                    'lng' => 55.164247843391835
                ]
            ],
        ];
        $result = $this->petFinder->isZoomValid($data['location']);
        $this->assertTrue($result);

        $data['location']['upLeft']['lng'] = 55.17;
        $result = $this->petFinder->isZoomValid($data['location']);
        $this->assertFalse($result);

        $data['location']['upLeft']['lat'] = 55.17;
        $result = $this->petFinder->isZoomValid($data['location']);
        $this->assertFalse($result);
    }

}

