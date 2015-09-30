<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Acme\BasicCmsBundle\Document\Post;

class LoadPostData implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 2;
    }

    public function load(ObjectManager $dm)
    {
//        if (!$dm instanceof DocumentManager) {
//            $class = get_class($dm);
//            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
//        }
//
//        $parent = $dm->find(null, '/cms/content/posts');
//
//        foreach (array('First', 'Second', 'Third', 'Fourth') as $title) {
//            $post = new Post();
//            $post->setSlug(sprintf('%s-post', strtolower($title)));
//            $post->setTitle(sprintf('My %s Post', $title));
//            $post->setParentDocument($parent);
//            if ($title != 'Fourth') {
//                $post->setPublishable(true);
//            } else {
//                $post->setPublishable(false);
//            }
//            $post->setContent(<<<HERE
//This is the content of my post.
//HERE
//            );
//
//            $dm->persist($post);
//        }
//
//        $dm->flush();
    }
}