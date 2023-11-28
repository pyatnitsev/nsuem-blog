<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Article;
use App\Message\ArticleNotification;
use App\MessageHandler\ArticleMessageHandler;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Article::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Article::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Article::class)]
class ArticleEntityListener
{
    public function __construct(
        private readonly Security $security,
        private readonly MessageBusInterface $bus
    )
    {
    }

    public function prePersist(\App\Entity\Article $entity): void
    {
        $entity->setCreatedAt(new \DateTimeImmutable())
            ->setAuthor($this->security->getUser());
    }

    public function postPersist(\App\Entity\Article $entity)
    {
        $this->bus->dispatch(new ArticleNotification(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getBody(),
            $entity->getAuthor()->getId(),
            true,
        ));
    }

    public function preUpdate(\App\Entity\Article $entity, PreUpdateEventArgs $event): void
    {
        $this->bus->dispatch(new ArticleNotification(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getBody(),
            $entity->getAuthor()->getId(),
            false,
        ));
    }
}