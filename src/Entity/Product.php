<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product extends BaseEntity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity=SubStep::class, inversedBy="products")
     */
    private $substeps;

    /**
     * @ORM\Column(type="string", length=255 , unique=true)
     */
    private $skuCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    public function __construct()
    {
        parent::__construct();
        $this->substeps = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, SubStep>
     */
    public function getSubsteps(): Collection
    {
        return $this->substeps;
    }

    public function addSubstep(SubStep $substep): self
    {
        if (!$this->substeps->contains($substep)) {
            $this->substeps[] = $substep;
        }

        return $this;
    }


    public function clearSubsteps(): Product
    {
        $this->substeps = new ArrayCollection();
        return $this;
    }

    public function removeSubstep(SubStep $substep): self
    {
        $this->substeps->removeElement($substep);

        return $this;
    }

    public function getSkuCode(): ?string
    {
        return $this->skuCode;
    }

    public function setSkuCode(string $skuCode): self
    {
        $this->skuCode = $skuCode;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
