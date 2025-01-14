<?php

/*
 * This file is part of the Aisel package.
 *
 * (c) Ivan Proskuryakov
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Aisel\PageBundle\Tests\EventListener;

use Aisel\ResourceBundle\Tests\AbstractWebTestCase;
use Aisel\PageBundle\Document\Page;

/**
 * PageUrlPersistenceListenerTest
 *
 * @author Ivan Proskuryakov <volgodark@gmail.com>
 */
class PageUrlPersistenceListenerTest extends AbstractWebTestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testDuplicatedUrlThrowsError()
    {
        $urlText = 'page-test-meta-url';
        $this->setExpectedException(
            'LogicException', 'Given URL already exists'
        );

        // Create Page 1
        $page1 = new Page();
        $page1->setLocale('en');
        $page1->setContent('...');
        $page1->setTitle('...');
        $page1->setStatus(true);
        $page1->setMetaUrl($urlText);
        $page1->setMetaTitle('...');
        $page1->setCommentStatus(true);

        $this->dm->persist($page1);
        $this->dm->flush();
        $this->dm->clear();

        // Create Page 2
        $page2 = new Page();
        $page2->setLocale('en');
        $page2->setContent('...');
        $page2->setTitle('...');
        $page2->setStatus(true);
        $page2->setMetaUrl($urlText);
        $page2->setMetaTitle('...');
        $page2->setCommentStatus(true);

        $this->dm->persist($page2);
        $this->dm->flush();

        // Delete Page 1
        $page1 = $this->dm
            ->getRepository('Aisel\PageBundle\Document\Page')
            ->findOneBy(['metaUrl' => $urlText]);

        $this->dm->remove($page1);
        $this->dm->flush();
        $this->dm->clear();
    }

}
