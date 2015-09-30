<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;

class LoadBlockData implements FixtureInterface
{
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