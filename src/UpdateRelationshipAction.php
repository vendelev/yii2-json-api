<?php
/**
 * @link http://www.stombox.com/
 * @copyright Copyright (c) 2015 Stombox LLC
 * @license http://www.stombox.com/license/
 */

namespace tuyakhov\jsonapi;


use yii\helpers\Inflector;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

class UpdateRelationshipAction extends Action
{
    public function run($id, $name)
    {
        $model = $this->findModel($id);

        if ($model instanceof ResourceInterface) {
            \Yii::$app->getRequest()->getBodyParams();
        }

        throw new ServerErrorHttpException('Failed to update the relationship for unknown reason.');
    }
}