<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi\tests;


use tuyakhov\jsonapi\JsonApiParser;
use yii\helpers\Json;

class JsonApiParserTest extends TestCase
{
    public function testParse()
    {
        $parser = new JsonApiParser();
        $body = Json::encode([
           'data' => [
               'id' => '123',
               'type' => 'resource-models',
               'attributes' => [
                   'field1' => 'test',
                   'field2' => 2,
                   'first-name' => 'Bob'
               ],
               'relationships' => [
                   'author' => [
                       'id' => '123',
                       'type' => 'resource-models'
                   ]
               ]
           ]
        ]);
        $this->assertEquals([
            'ResourceModel' => [
                'field1' => 'test',
                'field2' => 2,
                'first_name' => 'Bob',
            ],
            'author' => [
                'ResourceModel' => [
                    'id' => '123',
                    'type' => 'resource-models'
                ]
            ]
        ], $parser->parse($body, ''));
    }
}