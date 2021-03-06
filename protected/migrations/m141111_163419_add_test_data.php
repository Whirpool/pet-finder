<?php

class m141111_163419_add_test_data extends CDbMigration
{

	public function down()
	{
		echo "m141111_163419_add_test_data does not support migration down.\n";
		return false;
	}


	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	$id = 1;
        for($i = 0; $i < 2000000; $i++) {
            $data = $this->generateData();
            $this->insert('dog_lost', [
                'sex' => $data['sex'],
                'sigma' => $data['sigma'],
                'special' => $data['special'],
                'advanced' =>$data['advanced'],
                'date' => $data['date'],
                'date_create' => $data['date_create'],
                'date_update' => $data['date_update'],
                'author' => $data['author'],
                'coords' => $data['coords'],
                'eyes' => $data['eyes'],
                'colors' => $data['colors'],
                'fur' => $data['fur'],
                'type_color' => $data['type_color'],
                'figured' => $data['figured'],
                'age' => $data['age'],
                'name' => $data['name'],
            ]);
            foreach($data['breed'] as $breed) {
                $this->insert('map_dog_breed_lost', [
                    'id_dog' => $id,
                    'id_breed' => $breed
                ]);
            }
            $id++;
        }
	}

	public function safeDown()
	{
	}

    public function generateData()
    {
        $sex = mt_rand(1,3);
        $data['sex'] = $sex === 1 ? 'мужской' : 'женский';

        $data['sigma'] = new CDbExpression("to_tsvector('english',".substr(md5(mt_rand()), 0, 7).")");

        $data['special'] = substr(md5(mt_rand()), 0, 7);
        $data['advanced'] = substr(md5(mt_rand()), 0, 7);

        $date = mt_rand(1261055681,1262955681);
        $data['date'] = date("Y-m-d", $date);

        $date = mt_rand(1261055681,1262955681);
        $data['date_create'] = date("Y-m-d H:i:s", $date);

        $date = mt_rand(1261055681,1262955681);
        $data['date_update'] = date("Y-m-d H:i:s", $date);

        $data['author'] = mt_rand(0,2055681);

        $lng = 50 + mt_rand()/mt_getrandmax() * (70 - 50);
        $lat = 34 + mt_rand()/mt_getrandmax() * (157 - 34);

        $data['coords'] = new CDbExpression("ST_SetSRID(ST_MakePoint($lng, $lat), 4326)");
        $data['eyes'] = mt_rand(1,5);

        $color1 = 'a'.substr(md5(mt_rand()), 0, 7);
        $color2 = 'b'.substr(md5(mt_rand()), 0, 7);
        $color3 = 'c'.substr(md5(mt_rand()), 0, 7);
        $data['colors'] = '{'.$color1.','.$color2.','.$color3.'}';

        $data['fur'] = mt_rand(1,4);
        $data['type_color'] = mt_rand(1,7);
        $data['figured'] = mt_rand(1,3);

        $data['age'] = (mt_rand(0, 10) / 10) + mt_rand(0, 10);

        $data['name'] = substr(md5(mt_rand()), 0, 7);

        $breeds = mt_rand(1,2);

        if ($breeds === 1) {
            $data['breed'][0] = mt_rand(1,50);
        } else {
            $breed1 = mt_rand(1,24);
            $breed2 = mt_rand(25,50);
            $data['breed'][0] = $breed1;
            $data['breed'][1] = $breed2;
        }

        return $data;
    }
}