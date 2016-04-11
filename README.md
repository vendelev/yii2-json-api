
Implementation of JSON API specification for the Yii framework
==================================================================
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tuyakhov/yii2-youtube "dev-master"
```

or add

```
"tuyakhov/yii2-json-api": "dev-master"
```

to the require section of your `composer.json` file.


Usage
==============================
This implementation is not complete yet. Yii2 JSON Api extension is still under hard development process. [Content Negotiation](http://jsonapi.org/format/#content-negotiation) and [Document Structure](http://jsonapi.org/format/#document-structure) are only covered at the moment.<br/>
Once the extension is installed, simply use it in your code by  :
Data Serializing and Content Negotiation:
-------------------------------------------
Controller:
```php
class Controller extends \yii\rest\Controller
{
    public $serializer = 'tuyakhov\jsonapi\Serializer';
    
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/vnd.api+json' => Response::FORMAT_JSON,
                ],
            ]
        ]);
    }
}
```
Model:
```php
use tuyakhov\jsonapi\ResourceTrait;

class User extends ActiveRecord
{
    use ResourceTrait;
}
```
Enabling JSON Input
---------------------------
To let the API accept input data in JSON format, configure the [[yii\web\Request::$parsers|parsers]] property of the request application component to use the [[tuyakhov\jsonapi\JsonParser]] for JSON input
```php
'request' => [
  'parsers' => [
      'application/vnd.api+json' => 'tuyakhov\jsonapi\JsonApiParser',
  ]
]
```
