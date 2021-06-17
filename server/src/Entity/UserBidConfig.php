<?php

namespace App\Entity;

use App\Repository\UserBidConfigRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBidConfigRepository::class)
 */
class UserBidConfig
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userBidConfigs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default" = "0"})
     */
    private $maxBidAmount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getMaxBidAmount(): ?string
    {
        return $this->maxBidAmount;
    }

    public function setMaxBidAmount(string $maxBidAmount): self
    {
        $this->maxBidAmount = $maxBidAmount;

        return $this;
    }
}