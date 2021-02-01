<?php
namespace Kyrne\Shout\Api\Controllers; use Flarum\Api\Controller\AbstractCreateController; use Flarum\Http\AccessToken; use Illuminate\Contracts\Bus\Dispatcher; use Kyrne\Shout\Api\Serializers\KeySerializer; use Kyrne\Shout\Commands\UpdateKeys; use Psr\Http\Message\ServerRequestInterface; use Tobscure\JsonApi\Document; class UpdateKeysController extends AbstractCreateController { public $serializer = KeySerializer::class; protected $bus; public function __construct(Dispatcher $sp9afba9) { $this->bus = $sp9afba9; } protected function data(ServerRequestInterface $sp00f8d1, Document $speb2504) { $sp63f786 = $sp00f8d1->getAttribute('actor'); return $this->bus->dispatch(new UpdateKeys($sp63f786, $sp00f8d1->getParsedBody())); } }