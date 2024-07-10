<?php

namespace App\Entity;

use App\Repository\SaisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SaisonRepository::class)]
class Saison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Content $content_id = null;

    /**
     * @var Collection<int, Episode>
     */
    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'saison')]
    private Collection $episode_id;

    #[ORM\Column]
    private ?int $numero_saison = null;

    public function __construct()
    {
        $this->episode_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentId(): ?Content
    {
        return $this->content_id;
    }

    public function setContentId(?Content $content_id): static
    {
        $this->content_id = $content_id;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodeId(): Collection
    {
        return $this->episode_id;
    }

    public function addEpisodeId(Episode $episodeId): static
    {
        if (!$this->episode_id->contains($episodeId)) {
            $this->episode_id->add($episodeId);
            $episodeId->setSaison($this);
        }

        return $this;
    }

    public function removeEpisodeId(Episode $episodeId): static
    {
        if ($this->episode_id->removeElement($episodeId)) {
            // set the owning side to null (unless already changed)
            if ($episodeId->getSaison() === $this) {
                $episodeId->setSaison(null);
            }
        }

        return $this;
    }

    public function getNumeroSaison(): ?int
    {
        return $this->numero_saison;
    }

    public function setNumeroSaison(int $numero_saison): static
    {
        $this->numero_saison = $numero_saison;

        return $this;
    }
}
