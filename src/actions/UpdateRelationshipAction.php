<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi\actions;

use tuyakhov\jsonapi\ResourceInterface;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class UpdateRelationshipAction extends Action
{
    /**
     * @param $id
     * @param $name
     * @return array|null|ActiveDataProvider|\yii\db\ActiveRecord|\yii\db\ActiveRecordInterface
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     */
    public function run($id, $name)
    {
        /** @var BaseActiveRecord $model */
        $model = $this->findModel($id);

        if (!$model instanceof ResourceInterface) {
            throw new BadRequestHttpException('Impossible to update relationships for resource');
        }

        if (!$related = $model->getRelation($name, false)) {
            throw new NotFoundHttpException('Relationship does not exist');
        }
        $relatedClass = $related->modelClass;

        $data = \Yii::$app->getRequest()->getBodyParams();
        $data = ArrayHelper::isIndexed($data) ? $data : [$data];

        $ids = [];
        foreach ($data as $index => $relationshipObject) {
            if (!isset($relationshipObject['id'])) {
                continue;
            }
            $ids[] = $relationshipObject['id'];
        }
        /** @var BaseActiveRecord $relatedClass */
        $relationships = $relatedClass::find()
            ->andWhere(['in', $relatedClass::primaryKey(), $ids])
            ->all();

        if (!empty($relationships)) {
            $model->unlinkAll($name);
            $model->setResourceRelationship($name, $relationships);
        }

        if ($related->multiple) {
            return new ActiveDataProvider([
                'query' => $related
            ]);
        } else {
            return $related->one();
        }
    }
}