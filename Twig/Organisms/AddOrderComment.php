<?php

namespace OrderComment\Twig\Organisms;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use OrderComment\Service\OrderCommentService;
use Thelia\Core\Form\FormServiceInterface;
use Symfony\UX\LiveComponent\ComponentToolsTrait;

#[AsLiveComponent(name: 'AddOrderComment', template: '@OrderCommentModule/components/AddOrderComment.html.twig')]
class AddOrderComment extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;
    use ComponentToolsTrait;

    #[LiveProp(writable: true)]
    public string $comment = '';

    public ?int $cartId = null;

    public bool $message = false;

    public function __construct(
        private readonly FormServiceInterface $formService,
        private readonly RequestStack $requestStack,
        private readonly OrderCommentService $orderCommentService
    ) {
    }

    public function mount(): void
    {
        $session = $this->requestStack->getSession();
        $existingComment = $session->get('order_comment', '');

        if ($existingComment && empty($this->comment)) {
            $this->comment = $existingComment;
        }
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->formService->getFormByName('order_comment_form');
    }

    #[LiveAction]
    public function save(): void
    {
        try {
            $this->submitForm();
            if ($this->getForm()->isSubmitted() && $this->getForm()->isValid()) {
                $data = $this->getForm()->getData();

                $this->comment = $data['comment'];
                $this->orderCommentService->saveComment($this->comment);
                $this->message = true;
                $this->emit('updateNextButton');

            }
        } catch (\Exception $e) {
            throw new \RuntimeException($e->getMessage());
            $this->message = false;

        }
    }
}
