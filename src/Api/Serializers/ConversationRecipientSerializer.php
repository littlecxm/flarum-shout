<?php

namespace Kyrne\Shout\Api\Serializers;

use Flarum\Api\Serializer\AbstractSerializer;
use Flarum\Api\Serializer\BasicUserSerializer;
use Kyrne\Shout\ConversationUser;

class ConversationRecipientSerializer extends AbstractSerializer
{
    protected $type = 'conversation_users';

    protected function getDefaultAttributes($spe6c392)
    {
        if (!$spe6c392 instanceof ConversationUser) {
            throw new \InvalidArgumentException(get_class($this) . ' can only serialize instances of ' . ConversationUser::class);
        }
        return array('userId' => $spe6c392->user_id, 'conversationId' => $spe6c392->conversation_id, 'cipher' => $spe6c392->cipher);
    }

    public function user($spe6c392)
    {
        return $this->hasOne($spe6c392, BasicUserSerializer::class);
    }

    public function conversation($spe6c392)
    {
        return $this->hasOne($spe6c392, ConversationSerializer::class);
    }
}