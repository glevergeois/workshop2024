<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Level = null;

    private $uuid; // UUID ou identifiant unique

    /**
     * @ORM\Column(type="string", length=255)
     */

    #[ORM\Column(length: 255)]
    private ?string $Specialization = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'classroom')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLevel(): ?string
    {
        return $this->Level;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setLevel(string $Level): static
    {
        $this->Level = $Level;

        return $this;
    }

    public function getSpecialization(): ?string
    {
        return $this->Specialization;
    }

    public function setSpecialization(string $Specialization): static
    {
        $this->Specialization = $Specialization;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setClassroom($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getClassroom() === $this) {
                $user->setClassroom(null);
            }
        }

        return $this;
    }
}
