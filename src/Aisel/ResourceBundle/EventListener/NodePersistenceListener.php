<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\ResourceBundle\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Doctrine\ODM\MongoDB\DocumentManager;
use Aisel\ResourceBundle\Document\NodeInterface;
use Aisel\ResourceBundle\Document\Node;

/**
 * Class NodePersistenceListener.
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class NodePersistenceListener
{

    /**
     * @param LifecycleEventArgs $args
     */
    public function postUpdate(LifeCycleEventArgs $args)
    {
        $this->updateChildren($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function postPersist(LifeCycleEventArgs $args)
    {
        $this->updateChildren($args);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    private function updateChildren(LifeCycleEventArgs $args)
    {
        $dm = $args->getDocumentManager();

        /** @var NodeInterface $object */
        /** @var Node $parent */
        /** @var Node $child */
        $object = $args->getDocument();
        if ($object instanceof NodeInterface) {

            if ($parent = $object->getParent()) {

                foreach ($parent->getChildren() as $child) {

                    if ($child->getId() == $object->getId()) {
                        $parent->removeChild($child);
                    }
                }
                $parent->addChild($object);
                $dm->persist($parent);
                $dm->flush();
            }
        }
    }
}