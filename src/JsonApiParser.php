<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi;

use \yii\web\JsonParser;

class JsonApiParser extends JsonParser
{
    public function parse($rawBody, $contentType)
    {
        // TODO probably need to validate 'type' property.
        return parent::parse($rawBody, $contentType);
    }
}
