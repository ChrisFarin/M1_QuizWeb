<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ScoreRepository")
 */
class Score
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="scores")
     */
    private $Player;

    /**
     * @ORM\Column(type="integer")
     */
    private $RightAnswer;

    /**
     * @ORM\Column(type="integer")
     */
    private $TotalAnswer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="scores")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Quiz;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?User
    {
        return $this->Player;
    }

    public function setPlayer(?User $Player): self
    {
        $this->Player = $Player;

        return $this;
    }

    public function getRightAnswer(): ?int
    {
        return $this->RightAnswer;
    }

    public function setRightAnswer(int $RightAnswer): self
    {
        $this->RightAnswer = $RightAnswer;

        return $this;
    }

    public function getTotalAnswer(): ?int
    {
        return $this->TotalAnswer;
    }

    public function setTotalAnswer(int $TotalAnswer): self
    {
        $this->TotalAnswer = $TotalAnswer;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->Quiz;
    }

    public function setQuiz(?Quiz $Quiz): self
    {
        $this->Quiz = $Quiz;

        return $this;
    }
}
