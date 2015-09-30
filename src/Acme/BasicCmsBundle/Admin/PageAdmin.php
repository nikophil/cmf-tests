<?php
namespace Acme\BasicCmsBundle\Admin;

use Doctrine\ODM\PHPCR\DocumentManager;
use Sonata\DoctrinePHPCRAdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Knp\Menu\ItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use \Cocur\Slugify\Slugify;
use Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\MenuNode;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PageAdmin extends Admin implements ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title', 'text')
        ;
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('form.group_general')
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->end();
    }

    /**
     * @param \Acme\BasicCmsBundle\Document\Page $document
     * @return void
     */
    public function prePersist($document)
    {
        $slugify = new Slugify();
        $document->setSlug($slugify->slugify($document->getTitle()));

        $parent = $this->getModelManager()->find(null, '/cms/pages');
        $document->setParentDocument($parent);
    }

    /**
     * @param \Acme\BasicCmsBundle\Document\Page $document
     * @return void
     */
    public function postUpdate($document)
    {
        $nodeMenu = $this->getModelManager()->find(null, $document->getId());

        if (null === $nodeMenu) {
            $menu = $this->getModelManager()->find('Symfony\Cmf\Bundle\MenuBundle\Doctrine\Phpcr\Menu', '/cms/menu/main-menu');

            $nodeMenu = new MenuNode();
            $nodeMenu->setName($document->getSlug());
            $nodeMenu->setLabel($document->getTitle());
            $nodeMenu->setContent($document);
            $nodeMenu->setParentDocument($menu);

            /** @var DocumentManager $documentManager */
            $documentManager = $this->container->get('doctrine_phpcr')->getManager();
            $documentManager->persist($nodeMenu);
            $documentManager->flush();
        }
    }

    protected function configureSideMenu(ItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if ('edit' !== $action) {
            return;
        }

        $page = $this->getSubject();

        $menu->addChild('make-homepage', array(
            'label' => 'Make Homepage',
            'attributes' => array('class' => 'btn'),
            'route' => 'make_homepage',
            'routeParameters' => array(
                'id' => $page->getId(),
            ),
        ));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title', 'doctrine_phpcr_string');
    }

    public function getExportFormats()
    {
        return array();
    }
}