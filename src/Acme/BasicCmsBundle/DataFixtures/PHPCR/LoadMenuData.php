<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Acme\BasicCmsBundle\Document\Post;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\ODM\PHPCR\DocumentManager;
use PHPCR\Util\NodeHelper;
use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\SimpleBlock;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadMenuData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 4;
    }


    public function load(ObjectManager $manager)
    {
        $session = $manager->getPhpcrSession();

        $basepath = $this->container->getParameter('cmf_menu.persistence.phpcr.menu_basepath');
        $content_path = $this->container->getParameter('cmf_content.persistence.phpcr.content_basepath');

        NodeHelper::createPath($session, $basepath);
        $root = $manager->find(null, $basepath);

        $main = $this->createMenuNode($manager, $root, 'main', 'Home', $manager->find(null, "/cms/content/pages/home"));
        $this->createMenuNode($manager, $main, 'about', 'About', $manager->find(null, "/cms/content/pages/about"));

        $postsNode = $this->createMenuNode($manager, $main, 'posts', 'Liste des posts');
        $posts = $manager->getRepository('AcmeBasicCmsBundle:Post')->findAll();
        /** @var Post $post */
        foreach ($posts as $post) {
            $this->createMenuNode($manager, $postsNode, $post->getSlug(), $post->getTitle(), $post);
        }

//        $menu = new Menu();
//        $menu->setName('main-menu');
//        $menu->setLabel('Main Menu');
//        $menu->setParentDocument($menuParent);
//        $dm->persist($menu);
//
//        $homeMenuNode = new MenuNode();
//        $homePage = $dm->find('Acme\BasicCmsBundle\Document\Page', '/cms/content/pages/home');
//        $homeMenuNode->setName($homePage->getSlug());
//        $homeMenuNode->setLabel($homePage->getTitle());
//        $homeMenuNode->setContent($homePage);
//        $homeMenuNode->setParentDocument($menu);
//        $dm->persist($homeMenuNode);
//
//        $aboutMenuNode = new MenuNode();
//        $aboutPage = $dm->find('Acme\BasicCmsBundle\Document\Page', '/cms/content/pages/about');
//        $aboutMenuNode->setName($aboutPage->getSlug());
//        $aboutMenuNode->setLabel($aboutPage->getTitle());
//        $aboutMenuNode->setContent($aboutPage);
//        $aboutMenuNode->setParentDocument($homeMenuNode);
//        $dm->persist($aboutMenuNode);
//
//        $postsMenuNode = new MenuNode();
//        $postsMenuNode->setName('posts');
//        $postsMenuNode->setLabel('Liste des posts');
//        $postsMenuNode->setParentDocument($menu);
//        $dm->persist($postsMenuNode);
//
//        $posts = $dm->getRepository('AcmeBasicCmsBundle:Post')->findAll();
//
//        /** @var Post $post */
//        foreach ($posts as $post) {
//            $postMenuNode = new MenuNode();
//            $postMenuNode->setName($post->getSlug());
//            $postMenuNode->setLabel($post->getTitle());
//            $postMenuNode->setContent($post);
//            $postMenuNode->setParentDocument($postsMenuNode);
//            $dm->persist($postMenuNode);
//        }

        $manager->flush();
    }

    /**
     * @return MenuNode a Navigation instance with the specified information
     */
    protected function createMenuNode($manager, $parent, $name, $label, $content = null, $uri = null, $route = null)
    {
        if (!$parent instanceof MenuNode && !$parent instanceof Menu) {
            $menuNode = new Menu();
        } else {
            $menuNode = new MenuNode();
        }

        $menuNode->setParentDocument($parent);
        $menuNode->setName($name);

        $manager->persist($menuNode); // do persist before binding translation

        if (null !== $content) {
            $menuNode->setContent($content);
        } elseif (null !== $uri) {
            $menuNode->setUri($uri);
        } elseif (null !== $route) {
            $menuNode->setRoute($route);
        }

        if (is_array($label)) {
            foreach ($label as $locale => $l) {
                $menuNode->setLabel($l);
                $manager->bindTranslation($menuNode, $locale);
            }
        } else {
            $menuNode->setLabel($label);
        }

        return $menuNode;
    }
}