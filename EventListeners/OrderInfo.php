<?php
/**
 * Created by PhpStorm.
 * User: vincent
 * Date: 14/10/2014
 * Time: 15:17
 */

namespace OrderComment\EventListeners;

use OrderComment\Model\OrderComment;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;

class OrderInfo implements EventSubscriberInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function getSubscribedEvents()
    {
        return [TheliaEvents::ORDER_AFTER_CREATE => 'saveComment'];
    }

    public function saveComment(OrderEvent $orderEvent)
    {
        $comment = $this->request->getSession()->get('order-comment');

        $order = $orderEvent->getOrder();
        $orderId = $order->getId();

        if ($orderId != null && $comment != null) {
            $orderComment = new OrderComment();
            $orderComment->setOrderId($orderId);
            $orderComment->setComment($comment);

            $orderComment->save();

            $this->request->getSession()->set('order-comment', '');
        }

    }
}
