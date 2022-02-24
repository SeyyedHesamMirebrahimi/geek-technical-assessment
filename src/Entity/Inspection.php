<?php

namespace App\Entity;

use App\Repository\InspectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InspectionRepository::class)
 */
class Inspection extends BaseEntity
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
    private $heading;

    /**
     * @ORM\OneToMany(targetEntity=SubStep::class, mappedBy="inspection")
     */
    private $Substeps;

    public function __construct()
    {
        parent::__construct();
        $this->Substeps = new ArrayCollection();
    }

    public function __toString():string
    {
        return $this->getHeading();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeading(): ?string
    {
        return $this->heading;
    }

    public function setHeading(string $heading): self
    {
        $this->heading = $heading;

        return $this;
    }

    /**
     * @return Collection<int, SubStep>
     */
    public function getSubsteps(): Collection
    {
        return $this->Substeps;
    }

    public function addSubstep(SubStep $substep): self
    {
        if (!$this->Substeps->contains($substep)) {
            $this->Substeps[] = $substep;
            $substep->setInspection($this);
        }

        return $this;
    }


    public function removeSubstep(SubStep $substep): self
    {
        if ($this->Substeps->removeElement($substep)) {
            // set the owning side to null (unless already changed)
            if ($substep->getInspection() === $this) {
                $substep->setInspection(null);
            }
        }

        return $this;
    }
}
