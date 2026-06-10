<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace OrderComment\Hook;

use OrderComment\Model\OrderCommentQuery;
use Thelia\Core\Event\Hook\HookRenderEvent;
use Thelia\Core\Hook\BaseHook;

/**
 * Class BackHook
 * @package OrderComment\Hook
 * @author Etienne Perriere <eperriere@openstudio.fr>
 */
class BackHook extends BaseHook
{
    public static function getSubscribedHooks(): array
    {
        return [
            'order-edit.after-order-product-list' => [
                ['type' => 'back', 'method' => 'onOrderEditAfterOrderProductList'],
            ],
            'order-edit.bill-bottom' => [
                ['type' => 'back', 'method' => 'onOrderEditBillBottom'],
            ],
            'order.tab-content' => [
                ['type' => 'back', 'method' => 'onOrderTabContent'],
            ],
            'module.configuration' => [
                ['type' => 'back', 'method' => 'onModuleConfiguration'],
            ],
        ];
    }

    public function onModuleConfiguration(HookRenderEvent $event): void
    {
        $event->add($this->render('OrderComment/module-configuration.html.twig'));
    }

    public function onOrderEditAfterOrderProductList(HookRenderEvent $event): void
    {
        $event->add($this->renderComment($event));
    }

    public function onOrderEditBillBottom(HookRenderEvent $event): void
    {
        $event->add($this->renderComment($event));
    }

    public function onOrderTabContent(HookRenderEvent $event): void
    {
        $event->add($this->renderComment($event));
    }

    private function renderComment(HookRenderEvent $event): string
    {
        $orderId = (int) $event->getArgument('order_id', null);

        $orderComment = $orderId > 0
            ? OrderCommentQuery::create()->filterByOrderId($orderId)->findOne()
            : null;

        return $this->render('OrderComment/order-edit.html.twig', [
            'comment' => $orderComment?->getComment(),
        ]);
    }
}
