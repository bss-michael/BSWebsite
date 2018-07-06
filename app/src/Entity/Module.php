<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Summary;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $Discussion;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Project", inversedBy="modules")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Project;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ModuleImages", mappedBy="Module")
     */
    private $moduleImages;

    public function __construct()
    {
        $this->moduleImages = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->Summary;
    }

    public function setSummary(?string $Summary): self
    {
        $this->Summary = $Summary;

        return $this;
    }

    public function getDiscussion(): ?string
    {
        return $this->Discussion;
    }

    public function setDiscussion(?string $Discussion): self
    {
        $this->Discussion = $Discussion;

        return $this;
    }

    public function getProject(): ?Project
    {
        return $this->Project;
    }

    public function setProject(?Project $Project): self
    {
        $this->Project = $Project;

        return $this;
    }

    /**
     * @return Collection|ModuleImages[]
     */
    public function getModuleImages(): Collection
    {
        return $this->moduleImages;
    }

    public function addModuleImage(ModuleImages $moduleImage): self
    {
        if (!$this->moduleImages->contains($moduleImage)) {
            $this->moduleImages[] = $moduleImage;
            $moduleImage->addModule($this);
        }

        return $this;
    }

    public function removeModuleImage(ModuleImages $moduleImage): self
    {
        if ($this->moduleImages->contains($moduleImage)) {
            $this->moduleImages->removeElement($moduleImage);
            $moduleImage->removeModule($this);
        }

        return $this;
    }
}
