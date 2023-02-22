<?php

declare(strict_types=1);

namespace App\Manager\Store;

use App\Entity\Store\Comment;
use App\Entity\Store\Product;
use App\Event\Store\CommentAdded;
use App\Repository\Store\CommentRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

final class ProductManager
{
    public function __construct(
        private CommentRepository $commentRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function addComment(Comment $comment, Product $product): void
    {
        $comment->setProduct($product);
        $this->commentRepository->save($comment, true);

        $this->eventDispatcher->dispatch(new CommentAdded($comment));
    }

}