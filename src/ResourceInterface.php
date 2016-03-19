<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi;


interface ResourceInterface extends ResourceIdentifierInterface
{
    public function getAttributes(array $fields = []);

    public function getRelationships();

    public function getLinks();

    public function getMeta();

}