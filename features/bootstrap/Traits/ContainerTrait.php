<?php

namespace BehatTest\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;

trait ContainerTrait
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @required
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

}
