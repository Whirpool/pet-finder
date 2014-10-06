<?php

class RActiveRecord extends CActiveRecord
{
    /**
     * Рекурсивно конвертирует объект модели и
     * его связи в массив
     *
     * @param $models
     *
     * @return array
     */
    protected function convertModelToArray($models)
    {
        if (is_array($models)) {
            $arrayMode = true;
        } else {
            $models = array($models);
            $arrayMode = false;
        }

        $result = array();
        foreach ($models as $model) {
            $attributes = $model->getAttributes(false);
            $relations = array();
            foreach ($model->relations() as $key => $related) {
                if ($model->hasRelated($key)) {
                    $relations[$key] = $this->convertModelToArray($model->$key);
                }
            }
            $all = array_merge($attributes, $relations);

            if ($arrayMode) {
                array_push($result, $all);
            } else {
                $result = $all;
            }
        }
        return $result;
    }
}