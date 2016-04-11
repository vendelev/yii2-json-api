<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi\tests\data;

use tuyakhov\jsonapi\ResourceIdentifierInterface;
use tuyakhov\jsonapi\ResourceTrait;
use yii\base\Model;

class ResourceModel extends Model implements ResourceIdentifierInterface
{
    use ResourceTrait;

    public $testAttribute = 'testAttribute';

    public function getTestRelation()
    {
        return new self;
    }

    public function extraFields()
    {
        return ['testRelation'];
    }
}
