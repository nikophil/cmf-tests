<?php
/**
 * Created by PhpStorm.
 * User: nphilippe
 * Date: 30/09/15
 * Time: 13:30
 */

namespace Acme\BasicCmsBundle\Document;


trait PublishableTrait
{
    /**
     * @PHPCR\Boolean(nullable=true)
     */
    protected $publishable;

    public function __constructPublishableTrait()
    {
        $this->publishable = false;
    }

    /**
     * @param bool $publishable
     * @return $this
     */
    public function setPublishable($publishable)
    {
        $this->publishable = $publishable;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublishable()
    {
        return $this->publishable;
    }
}