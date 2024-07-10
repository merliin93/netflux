<?php

namespace App\Entity;

use App\Repository\EpisodeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Content $content_id = null;

    #[ORM\Column]
    private ?int $numero_episode = null;

    #[ORM\Column(length: 255)]
    private ?string $titre_episode = null;

    #[ORM\Column]
    private ?int $duree = null;

    #[ORM\ManyToOne(inversedBy: 'episode_id')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Saison $saison = null;

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

    public function getNumeroEpisode(): ?int
    {
        return $this->numero_episode;
    }

    public function setNumeroEpisode(int $numero_episode): static
    {
        $this->numero_episode = $numero_episode;

        return $this;
    }

    public function getTitreEpisode(): ?string
    {
        return $this->titre_episode;
    }

    public function setTitreEpisode(string $titre_episode): static
    {
        $this->titre_episode = $titre_episode;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getSaison(): ?Saison
    {
        return $this->saison;
    }

    public function setSaison(?Saison $saison): static
    {
        $this->saison = $saison;

        return $this;
    }
}
