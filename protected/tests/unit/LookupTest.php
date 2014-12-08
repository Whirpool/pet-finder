<?php
class LookupTest extends CDbTestCase
{
    public function testGetLookup()
    {
        $result = (new Pet)->getLookup();
        $this->assertNotNull($result);
        $this->assertNotEmpty($result);
        $this->assertCount(3, $result);
        $this->assertCount(4, $result['pet']);
        $this->assertCount(3, $result['dog']);
        $this->assertCount(2, $result['cat']);
    }
}
