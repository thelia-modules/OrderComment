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

namespace OrderComment\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Thelia\Form\BaseForm;

class CommentForm extends BaseForm
{
    public function buildForm(): void
    {
        self::addCommentFormField($this->formBuilder);
    }

    public static function addCommentFormField(FormBuilderInterface $formBuilder): void
    {
        $formBuilder
            ->add(
                'comment',
                TextareaType::class,
                [
                    'required' => false,
                ]
            );
    }

    public static function getName()
    {
        return 'order_comment_form';
    }
}
