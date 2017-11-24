<?php

namespace OrderComment\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Thelia\Form\BaseForm;

class CommentForm extends BaseForm
{
    public function buildForm()
    {
        self::addCommentFormField($this->formBuilder);
    }

    public static function addCommentFormField(FormBuilderInterface $formBuilder)
    {
        $formBuilder
            ->add(
                'comment',
                'textarea',
                array(
                    'required' => false
                )
            );
    }

    public function getName()
    {
        return "order_comment_form";
    }
}
