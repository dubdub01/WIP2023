<?php

namespace App\Entity;

use App\Entity\Skills;
use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\WorkerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: WorkerRepository::class)]
#[ORM\HasLifecycleCallbacks]

class Worker
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Firsname = null;

    #[ORM\Column(length: 255)]
    private ?string $Lastname = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Age = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $Description = null;

    #[ORM\Column]
    private ?bool $Visibility = null;

    #[ORM\OneToOne(mappedBy: 'Worker', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $Slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\ManyToMany(targetEntity: Skills::class, inversedBy: 'workers')]
    private Collection $skills;

    public function __construct()
    {
        $this->skills = new ArrayCollection();
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function initializeSlug(): void
    {
        if(empty($this->Slug)){
            $slugify = new Slugify();
            $this->Slug = $slugify->slugify($this->Firsname.' '.$this->Lastname.' '.uniqid());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirsname(): ?string
    {
        return $this->Firsname;
    }

    public function setFirsname(string $Firsname): self
    {
        $this->Firsname = $Firsname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->Lastname;
    }

    public function setLastname(string $Lastname): self
    {
        $this->Lastname = $Lastname;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->Age;
    }

    public function setAge(\DateTimeInterface $Age): self
    {
        $this->Age = $Age;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->Visibility;
    }

    public function setVisibility(bool $Visibility): self
    {
        $this->Visibility = $Visibility;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        // unset the owning side of the relation if necessary
        if ($user === null && $this->user !== null) {
            $this->user->setWorker(null);
        }

        // set the owning side of the relation if necessary
        if ($user !== null && $user->getWorker() !== $this) {
            $user->setWorker($this);
        }

        $this->user = $user;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->Slug;
    }

    public function setSlug(string $Slug): self
    {
        $this->Slug = $Slug;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return Collection<int, Skills>
     */
    public function getSkills(): Collection
    {
        return $this->skills;
    }

    public function addSkill(Skills $skill): self
    {
        if (!$this->skills->contains($skill)) {
            $this->skills->add($skill);
        }

        return $this;
    }

    public function removeSkill(Skills $skill): self
    {
        $this->skills->removeElement($skill);

        return $this;
    }
}
