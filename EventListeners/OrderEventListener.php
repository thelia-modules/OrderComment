<?php

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
        return array(
            TheliaEvents::ORDER_AFTER_CREATE => array('onOrderCreate', 128),
            TheliaEvents::ORDER_SET_DELIVERY_MODULE => array('onOrderSetDeliveryModule', 128),
        );
    }

    public function onOrderSetDeliveryModule(OrderEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $form = $request->request->get(OrderFormListener::THELIA_ORDER_DELIVERY_FORM_NAME);

        if (is_null($form) or !array_key_exists(OrderFormListener::ORDER_COMMENT_FORM_FIELD_NAME, $form)) {
            return;
        }

        $comment = $form[OrderFormListener::ORDER_COMMENT_FORM_FIELD_NAME];

        if (!empty($comment)) {
            $request->getSession()->set('order-comment', $comment);
        }
    }

    public function onOrderCreate(OrderEvent $event)
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
