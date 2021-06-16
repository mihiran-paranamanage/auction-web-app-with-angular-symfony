<?php

namespace App\Entity;

use App\Repository\UserRoleDataGroupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRoleDataGroupRepository::class)
 */
class UserRoleDataGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=UserRole::class, inversedBy="userRoleDataGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRole;

    /**
     * @ORM\ManyToOne(targetEntity=DataGroup::class, inversedBy="userRoleDataGroups")
     * @ORM\JoinColumn(nullable=false)
     */
    private $dataGroup;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $canRead;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $canCreate;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $canUpdate;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $canDelete;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserRole(): ?UserRole
    {
        return $this->userRole;
    }

    public function setUserRole(?UserRole $userRole): self
    {
        $this->userRole = $userRole;

        return $this;
    }

    public function getDataGroup(): ?DataGroup
    {
        return $this->dataGroup;
    }

    public function setDataGroup(?DataGroup $dataGroup): self
    {
        $this->dataGroup = $dataGroup;

        return $this;
    }

    public function getCanRead(): ?bool
    {
        return $this->canRead;
    }

    public function setCanRead(bool $canRead): self
    {
        $this->canRead = $canRead;

        return $this;
    }

    public function getCanCreate(): ?bool
    {
        return $this->canCreate;
    }

    public function setCanCreate(bool $canCreate): self
    {
        $this->canCreate = $canCreate;

        return $this;
    }

    public function getCanUpdate(): ?bool
    {
        return $this->canUpdate;
    }

    public function setCanUpdate(bool $canUpdate): self
    {
        $this->canUpdate = $canUpdate;

        return $this;
    }

    public function getCanDelete(): ?bool
    {
        return $this->canDelete;
    }

    public function setCanDelete(bool $canDelete): self
    {
        $this->canDelete = $canDelete;

        return $this;
    }
}
