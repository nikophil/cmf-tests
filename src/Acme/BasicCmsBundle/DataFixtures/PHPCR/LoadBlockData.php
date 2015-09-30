<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;

class LoadBlockData implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 3;
    }

    public function load(ObjectManager $dm)
    {
        if (!$dm instanceof DocumentManager) {
            $class = get_class($dm);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $parentDocument = $dm->find(null, '/cms/blocks');

        $myBlock = new SimpleBlock();
        $myBlock->setParentDocument($parentDocument);
        $myBlock->setName('RssBlock');
        $myBlock->setTitle('My first block');
        $myBlock->setBody('Hello block world!');

        $dm->persist($myBlock);

        $dm->flush();
    }
}