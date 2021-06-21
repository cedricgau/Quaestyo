<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlayerRepository;



/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 */
class Player
{
     /**
     * @ORM\Id
     * @ORM\Column(name="id_player", type="string", length=30)
     */
    private $id_player;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $pseudo;

    /**
     * @ORM\Column(type="date")
     */
    private $date_creation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $first_purchase;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $state;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency1;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency2;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency3;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency4;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency5;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $currency6;

    /**
     * @ORM\Column(type="string", length=13, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $last_seen;

    public function getIdPlayer(): ?string
    {
        return $this->id_player;
    }

    public function setIdPlayer(string $id_player): self
    {
        $this->id_player = $id_player;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->date_creation;
    }

    public function setDateCreation(\DateTime $date_creation): self
    {
        $this->date_creation = $date_creation;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getFirstPurchase(): ?\DateTime
    {
        return $this->first_purchase;
    }

    public function setFirstPurchase(?\DateTime $first_purchase): self
    {
        $this->first_purchase = $first_purchase;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getCurrency1(): ?int
    {
        return $this->currency1;
    }

    public function setCurrency1(?int $currency1): self
    {
        $this->currency1 = $currency1;

        return $this;
    }

    public function getCurrency2(): ?int
    {
        return $this->currency2;
    }

    public function setCurrency2(?int $currency2): self
    {
        $this->currency2 = $currency2;

        return $this;
    }

    public function getCurrency3(): ?int
    {
        return $this->currency3;
    }

    public function setCurrency3(?int $currency3): self
    {
        $this->currency3 = $currency3;

        return $this;
    }

    public function getCurrency4(): ?int
    {
        return $this->currency4;
    }

    public function setCurrency4(?int $currency4): self
    {
        $this->currency4 = $currency4;

        return $this;
    }

    public function getCurrency5(): ?int
    {
        return $this->currency5;
    }

    public function setCurrency5(?int $currency5): self
    {
        $this->currency5 = $currency5;

        return $this;
    }

    public function getCurrency6(): ?int
    {
        return $this->currency6;
    }

    public function setCurrency6(?int $currency6): self
    {
        $this->currency6 = $currency6;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getLastSeen(): ?\DateTime
    {
        return $this->last_seen;
    }

    public function setLastSeen(?\DateTime $last_seen): self
    {
        $this->last_seen = $last_seen;

        return $this;
    }
}
