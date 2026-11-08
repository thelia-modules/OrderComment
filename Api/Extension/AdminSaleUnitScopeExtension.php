<?php

declare(strict_types=1);

namespace OrderComment\Api\Extension;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use OrderComment\Api\Resource\OrderComment;
use OrderComment\Model\Map\OrderCommentTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Thelia\Api\Bridge\Propel\Extension\QueryCollectionExtensionInterface;
use Thelia\Api\Bridge\Propel\Extension\QueryItemExtensionInterface;
use Thelia\Domain\SaleUnit\Access\AdminSaleUnitScope;

/**
 * Narrows the admin order comments of an administrator restricted to some sale
 * units to the comments of the orders placed on those units. Collections lose the
 * other rows and an item out of reach answers 404, as for the orders themselves.
 *
 * An administrator attached to no unit, and a superadministrator, see everything.
 * A comment on a national order (no unit) belongs to none of a restricted
 * administrator's units, so it is left out.
 */
final readonly class AdminSaleUnitScopeExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function __construct(private AdminSaleUnitScope $scope)
    {
    }

    public function applyToCollection(ModelCriteria $query, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->restrict($query, $resourceClass, $operation);
    }

    public function applyToItem(ModelCriteria $query, string $resourceClass, ?Operation $operation = null, array $context = []): void
    {
        $this->restrict($query, $resourceClass, $operation);
    }

    private function restrict(ModelCriteria $query, string $resourceClass, ?Operation $operation): void
    {
        if (OrderComment::class !== $resourceClass) {
            return;
        }

        if (!$operation instanceof HttpOperation || !str_starts_with($operation->getUriTemplate() ?? '', '/admin')) {
            return;
        }

        $allowed = $this->scope->allowedSaleUnitIds();

        if (null === $allowed) {
            return;
        }

        // The ids are integers read from the database, never from the request.
        $query->addAnd(OrderCommentTableMap::COL_ORDER_ID, \sprintf(
            '%s IN (SELECT `order`.`id` FROM `order` WHERE `order`.`sale_unit_id` IN (%s))',
            OrderCommentTableMap::COL_ORDER_ID,
            implode(',', array_map('intval', $allowed)) ?: 'NULL',
        ), Criteria::CUSTOM);
    }
}
