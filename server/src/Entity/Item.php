<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ItemRepository::class)
 */
class Item
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
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default" = "0"})
     */
    private $price;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, options={"default" = "0"})
     */
    private $bid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $closeDateTime;

    /**
     * @ORM\OneToMany(targetEntity=Bid::class, mappedBy="item")
     */
    private $bids;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $isClosed;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="items")
     */
    private $awardedUser;

    /**
     * @ORM\Column(type="boolean", options={"default" = "0"})
     */
    private $isAwardNotified;

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->bids = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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

    public function getCloseDateTime(): ?\DateTimeInterface
    {
        return $this->closeDateTime;
    }

    public function setCloseDateTime(\DateTimeInterface $closeDateTime): self
    {
        $this->closeDateTime = $closeDateTime;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setItem($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getItem() === $this) {
                $bid->setItem(null);
            }
        }

        return $this;
    }

    public function getIsClosed(): ?bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): self
    {
        $this->isClosed = $isClosed;

        return $this;
    }

    public function getAwardedUser(): ?User
    {
        return $this->awardedUser;
    }

    public function setAwardedUser(?User $awardedUser): self
    {
        $this->awardedUser = $awardedUser;

        return $this;
    }

    public function getIsAwardNotified(): ?bool
    {
        return $this->isAwardNotified;
    }

    public function setIsAwardNotified(bool $isAwardNotified): self
    {
        $this->isAwardNotified = $isAwardNotified;

        return $this;
    }
}
