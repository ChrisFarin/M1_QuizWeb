<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{

    const ONLYSCORE = "ONLYSCORE";
    const HIDEANSWER = "HIDEANSWER";
    const SHOWANSWER = "SHOWANSWER";

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="quizzes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsVisible;

    /**
     * 
     * https://gist.github.com/pylebecq/f844d1f6860241d8b025
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="Quiz", orphanRemoval=true, cascade={"remove"})
     */
    private $questions;


    /**
     * @ORM\Column(name="resultDisplay", type="string", columnDefinition="enum('ONLYSCORE', 'HIDEANSWER', 'SHOWANSWER')")
     */

    private $resultDisplay;

    /**
     * @ORM\Column(type="integer")
     */
    private $Amount = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Score", mappedBy="Quiz", orphanRemoval=true)
     */
    private $scores;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->scores = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->IsVisible;
    }

    public function setIsVisible(bool $IsVisible): self
    {
        $this->IsVisible = $IsVisible;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function setResultDisplay($resultDisplay) : self
    {
        if (!in_array($resultDisplay, array(self::ONLYSCORE, self::HIDEANSWER, self::SHOWANSWER))) {
            throw new \InvalidArgumentException("Invalid display result");
        }
        $this->resultDisplay = $resultDisplay;

        return $this;
    }

    public function getResultDisplay(): ?string {
        return $this -> resultDisplay;
    }

    public function getAmount(): ?int
    {
        return $this->Amount;
    }

    public function setAmount(int $Amount): self
    {
        $this->Amount = $Amount;

        return $this;
    }

    /**
     * @return Collection|Score[]
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): self
    {
        if (!$this->scores->contains($score)) {
            $this->scores[] = $score;
            $score->setQuiz($this);
        }

        return $this;
    }

    public function removeScore(Score $score): self
    {
        if ($this->scores->contains($score)) {
            $this->scores->removeElement($score);
            // set the owning side to null (unless already changed)
            if ($score->getQuiz() === $this) {
                $score->setQuiz(null);
            }
        }

        return $this;
    }
}
