<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResponseRepository")
 */
class Response
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $Question;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Entitled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $Correct;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?int
    {
        return $this->Question;
    }

    public function setQuestion(int $Question): self
    {
        $this->Question = $Question;

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

    public function getCorrect(): ?bool
    {
        return $this->Correct;
    }

    public function setCorrect(bool $Correct): self
    {
        $this->Correct = $Correct;

        return $this;
    }
}
