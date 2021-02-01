<?php

namespace App\Entity\Traits;

trait IdEntity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected int $id;

    public function getId(): ?string
    {
        return $this->id;
    }
}
