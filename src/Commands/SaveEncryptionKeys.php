<?php

namespace Kyrne\Shout\Commands;

use Flarum\User\User;

class SaveEncryptionKeys
{
    public $actor;
    public $data;

    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }
}