<?php
/**
 * @author Anton Tuyakhov <atuyakhov@gmail.com>
 */

namespace tuyakhov\jsonapi;

interface ResourceIdentifierInterface
{
    public function getId();

    public function getType();
}
