<?php

namespace OrderComment\Service;

use OrderComment\Model\OrderComment;
use Thelia\Core\HttpFoundation\Session\Session;
use Thelia\Model\OrderQuery;

readonly class OrderCommentService
{
    public function __construct(private Session $session)
    {
    }

    public function saveComment(?int $orderId, ?string $cartToken, string $comment): void
    {
        if (trim($comment) === '') {
            return;
        }

        $this->session->set('order_comment_'.$cartToken, $comment);

        if ($orderId !== null) {
            $order = OrderQuery::create()->findPk($orderId);
            if ($order) {
                $orderComment = new OrderComment();
                $orderComment
                    ->setOrderId($orderId)
                    ->setComment($comment)
                    ->save();
            }
        }
    }

}
