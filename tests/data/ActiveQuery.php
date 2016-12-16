<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\jsonapi\tests\data;


class ActiveQuery extends \yii\db\ActiveQuery
{
    public function one($db = null)
    {
        return new $this->modelClass;
    }

}