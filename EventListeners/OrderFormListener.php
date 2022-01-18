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

use OrderComment\Form\CommentForm;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\TheliaFormEvent;

class OrderFormListener implements EventSubscriberInterface
{
    /** 'thelia_order_delivery' is the name of the form used to choose delivery (Thelia\Form\OrderDelivery). */
    const THELIA_ORDER_DELIVERY_FORM_NAME = 'thelia_order_delivery';

    const ORDER_COMMENT_FORM_FIELD_NAME = 'comment';

    public static function getSubscribedEvents()
    {
        return [
            TheliaEvents::FORM_AFTER_BUILD.'.'.self::THELIA_ORDER_DELIVERY_FORM_NAME => ['addCommentFieldForDelivery', 128],
        ];
    }

    /**
     * Callback used to add a comment field to the Thelia's OrderDelivery form.
     */
    public function addCommentFieldForDelivery(TheliaFormEvent $event): void
    {
        CommentForm::addCommentFormField($event->getForm()->getFormBuilder());
    }
}
