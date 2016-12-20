<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi\actions;

use tuyakhov\jsonapi\ResourceInterface;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\rest\Action;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * UpdateRelationshipAction implements the API endpoint for updating relationships.
 * @link http://jsonapi.org/format/#crud-updating-relationships
 */
class UpdateRelationshipAction extends Action
{
    /**
     * Prepares the relationships to link with primary model.
     * @var callable
     */
    public $prepareRelationships;
    /**
     * Update of relationships independently.
     * @param string $id an ID of the primary resource
     * @param string $name a name of the related resource
     * @return ActiveDataProvider|BaseActiveRecord
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

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model, $name);
        }

        $relationships = $this->prepareRelationships($related);

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

    protected function prepareRelationships($related)
    {
        if ($this->prepareRelationships !== null) {
            return call_user_func($this->prepareRelationships, $this, $related);
        }

        /** @var BaseActiveRecord $relatedClass */
        $relatedClass = new $related->modelClass;

        $data = \Yii::$app->getRequest()->getBodyParams();
        
        $data = ArrayHelper::keyExists($relatedClass->formName(), $data) ? $data[$relatedClass->formName()] : [];
        
        if (!ArrayHelper::isIndexed($data)) {
            $data = [$data];
        }

        $ids = [];
        foreach ($data as $index => $relationshipObject) {
            if (!isset($relationshipObject['id'])) {
                continue;
            }
            $ids[] = $relationshipObject['id'];
        }

        return $relatedClass::find()
            ->andWhere(['in', $relatedClass::primaryKey(), $ids])
            ->all();
    }
}