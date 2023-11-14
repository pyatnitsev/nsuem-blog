<?php

declare(strict_types=1);

namespace App\Message;
class ArticleNotification
{
    public function __construct(
        private readonly int $articleId,
        private readonly string $title,
        private readonly string $body,
        private readonly int $authorId,
        private readonly bool $isCreation,
    )
    {
    }

    public function getArticleId(): int
    {
        return $this->articleId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getAuthorId(): int
    {
        return $this->authorId;
    }

    public function isCreation(): bool
    {
        return $this->isCreation;
    }
}