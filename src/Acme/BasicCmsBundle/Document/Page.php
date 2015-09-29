<?php

namespace Acme\BasicCmsBundle\Document;

use Symfony\Cmf\Component\Routing\RouteReferrersReadInterface;
use Knp\Menu\NodeInterface as KnpNodeInterface;
use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCR;

/**
 * @PHPCR\Document(referenceable=true)
 */
class Page implements RouteReferrersReadInterface, KnpNodeInterface
{
    use ContentTrait;

    /**
     * @PHPCR\Children()
     */
    protected $children;

    public function getName()
    {
        return $this->title;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getOptions()
    {
        return array(
            'label'              => $this->title,
            'content'            => $this,
            'attributes'         => array(),
            'childrenAttributes' => array(),
            'displayChildren'    => true,
            'linkAttributes'     => array(),
            'labelAttributes'    => array(),
        );
    }
}