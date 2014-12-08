<?php
class Lookups
{
    public function getAll()
    {
        $result = [];
        $result['pet']['type'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_pet_type')->queryAll();
        $result['pet']['status'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_pet_status')->queryAll();
        $result['pet']['eyes'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_pet_eyes')->queryAll();
        $result['pet']['fur'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_pet_fur')->queryAll();
        $result['cat']['color'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_cat_type_color')->queryAll();
        $result['cat']['breed'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_cat_breed')->queryAll();
        $result['dog']['breed'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_dog_breed')->queryAll();
        $result['dog']['figured'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_dog_type_figured')->queryAll();
        $result['dog']['color'] = Yii::app()->db->createCommand()->select('id, value')->from('lookup_dog_type_color')->queryAll();

        return $result;
    }
}