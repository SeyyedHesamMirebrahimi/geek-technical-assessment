<?php

namespace App\Entity;

use App\Repository\SubStepRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubStepRepository::class)
 */
class SubStep extends BaseEntity
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $column3;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, mappedBy="substeps")
     */
    private $products;

    /**
     * @ORM\ManyToOne(targetEntity=Inspection::class, inversedBy="Substeps")
     * @ORM\JoinColumn(nullable=false)
     */
    private $inspection;

    public function __construct()
    {
        parent::__construct();
        $this->products = new ArrayCollection();
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

    public function getColumn3(): ?string
    {
        return $this->column3;
    }

    public function setColumn3(?string $column3): self
    {
        $this->column3 = $column3;

        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addSubstep($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            $product->removeSubstep($this);
        }

        return $this;
    }

    public function hasProduct(Product $product): bool
    {
      return   $this->products->contains($product);

    }

    public function getInspection(): ?Inspection
    {
        return $this->inspection;
    }

    public function setInspection(?Inspection $inspection): self
    {
        $this->inspection = $inspection;

        return $this;
    }
}
