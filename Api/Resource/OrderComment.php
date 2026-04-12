<?php

namespace OrderComment\Api\Resource;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation;
use ApiPlatform\OpenApi\Model\Parameter;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\Ignore;
use Thelia\Api\Bridge\Propel\Filter\SearchFilter;
use Thelia\Api\Resource\PropelResourceInterface;
use Thelia\Api\Resource\PropelResourceTrait;
use OrderComment\Model\Map\OrderCommentTableMap;
use Propel\Runtime\Map\TableMap;

#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/admin/order-comments',
            openapi: new Operation(parameters: [
                new Parameter(name: 'order_id', in: 'query', description: 'Filter comments by order ID', required: false, schema: ['type' => 'integer']),
            ]),
            paginationEnabled: false,
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_ADMIN_READ]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/order-comments',
            openapi: new Operation(parameters: [
                new Parameter(name: 'order_id', in: 'query', description: 'Filter comments by order ID', required: false, schema: ['type' => 'integer']),
            ]),
            paginationEnabled: false,
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_FRONT_READ]]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/front/session/order-comment',
            openapi: new Operation(parameters: [
                new Parameter(name: 'order_id', in: 'query', description: 'Filter comments by order ID', required: false, schema: ['type' => 'integer']),
            ]),
            paginationEnabled: false,
        ),
    ],
    normalizationContext: ['groups' => [self::GROUP_FRONT_READ]]
)]
class OrderComment implements PropelResourceInterface
{
    use PropelResourceTrait;

    public const GROUP_ADMIN_READ = 'admin:order_comment:read';
    public const GROUP_FRONT_READ = 'front:order_comment:read';

    #[Groups([self::GROUP_ADMIN_READ])]
    public ?int $id = null;

    #[Groups([self::GROUP_ADMIN_READ])]
    public ?int $orderId = null;

    #[Groups([self::GROUP_ADMIN_READ])]
    public ?string $comment = null;

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public function setOrderId(?int $orderId): self
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @throws PropelException
     */
    #[Ignore]
    public static function getPropelRelatedTableMap(): ?TableMap
    {
        return OrderCommentTableMap::getTableMap();
    }
}
