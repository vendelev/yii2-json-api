<?php
/**
 * @link http://www.stombox.com/
 * @copyright Copyright (c) 2015 Stombox LLC
 * @license http://www.stombox.com/license/
 */

namespace tuyakhov\jsonapi;

use \yii\web\Linkable;

interface LinksInterface extends Linkable
{
    public function getRelationshipLinks($name);
}