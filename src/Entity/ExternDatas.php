<?php

namespace App\Entity;

use App\Repository\ExternDatasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExternDatasRepository::class)
 */
class ExternDatas
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $CA;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $advert;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_payed;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCA(): ?float
    {
        return $this->CA;
    }

    public function setCA(?float $CA): self
    {
        $this->CA = $CA;

        return $this;
    }

    public function getAdvert(): ?float
    {
        return $this->advert;
    }

    public function setAdvert(?float $advert): self
    {
        $this->advert = $advert;

        return $this;
    }

    public function getDatePayed(): ?\DateTimeInterface
    {
        return $this->date_payed;
    }

    public function setDatePayed(?\DateTimeInterface $date_payed): self
    {
        $this->date_payed = $date_payed;

        return $this;
    }
}
