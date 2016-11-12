<?php
/**
 * @link http://www.stombox.com/
 * @copyright Copyright (c) 2015 Stombox LLC
 * @license http://www.stombox.com/license/
 */

namespace tuyakhov\jsonapi;

use yii\db\BaseActiveRecord;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

class UpdateRelationshipAction extends Action
{
    /**
     * @param $id
     * @param $name
     * @throws BadRequestHttpException
     * @throws ServerErrorHttpException
     */
    public function run($id, $name)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->findModel($id);

        if (!$model instanceof ResourceInterface) {
            throw new BadRequestHttpException('Impossible to update relationships for resource');
        }

        $data = \Yii::$app->getRequest()->getBodyParams();
        $relationships = [];
        foreach ($data as $modelName => $identifier) {
            if (!isset($identifier['id'])) {
                continue;
            }
            /** @var BaseActiveRecord $modelName */
            if ($relationship = $modelName::findOne($identifier['id'])) {
                $relationships[] = $relationship;
            }
        }

        if (!empty($relationships)) {
            $model->unlinkAll($name);
            $model->setResourceRelationship($name, $relationships);
        }

        throw new ServerErrorHttpException('Failed to update the relationship for unknown reason.');
    }
}