<?php
namespace Acme\BasicCmsBundle\DataFixtures\PHPCR;

use Symfony\Cmf\Bundle\SimpleCmsBundle\Doctrine\Phpcr\Page;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ODM\PHPCR\DocumentManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadPageData extends ContainerAware implements FixtureInterface, OrderedFixtureInterface
{
    public function getOrder()
    {
        return 1;
    }

    public function load(ObjectManager $manager)
    {
        if (!$manager instanceof DocumentManager) {
            $class = get_class($manager);
            throw new \RuntimeException("Fixture requires a PHPCR ODM DocumentManager instance, instance of '$class' given.");
        }

        $basepath = $this->container->getParameter('cmf_simple_cms.persistence.phpcr.basepath');
        $root = $manager->find(null, $basepath);
        $root->setTitle('simple cms root (hidden by the home route in the sandbox)');

        $this->createPage($manager, $root, 'about', 'About us', 'Some information about us', 'The about us page with some content');
        $this->createPage($manager, $root, 'contact', 'Contact', 'A contact page', 'Please send an email to cmf-devs@groups.google.com');

        $manager->flush();
    }

    /**
     * @return Page instance with the specified information
     */
    protected function createPage(DocumentManager $manager, $parent, $name, $label, $title, $body)
    {
        $page = new Page();
        $page->setPosition($parent, $name);
        $page->setLabel($label);
        $page->setTitle($title);
        $page->setBody($body);

        $manager->persist($page);

        return $page;
    }
}