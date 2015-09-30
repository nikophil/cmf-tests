<?php

namespace Acme\BasicCmsBundle\Initializer;

use Doctrine\Bundle\PHPCRBundle\Initializer\InitializerInterface;
use PHPCR\Util\NodeHelper;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Acme\BasicCmsBundle\Document\Site;
use PHPCR\SessionInterface;

class SiteInitializer implements InitializerInterface
{
    private $basePath;

    public function __construct($basePath = '/cms')
    {
        $this->basePath = $basePath;
    }

    public function init(ManagerRegistry $registry)
    {
        $dm = $registry->getManager();
        if ($dm->find(null, $this->basePath)) {
            return;
        }

        $site = new Site();
        $site->setId($this->basePath);
        $dm->persist($site);
        $dm->flush();

        /** @var SessionInterface $session */
        $session = $registry->getConnection();

        // create the 'cms', 'pages', and 'posts' nodes
        NodeHelper::createPath($session, $this->basePath . '/pages');
        NodeHelper::createPath($session, $this->basePath . '/posts');
        NodeHelper::createPath($session, $this->basePath . '/routes');
        NodeHelper::createPath($session, $this->basePath . '/blocks');

        $session->save();
    }

    public function getName()
    {
        return 'My site initializer';
    }
}