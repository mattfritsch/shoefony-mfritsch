<?php

declare(strict_types=1);

namespace App\Event\Store;


use App\Entity\Store\Comment;

final class CommentAdded
{
    public function __construct(
        public readonly Comment $comment,
    ) {}
}