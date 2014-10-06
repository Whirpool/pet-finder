<?php
class LookupTest extends CDbTestCase
{
    public $fixtures=array(
        'lookup' => 'Lookup'
    );

    public function testGetRelationData()
    {
        $lookup = new Lookup;
        $result = $lookup->getRelationData();
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertCount(3, $result);
    }
}
