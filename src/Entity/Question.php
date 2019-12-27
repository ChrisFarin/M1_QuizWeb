<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Quiz;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Entitled;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEntitled(): ?string
    {
        return $this->Entitled;
    }

    public function setEntitled(string $Entitled): self
    {
        $this->Entitled = $Entitled;

        return $this;
    }
}
