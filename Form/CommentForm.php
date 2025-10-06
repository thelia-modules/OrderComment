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
                    'required' => true,
                    'label' => 'Date et heure du retrait',
                    'attr' => [
                        'placeholder' => 'Indiquez nous la date el l\'heure souhaitées pour le retrait de votre commande',
                    ]
                ]
            )
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'size' => 'medium'
                ],
                'row_attr' => [
                    'class' => 'flex justify-end',
                ]
            ]);
    }

    public static function getName(): string
    {
        return 'order_comment_form';
    }
}
