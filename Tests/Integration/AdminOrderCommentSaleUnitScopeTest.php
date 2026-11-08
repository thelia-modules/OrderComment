<?php

declare(strict_types=1);

namespace OrderComment\Tests\Integration;

use ApiPlatform\Metadata\GetCollection;
use OrderComment\Api\Extension\AdminSaleUnitScopeExtension;
use OrderComment\Api\Resource\OrderComment as OrderCommentResource;
use OrderComment\Model\OrderComment;
use OrderComment\Model\OrderCommentQuery;
use Thelia\Core\Security\AccessManager;
use Thelia\Core\Security\Resource\AdminResources;
use Thelia\Domain\SaleUnit\Access\AdminSaleUnitScope;
use Thelia\Model\Admin;
use Thelia\Test\ActionIntegrationTestCase;
use Thelia\Test\Trait\LogsInAsAdmin;

/**
 * The admin order comments of an administrator restricted to some sale units are
 * those of the orders placed on them.
 */
final class AdminOrderCommentSaleUnitScopeTest extends ActionIntegrationTestCase
{
    use LogsInAsAdmin;

    private int $mine;
    private int $theirs;
    private int $national;
    private Admin $restricted;

    protected function setUp(): void
    {
        parent::setUp();

        $first = $this->factory->saleUnit();
        $second = $this->factory->saleUnit();
        $connection = $this->getPropelConnection();

        $myOrder = $this->factory->order()->setSaleUnitId($first->getId());
        $myOrder->save($connection);
        $theirOrder = $this->factory->order()->setSaleUnitId($second->getId());
        $theirOrder->save($connection);
        $nationalOrder = $this->factory->order();

        $this->mine = $this->comment($myOrder->getId());
        $this->theirs = $this->comment($theirOrder->getId());
        $this->national = $this->comment($nationalOrder->getId());

        $this->restricted = $this->factory->restrictedAdmin([AdminResources::ORDER => [AccessManager::VIEW]]);
        $this->factory->adminSaleUnit($this->restricted, $first);
    }

    protected function tearDown(): void
    {
        $this->getService(AdminSaleUnitScope::class)->reset();
        $this->logoutAdmin();

        parent::tearDown();
    }

    public function testARestrictedAdministratorReadsTheCommentsOfItsUnitsOnly(): void
    {
        $this->loginAsAdminInSession($this->restricted);

        self::assertSame([$this->mine], $this->ids('/admin/order-comments'));
    }

    public function testAnItemOfAnotherUnitIsOutOfReach(): void
    {
        $this->loginAsAdminInSession($this->restricted);

        self::assertSame([], $this->ids('/admin/order-comments', [$this->theirs], true));
        self::assertSame([], $this->ids('/admin/order-comments', [$this->national], true));
        self::assertSame([$this->mine], $this->ids('/admin/order-comments', [$this->mine], true));
    }

    public function testAnAdministratorOfNoUnitReadsEverything(): void
    {
        $this->loginAsAdminInSession($this->factory->restrictedAdmin([AdminResources::ORDER => [AccessManager::VIEW]]));

        self::assertSame([$this->mine, $this->theirs, $this->national], $this->ids('/admin/order-comments'));
    }

    public function testTheFrontCollectionIsLeftAlone(): void
    {
        $this->loginAsAdminInSession($this->restricted);

        self::assertSame([$this->mine, $this->theirs, $this->national], $this->ids('/front/order-comments'));
    }

    private function comment(int $orderId): int
    {
        $comment = (new OrderComment())->setOrderId($orderId)->setComment('comment');
        $comment->save($this->getPropelConnection());

        return $comment->getId();
    }

    /**
     * @param list<int>|null $among
     *
     * @return list<int>
     */
    private function ids(string $uriTemplate, ?array $among = null, bool $item = false): array
    {
        $among ??= [$this->mine, $this->theirs, $this->national];
        $query = OrderCommentQuery::create()->filterById($among)->orderById();
        $extension = new AdminSaleUnitScopeExtension($this->getService(AdminSaleUnitScope::class));
        $operation = new GetCollection(uriTemplate: $uriTemplate);

        $item
            ? $extension->applyToItem($query, OrderCommentResource::class, $operation)
            : $extension->applyToCollection($query, OrderCommentResource::class, $operation);

        return array_map('intval', $query->select(['Id'])->find($this->getPropelConnection())->getData());
    }
}
