<?php

/*
 * This file is part of the Thelia package.
 * http://www.thelia.net
 *
 * (c) OpenStudio <info@thelia.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace OrderComment\EventListeners;

use OrderComment\Model\OrderComment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;

class OrderEventListener implements EventSubscriberInterface
{
    public function __construct(protected RequestStack $requestStack)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            TheliaEvents::ORDER_PAY => ['onOrderPay', 100],
        ];
    }

    public function onOrderPay(OrderEvent $event): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $comment = $session->get('order_comment', null);

        $order = $event->getPlacedOrder();
        $orderId = $order->getId();

        if ($orderId != null && !empty($comment)) {
            $orderComment = new OrderComment();
            $orderComment->setOrderId($orderId);
            $orderComment->setComment($comment);
            $orderComment->save();

            $session->set('order_comment', '');
        }
    }
}
