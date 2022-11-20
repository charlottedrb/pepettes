<?php

namespace App\Entity;

use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $price_range = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $security_level = null;

    #[Assert\NotBlank]
    #[ORM\Column]
    private ?int $charo_rate = null;

    #[ORM\Column]
    private ?bool $has_cocktails = null;

    #[ORM\Column]
    private ?bool $has_beers = null;

    #[ORM\Column]
    private ?bool $has_wines = null;

    #[ORM\Column]
    private ?bool $has_softs = null;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'places')]
    private Collection $tags;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    private ?City $city = null;

    #[ORM\Column(length: 255)]
    private ?string $imageFilename = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $tips = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $recommandations = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'place', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPriceRange(): ?int
    {
        return $this->price_range;
    }

    public function setPriceRange(int $price_range): self
    {
        $this->price_range = $price_range;

        return $this;
    }

    public function getSecurityLevel(): ?int
    {
        return $this->security_level;
    }

    public function setSecurityLevel(int $security_level): self
    {
        $this->security_level = $security_level;

        return $this;
    }

    public function getCharoRate(): ?int
    {
        return $this->charo_rate;
    }

    public function setCharoRate(?int $charo_rate): self
    {
        $this->charo_rate = $charo_rate;

        return $this;
    }

    public function isHasCocktails(): ?bool
    {
        return $this->has_cocktails;
    }

    public function setHasCocktails(bool $has_cocktails): self
    {
        $this->has_cocktails = $has_cocktails;

        return $this;
    }

    public function isHasBeers(): ?bool
    {
        return $this->has_beers;
    }

    public function setHasBeers(bool $has_beers): self
    {
        $this->has_beers = $has_beers;

        return $this;
    }

    public function isHasWines(): ?bool
    {
        return $this->has_wines;
    }

    public function setHasWines(bool $has_wines): self
    {
        $this->has_wines = $has_wines;

        return $this;
    }

    public function isHasSofts(): ?bool
    {
        return $this->has_softs;
    }

    public function setHasSofts(bool $has_softs): self
    {
        $this->has_softs = $has_softs;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename($imageFilename): self
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getTips(): ?string
    {
        return $this->tips;
    }

    public function setTips(?string $tips): self
    {
        $this->tips = $tips;

        return $this;
    }

    public function getRecommandations(): ?string
    {
        return $this->recommandations;
    }

    public function setRecommandations(?string $recommandations): self
    {
        $this->recommandations = $recommandations;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Review $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews->add($review);
            $review->setPlace($this);
        }

        return $this;
    }

    public function removeReview(Review $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getPlace() === $this) {
                $review->setPlace(null);
            }
        }

        return $this;
    }
}
