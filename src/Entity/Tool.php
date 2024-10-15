<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ToolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ToolRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['tool']])]
class Tool
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    
    #[ORM\Column(length: 100)]
    #[Groups('tool')]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'tools')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('tool')]
    private ?Category $category = null;

    #[ORM\Column(length: 255)]
    private ?string $mainPic = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pic2 = null;

    #[ORM\Column(length: 255)]
    private ?string $pic3 = null;

    #[ORM\Column(length: 2000, nullable: true)]
    #[Groups('tool')]
    private ?string $description = null;

    /**
     * @var Collection<int, Borrow>
     */
    #[ORM\OneToMany(targetEntity: Borrow::class, mappedBy: 'toolBorrowed')]
    private Collection $borrows;

    #[ORM\ManyToOne(inversedBy: 'tools')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->borrows = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPic(): ?string
    {
        return $this->mainPic;
    }

    public function setMainPic(string $mainPic): static
    {
        $this->mainPic = $mainPic;

        return $this;
    }

    public function getPic2(): ?string
    {
        return $this->pic2;
    }

    public function setPic2(string $pic2): static
    {
        $this->pic2 = $pic2;

        return $this;
    }

    public function getPic3(): ?string
    {
        return $this->pic3;
    }

    public function setPic3(string $pic3): static
    {
        $this->pic3 = $pic3;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Borrow>
     */
    public function getBorrows(): Collection
    {
        return $this->borrows;
    }

    public function getPastBorrows(): Collection
    {
    $now = new \DateTime();

    $criteria = Criteria::create()
        ->where(Criteria::expr()->lt('endDate', $now));

    return $this->borrows->matching($criteria);
    }

    public function getActualBorrows(): Collection
    {
    $now = new \DateTime();

    $criteria = Criteria::create()
        ->where(Criteria::expr()->lte('startDate', $now))
        ->andWhere(Criteria::expr()->gte('endDate', $now));

    return $this->borrows->matching($criteria);
    }

    public function getFuturBorrows(): Collection
    {
    $now = new \DateTime();

    $criteria = Criteria::create()
        ->where(Criteria::expr()->gt('startDate', $now));

    return $this->borrows->matching($criteria);
    }


    public function addBorrow(Borrow $borrow): static
    {
        if (!$this->borrows->contains($borrow)) {
            $this->borrows->add($borrow);
            $borrow->setToolBorrowed($this);
        }

        return $this;
    }

    public function removeBorrow(Borrow $borrow): static
    {
        if ($this->borrows->removeElement($borrow)) {
            // set the owning side to null (unless already changed)
            if ($borrow->getToolBorrowed() === $this) {
                $borrow->setToolBorrowed(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
