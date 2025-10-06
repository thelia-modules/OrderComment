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

    public function saveComment(string $comment): void
    {
        $this->session->set('order_comment', $comment);
    }
}
