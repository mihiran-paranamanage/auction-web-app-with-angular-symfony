<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
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
    private $username;

    /**
     * @ORM\ManyToOne(targetEntity=UserRole::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userRole;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=AccessToken::class, mappedBy="user")
     */
    private $accessTokens;

    /**
     * @ORM\OneToMany(targetEntity=Bid::class, mappedBy="user")
     */
    private $bids;

    /**
     * @ORM\OneToMany(targetEntity=UserBidConfig::class, mappedBy="user")
     */
    private $userBidConfigs;

    public function __construct()
    {
        $this->accessTokens = new ArrayCollection();
        $this->bids = new ArrayCollection();
        $this->userBidConfigs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection|AccessToken[]
     */
    public function getAccessTokens(): Collection
    {
        return $this->accessTokens;
    }

    public function addAccessToken(AccessToken $accessToken): self
    {
        if (!$this->accessTokens->contains($accessToken)) {
            $this->accessTokens[] = $accessToken;
            $accessToken->setUser($this);
        }

        return $this;
    }

    public function removeAccessToken(AccessToken $accessToken): self
    {
        if ($this->accessTokens->removeElement($accessToken)) {
            // set the owning side to null (unless already changed)
            if ($accessToken->getUser() === $this) {
                $accessToken->setUser(null);
            }
        }

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
            $bid->setUser($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->removeElement($bid)) {
            // set the owning side to null (unless already changed)
            if ($bid->getUser() === $this) {
                $bid->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserBidConfig[]
     */
    public function getUserBidConfigs(): Collection
    {
        return $this->userBidConfigs;
    }

    public function addUserBidConfig(UserBidConfig $userBidConfig): self
    {
        if (!$this->userBidConfigs->contains($userBidConfig)) {
            $this->userBidConfigs[] = $userBidConfig;
            $userBidConfig->setUser($this);
        }

        return $this;
    }

    public function removeUserBidConfig(UserBidConfig $userBidConfig): self
    {
        if ($this->userBidConfigs->removeElement($userBidConfig)) {
            // set the owning side to null (unless already changed)
            if ($userBidConfig->getUser() === $this) {
                $userBidConfig->setUser(null);
            }
        }

        return $this;
    }
}
