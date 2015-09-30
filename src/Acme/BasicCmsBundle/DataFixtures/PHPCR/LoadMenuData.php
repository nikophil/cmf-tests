<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Acme\BasicCmsBundle\Document\Post;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;

class LoadMenuData implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 4;
    }


    public function load(ObjectManager $dm)
    {
        $menuParent = $dm->find(null, '/cms/menu');

        $menu = new Menu();
        $menu->setName('main-menu');
        $menu->setLabel('Main Menu');
        $menu->setParentDocument($menuParent);
        $dm->persist($menu);

        $homeMenuNode = new MenuNode();
        $homePage = $dm->find('Acme\BasicCmsBundle\Document\Page', '/cms/pages/main/Home');
        $homeMenuNode->setName($homePage->getSlug());
        $homeMenuNode->setLabel($homePage->getTitle());
        $homeMenuNode->setContent($homePage);
        $homeMenuNode->setParentDocument($menu);
        $dm->persist($homeMenuNode);

        $aboutMenuNode = new MenuNode();
        $aboutPage = $dm->find('Acme\BasicCmsBundle\Document\Page', '/cms/pages/main/About');
        $aboutMenuNode->setName($aboutPage->getSlug());
        $aboutMenuNode->setLabel($aboutPage->getTitle());
        $aboutMenuNode->setContent($aboutPage);
        $aboutMenuNode->setParentDocument($homeMenuNode);
        $dm->persist($aboutMenuNode);

        $postsMenuNode = new MenuNode();
        $postsMenuNode->setName('posts');
        $postsMenuNode->setLabel('Liste des posts');
        $postsMenuNode->setParentDocument($menu);
        $dm->persist($postsMenuNode);

        $posts = $dm->getRepository('AcmeBasicCmsBundle:Post')->findAll();

        /** @var Post $post */
        foreach ($posts as $post) {
            $postMenuNode = new MenuNode();
            $postMenuNode->setName($post->getSlug());
            $postMenuNode->setLabel($post->getTitle());
            $postMenuNode->setContent($post);
            $postMenuNode->setParentDocument($postsMenuNode);
            $dm->persist($postMenuNode);
        }

        $dm->flush();
    }
}