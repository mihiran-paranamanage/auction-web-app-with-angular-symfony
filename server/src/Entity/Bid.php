<?php

namespace App\Entity;

use App\Repository\BidRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BidRepository::class)
 */
class Bid
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Item::class, inversedBy="bids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $item;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default" = "0"})
     */
    private $bid;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $isAutoBid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

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

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getBid(): ?string
    {
        return $this->bid;
    }

    public function setBid(string $bid): self
    {
        $this->bid = $bid;

        return $this;
    }

    public function getIsAutoBid(): ?bool
    {
        return $this->isAutoBid;
    }

    public function setIsAutoBid(bool $isAutoBid): self
    {
        $this->isAutoBid = $isAutoBid;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
