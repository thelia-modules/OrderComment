<?php

namespace OrderComment\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use OrderComment\Api\State\SessionOrderCommentProvider;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/session/order-comment',
            paginationEnabled: false,
            provider: SessionOrderCommentProvider::class
        )
    ],
    normalizationContext: ['groups' => [self::GROUP_FRONT_READ]]
)]
class SessionOrderComment
{
    use PropelResourceTrait;

    public const GROUP_FRONT_READ = 'front:order_comment:session:read';

    #[Groups([self::GROUP_FRONT_READ])]
    public ?string $comment = null;
}
