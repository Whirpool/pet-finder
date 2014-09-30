<?php
Yii::import('search.models.PetFinder');
class PetFinderTest extends CDbTestCase
{
    public $fixtures=array(
        'pet'=>'Pet',
        'age'=>'Age',
        'finder' => 'PetFinder'
    );

    public function testGetRelationData()
    {
        $relation = 'pets';
        $petFinder = new PetFinder;
        $result = $petFinder->getRelationData($relation);
        $this->assertEquals($result, $this->pet);

        $relation = 'ages';
        $result = $petFinder->getRelationData($relation);
        $this->assertEquals($result, $this->age);
    }

    public function testSearchPetOne()
    {
        $data = [
            'search_type' => 1,
            'date' => '27-02-2014',
            'breed' => 'перс',
            'bounds' => [
                [61.23488802399125, 55.076234138901754],
                [61.653398461002965,55.194247843391835]
            ]
        ];
        $petFinder = new PetFinder;
        $result = $petFinder->searchPet($data);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        foreach ($result as $res) {
            $this->assertEquals($res->breed, $this->finder[0]['breed']);
        }
    }

    public function testSearchPetMany()
    {
        $data = [
            'search_type' => 1,
            'date' => '27-02-2014',
            'bounds' => [
                [61.23488802399125, 55.076234138901754],
                [61.653398461002965,55.194247843391835]
            ]
        ];
        $petFinder = new PetFinder;
        $result = $petFinder->searchPet($data);
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertCount(2, $result);
        foreach ($result as $key => $res) {
            $this->assertEquals($res->breed, $this->finder[$key]['breed']);
        }
    }

    public function testValidate()
    {
        $petFinder = new PetFinder;
        $petFinder->breed = '';
        $this->assertFalse($petFinder->validate(array('breed')));
        $petFinder->pet_id = 'text';
        $this->assertFalse($petFinder->validate(array('pet_id')));
    }

    public function testSave()
    {
        $petFinder = new PetFinder;
        $petFinder->setAttributes(array(
                'pet_id' => 1,
                'breed' => 'перс',
                'age_id' => 3,
                'date' => 1390240800,
                'date_create' => 1390260843,
                'date_update' => 1390260843,
                'contact' => '8-919-22-222-22 Ольга',
                'search_type' => 1,
                'search_status' => 1,
                'author' => 1,
                'lng' => 53.12,
                'lat' => 60.42,
                'src' => 'Перс.jpg'
            ),false);
        $this->assertTrue($petFinder->save(false));
    }
}

