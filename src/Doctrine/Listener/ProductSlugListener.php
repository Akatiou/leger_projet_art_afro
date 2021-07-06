<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    public function PrePersist(LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof Product) {
            return;
        }

        if (empty($entity->getSlug())) {
            //calculer le slug
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}
