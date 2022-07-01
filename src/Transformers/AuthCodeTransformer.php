<?php

declare(strict_types=1);

namespace Cortex\Oauth\Transformers;

use Cortex\Oauth\Models\AuthCode;
use Rinvex\Support\Traits\Escaper;
use League\Fractal\TransformerAbstract;

class AuthCodeTransformer extends TransformerAbstract
{
    use Escaper;

    /**
     * List of resources to automatically include.
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'client',
        'user',
    ];

    /**
     * Transform auth code model.
     *
     * @param \Cortex\Oauth\Models\AuthCode $authCode
     *
     * @throws \Exception
     *
     * @return array
     */
    public function transform(AuthCode $authCode): array
    {
        return $this->escape([
            'id' => (string) $authCode->getRouteKey(),
            'user_type' => (string) $authCode->user_type,
            'abilities' => (string) $authCode->abilities->isNotEmpty() ? $authCode->abilities->map->title->all() : [],
            'is_revoked' => (bool) $authCode->is_revoked,
            'expires_at' => (string) $authCode->expires_at,
            'created_at' => (string) $authCode->created_at,
            'updated_at' => (string) $authCode->updated_at,
        ]);
    }

    /**
     * Include Author.
     *
     * @param \Cortex\Oauth\Models\AuthCode $authCode
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeClient(AuthCode $authCode)
    {
        $client = $authCode->client;

        return $this->item($client, new ClientTransformer());
    }

    /**
     * Include Author.
     *
     * @param \Cortex\Oauth\Models\AuthCode $authCode
     *
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(AuthCode $authCode)
    {
        $user = $authCode->user;
        $transformer = '\Cortex\Auth\Transformers\\'.ucwords($authCode->user_type).'Transformer';

        return $this->item($user, new $transformer());
    }
}
