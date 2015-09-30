<?php

namespace Acme\BasicCmsBundle\Document;

use Symfony\Cmf\Bundle\CoreBundle\PublishWorkflow\PublishableInterface;
use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;
use Knp\Menu\NodeInterface;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Page implements RouteReferrersReadInterface, PublishableInterface
{
    use ContentTrait;
    use PublishableTrait;

    /**
     * @PHPCR\Children()
     */
    protected $children;

    public function __construct()
    {
        $this->__constructPublishableTrait();
    }
}