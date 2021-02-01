<?php

namespace BehatTest\Traits;

use Doctrine\ORM\EntityManagerInterface;

trait DoctrineTrait
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @required
     * @param EntityManagerInterface $em
     */
    public function setEm(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @return EntityManagerInterface
     */
    public function getEm()
    {
        return $this->em;
    }

}
