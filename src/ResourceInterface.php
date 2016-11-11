<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi;

interface ResourceInterface extends ResourceIdentifierInterface
{
    public function getResourceAttributes(array $fields = []);

    public function getResourceRelationships();

    public function setResourceRelationship($name, $relationship);

    public function getLinks();

    public function getMeta();

}
