<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Entitled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isRightAnswer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     */
    private $Question;

    /**
     * @ORM\Column(type="integer")
     */
    private $AnsweredRight = 0;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getIsRightAnswer(): ?bool
    {
        return $this->isRightAnswer;
    }

    public function setIsRightAnswer(bool $isRightAnswer): self
    {
        $this->isRightAnswer = $isRightAnswer;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->Question;
    }

    public function setQuestion(?Question $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getAnsweredRight(): ?int
    {
        return $this->AnsweredRight;
    }

    public function setAnsweredRight(int $AnsweredRight): self
    {
        $this->AnsweredRight = $AnsweredRight;

        return $this;
    }
}
