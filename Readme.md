# OrderComment

This module allows your customers to write a comment for an order. Then you can find this comment in the order detail page of the backoffice.

![OrderComment preview](preview.png)

## Installation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is OrderComment.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer require thelia/order-comment-module:~1.0
```

## Loop

OrderCommentLoop : order.comment.comment

This loop return the comment of an order.

### Input arguments

|Argument |Description |
|---      |--- |
|**order_id** | Unique ID of the order (eg. order_id=5) |

### Output arguments

|Variable   |Description |
|---        |--- |
|$ORDER_COMMENT    | Content of the comment |

### Example

```
{ifloop rel="order_comment_loop"}
    {loop name="order_comment_loop" type="order.comment.comment" order_id="$order_id" }
        {$ORDER_COMMENT}
    {/loop}
{/ifloop}
{elseloop rel="order_comment_loop"}
    <p class="alert alert-warning">
        {intl l="No comment for this order was defined." d="ordercomment"}
    </p>
{/elseloop}
```

## Other ?

It may be convenient to display the comment on the detail page of the customer's order.
To do that, uses the loop described previously.