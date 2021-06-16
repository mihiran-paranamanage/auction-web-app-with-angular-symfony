<?php

namespace App\Entity;

use App\Repository\DataGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DataGroupRepository::class)
 */
class DataGroup
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
     * @ORM\OneToMany(targetEntity=UserRoleDataGroup::class, mappedBy="dataGroup")
     */
    private $userRoleDataGroups;

    public function __construct()
    {
        $this->userRoleDataGroups = new ArrayCollection();
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
     * @return Collection|UserRoleDataGroup[]
     */
    public function getUserRoleDataGroups(): Collection
    {
        return $this->userRoleDataGroups;
    }

    public function addUserRoleDataGroup(UserRoleDataGroup $userRoleDataGroup): self
    {
        if (!$this->userRoleDataGroups->contains($userRoleDataGroup)) {
            $this->userRoleDataGroups[] = $userRoleDataGroup;
            $userRoleDataGroup->setDataGroup($this);
        }

        return $this;
    }

    public function removeUserRoleDataGroup(UserRoleDataGroup $userRoleDataGroup): self
    {
        if ($this->userRoleDataGroups->removeElement($userRoleDataGroup)) {
            // set the owning side to null (unless already changed)
            if ($userRoleDataGroup->getDataGroup() === $this) {
                $userRoleDataGroup->setDataGroup(null);
            }
        }

        return $this;
    }
}
