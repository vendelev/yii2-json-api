<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */
namespace tuyakhov\jsonapi\tests\actions;

use tuyakhov\jsonapi\tests\TestCase;
use tuyakhov\jsonapi\actions\ViewRelatedAction;
use yii\base\Controller;
use tuyakhov\jsonapi\tests\data\ResourceModel;
use yii\data\ActiveDataProvider;
use \tuyakhov\jsonapi\tests\data\ActiveQuery;
use yii\web\NotFoundHttpException;

class ViewRelatedActionTest extends TestCase
{

    public function testSuccess()
    {
        $model = new ResourceModel();
        $action = new ViewRelatedAction('test', new Controller('test', \Yii::$app), [
            'modelClass' => ResourceModel::className()
        ]);
        $model->setRelation('multiple', new ActiveQuery(ResourceModel::className(), ['multiple' => true]));
        $model->setRelation('single', new ActiveQuery(ResourceModel::className()));
        $action->findModel = function ($id, $action) use($model) {
            return $model;
        };

        $this->assertInstanceOf(ActiveDataProvider::className(), $action->run(1, 'multiple'));
        $this->assertInstanceOf(ResourceModel::className(), $action->run(1, 'single'));
    }

    public function testInvalidRelation()
    {
        $action = new ViewRelatedAction('test', new Controller('test', \Yii::$app), [
            'modelClass' => ResourceModel::className()
        ]);
        $action->findModel = function ($id, $action) {
            return new ResourceModel();
        };
        $this->expectException(NotFoundHttpException::class);
        $action->run(1, 'invalid');
    }
}