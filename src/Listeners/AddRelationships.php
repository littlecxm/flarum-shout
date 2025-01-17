<?php

namespace Kyrne\Shout\Listeners;

use Flarum\Api\Controller;
use Flarum\Api\Event\Serializing;
use Flarum\Api\Event\WillGetData;
use Flarum\Api\Serializer;
use Flarum\Event\GetApiRelationship;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;
use Illuminate\Contracts\Events\Dispatcher;
use Kyrne\Shout\Api\Serializers\ConversationRecipientSerializer;
use Kyrne\Shout\Encryption;

class AddRelationships
{
    protected $settings;

    public function __construct(SettingsRepositoryInterface $sp82b472)
    {
        $this->settings = $sp82b472;
    }

    public function subscribe(Dispatcher $sp0f73de)
    {
        $sp0f73de->listen(GetApiRelationship::class, array($this, 'getApiAttributes'));
        $sp0f73de->listen(Serializing::class, array($this, 'prepareApiAttributes'));
        $sp0f73de->listen(WillGetData::class, array($this, 'includeData'));
    }

    public function prepareApiAttributes(Serializing $sp01476c)
    {
        if ($sp01476c->isSerializer(Serializer\ForumSerializer::class)) {
            $sp01476c->attributes['canMessage'] = $sp01476c->actor->can('startConversation');
            $sp01476c->attributes['shoutOwnPassword'] = (bool)$this->settings->get('kyrne-shout.set_own_password');
            $sp01476c->attributes['shoutReturnKey'] = (bool)$this->settings->get('kyrne-shout.return_key');
        }
        if ($sp01476c->isSerializer(Serializer\BasicUserSerializer::class)) {
            $newEncryption = Encryption::where('user_id', $sp01476c->model->id)->first();
            $sp01476c->attributes['PMSetup'] = (bool)$newEncryption;
            $sp01476c->attributes['PrekeysExhausted'] = (bool)$newEncryption ? $newEncryption->prekeys_exhausted : false;
        }
        if ($sp01476c->isSerializer(Serializer\CurrentUserSerializer::class)) {
            $newEncryption = Encryption::where('user_id', $sp01476c->model->id)->first();
            $sp01476c->attributes['unreadMessages'] = $sp01476c->model->unread_messages;
            $newEncryption ? $sp01476c->attributes['PrekeyIndex'] = $newEncryption->prekey_index : 0;
        }
    }

    public function getApiAttributes(GetApiRelationship $sp01476c)
    {
        if ($sp01476c->isRelationship(Serializer\CurrentUserSerializer::class, 'conversations')) {
            return $sp01476c->serializer->hasMany($sp01476c->model, ConversationRecipientSerializer::class, 'conversations');
        }
    }

    public function includeData(WillGetData $sp01476c)
    {
        if ($sp01476c->isController(Controller\ListUsersController::class) || $sp01476c->isController(Controller\ShowUserController::class) || $sp01476c->isController(Controller\CreateUserController::class) || $sp01476c->isController(Controller\UpdateUserController::class)) {
            $sp01476c->addInclude(array('conversations'));
        }
    }
}