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
    /** @var RequestStack */
    protected $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::ORDER_PRODUCT_AFTER_CREATE => ['onOrderCreate', 128],
            TheliaEvents::ORDER_SET_DELIVERY_MODULE => ['onOrderSetDeliveryModule', 128],
        ];
    }

    public function onOrderSetDeliveryModule(OrderEvent $event): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $form = $request->get(OrderFormListener::THELIA_ORDER_DELIVERY_FORM_NAME);

        if (null === $form || !\array_key_exists(OrderFormListener::ORDER_COMMENT_FORM_FIELD_NAME, $form)) {
            return;
        }

        $comment = $form[OrderFormListener::ORDER_COMMENT_FORM_FIELD_NAME];

        if (!empty($comment)) {
            $request->getSession()->set('order-comment', $comment);
        }
    }

    public function onOrderCreate(OrderEvent $event): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $comment = $session->get('order-comment', null);

        $order = $event->getOrder();
        $orderId = $order->getId();

        if ($orderId != null && !empty($comment)) {
            $orderComment = new OrderComment();
            $orderComment->setOrderId($orderId);
            $orderComment->setComment($comment);
            $orderComment->save();

            $session->set('order-comment', '');
        }
    }
}
