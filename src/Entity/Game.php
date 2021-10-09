<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
     /**
     * @ORM\Id
     * @ORM\Column(name="code_adv", type="string", length=30)
     */
    private $code_adv;

    /**
     * @ORM\Id
     * @ORM\Column(name="id_player", type="string", length=30)
     */
    private $id_player;

    /**
     * @ORM\Column(type="date")
     */
    private $date_played;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $state;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    public function getCodeAdv(): ?string
    {
        return $this->code_adv;
    }

    public function setCodeAdv(string $code_adv): self
    {
        $this->code_adv = $code_adv;

        return $this;
    }

    public function getIdPlayer(): ?string
    {
        return $this->id_player;
    }

    public function setIdPlayer(string $id_player): self
    {
        $this->id_player = $id_player;

        return $this;
    }

    public function getDatePlayed(): ?\DateTime
    {
        return $this->date_played;
    }

    public function setDatePlayed(\DateTime $date_played): self
    {
        $this->date_played = $date_played;

        return $this;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }
}
