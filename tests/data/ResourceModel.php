<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi\tests\data;

use tuyakhov\jsonapi\ResourceInterface;
use tuyakhov\jsonapi\ResourceTrait;
use yii\base\Model;

class ResourceModel extends Model implements ResourceInterface
{
    use ResourceTrait;

    public static $id = '123';
    public static $fields = ['field1', 'field2'];
    public static $extraFields = [];
    public $field1 = 'test';
    public $field2 = 2;
    public $username = '';
    public $extraField1 = 'testExtra';
    public $extraField2 = 42;

    public function getId()
    {
        return static::$id;
    }

    public function fields()
    {
        return static::$fields;
    }

    public function extraFields()
    {
        return static::$extraFields;
    }
}
