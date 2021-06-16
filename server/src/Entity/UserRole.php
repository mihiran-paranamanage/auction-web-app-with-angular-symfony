<?php

namespace App\Entity;

use App\Repository\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRoleRepository::class)
 */
class UserRole
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
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="userRole")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=UserRoleDataGroup::class, mappedBy="userRole")
     */
    private $userRoleDataGroups;

    public function __construct()
    {
        $this->users = new ArrayCollection();
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
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setUserRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getUserRole() === $this) {
                $user->setUserRole(null);
            }
        }

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
            $userRoleDataGroup->setUserRole($this);
        }

        return $this;
    }

    public function removeUserRoleDataGroup(UserRoleDataGroup $userRoleDataGroup): self
    {
        if ($this->userRoleDataGroups->removeElement($userRoleDataGroup)) {
            // set the owning side to null (unless already changed)
            if ($userRoleDataGroup->getUserRole() === $this) {
                $userRoleDataGroup->setUserRole(null);
            }
        }

        return $this;
    }
}
