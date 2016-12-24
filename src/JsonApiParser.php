<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi;

use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use \yii\web\JsonParser;

class JsonApiParser extends JsonParser
{
    /**
     * Converts 'type' member to form name
     * If not set, type will be converted to singular form.
     * For example, 'articles' will be converted to 'Article'
     * @var callable
     */
    public $formNameCallback;

    /**
     * Converts member names to variable names
     * If not set, all special characters will be replaced by underscore
     * For example, 'first-name' will be converted to 'first_name'
     * @var callable
     */
    public $memberNameCallback;

    /**
     * Parse resource object into the input data to populates the model
     * @inheritdoc
     */
    public function parse($rawBody, $contentType)
    {
        $array = parent::parse($rawBody, $contentType);
        if ($type = ArrayHelper::getValue($array, 'data.type')) {
            $formName = $this->typeToFormName($type);
            if ($attributes = ArrayHelper::getValue($array, 'data.attributes')) {
                $result[$formName] = array_combine($this->parseMemberNames(array_keys($attributes)), array_values($attributes));
            } elseif ($id = ArrayHelper::getValue($array, 'data.id')) {
                $result[$formName] = ['id' => $id, 'type' => $type];
            }
            if ($relationships = ArrayHelper::getValue($array, 'data.relationships')) {
                foreach ($relationships as $name => $relationship) {
                    if (isset($relationship[0])) {
                        foreach ($relationship as $item) {
                            if (isset($item['type']) && isset($item['id'])) {
                                $formName = $this->typeToFormName($item['type']);
                                $result[$name][$formName][] = $item;
                            }
                        }
                    } elseif (isset($relationship['type']) && isset($relationship['id'])) {
                        $formName = $this->typeToFormName($relationship['type']);
                        $result[$name][$formName] = $relationship;
                    }
                }
            }
        } else {
            $data = ArrayHelper::getValue($array, 'data', []);
            foreach ($data as $relationLink) {
                if (isset($relationLink['type']) && isset($relationLink['id'])) {
                    $formName = $this->typeToFormName($relationLink['type']);
                    $result[$formName][] = $relationLink;
                }
            }
        }
        return isset($result) ? $result : $array;
    }

    /**
     * @param $type 'type' member of the document
     * @return string form name
     */
    protected function typeToFormName($type)
    {
        if ($this->formNameCallback !== null) {
            return call_user_func($this->formNameCallback, $type);
        }
        return Inflector::id2camel(Inflector::singularize($type));
    }

    /**
     * @param array $memberNames
     * @return array variable names
     */
    protected function parseMemberNames(array $memberNames = [])
    {
        $callback = $this->memberNameCallback !== null ? $this->memberNameCallback : function($name) {
            return str_replace(' ', '_', preg_replace('/[^A-Za-z0-9]+/', ' ', $name));
        };
        return array_map($callback, $memberNames);
    }
}
