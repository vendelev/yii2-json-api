
Implementation of JSON API specification for the Yii framework
==================================================================
[![Latest Stable Version](https://poser.pugx.org/tuyakhov/yii2-json-api/v/stable.png)](https://packagist.org/packages/tuyakhov/yii2-json-api)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tuyakhov/yii2-json-api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-json-api/?branch=master) [![Build Status](https://scrutinizer-ci.com/g/tuyakhov/yii2-json-api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tuyakhov/yii2-json-api/build-status/master)
[![Total Downloads](https://poser.pugx.org/tuyakhov/yii2-json-api/downloads.png)](https://packagist.org/packages/tuyakhov/yii2-json-api)
Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist tuyakhov/yii2-json-api "*"
```

or add

```
"tuyakhov/yii2-json-api": "*"
```

to the require section of your `composer.json` file.


Usage
==============================
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
Defining models:

1) Let's define `User` model and declare an `articles` relation 
```php
use tuyakhov\jsonapi\ResourceTrait;
use tuyakhov\jsonapi\ResourceInterface;

class User extends ActiveRecord implements ResourceInterface
{
    use ResourceTrait;
    
    public function getArticles()
    {
        return $this->hasMany(Article::className(), ['author_id' => 'id']);
    }
}
```
2) Now we need to define `Article` model
```php
use tuyakhov\jsonapi\ResourceTrait;
use tuyakhov\jsonapi\ResourceInterface;

class Article extends ActiveRecord implements ResourceInterface
{
    use ResourceTrait;
}
```
3) As the result `User` model will be serialized into the proper json api resource object:
```javascript
{
  "data": {
    "type": "users",
    "id": "1",
    "attributes": {
      // ... this user's attributes
    },
    "relationships": {
      "articles": {
        // ... this user's articles
      }
    }
  }
}
```
Enabling JSON Input
---------------------------
To let the API accept input data in JSON API format, configure the [[yii\web\Request::$parsers|parsers]] property of the request application component to use the [[tuyakhov\jsonapi\JsonApiParser]] for JSON input
```php
'request' => [
  'parsers' => [
      'application/vnd.api+json' => 'tuyakhov\jsonapi\JsonApiParser',
  ]
]
```
