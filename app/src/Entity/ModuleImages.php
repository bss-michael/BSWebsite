<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModuleImagesRepository")
 */
class ModuleImages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Module", inversedBy="moduleImages")
     */
    private $Module;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $Path;

    public function __construct()
    {
        $this->Module = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|Module[]
     */
    public function getModule(): Collection
    {
        return $this->Module;
    }

    public function addModule(Module $module): self
    {
        if (!$this->Module->contains($module)) {
            $this->Module[] = $module;
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->Module->contains($module)) {
            $this->Module->removeElement($module);
        }

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->Path;
    }

    public function setPath(string $Path): self
    {
        $this->Path = $Path;

        return $this;
    }
}
