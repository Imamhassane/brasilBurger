<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: ImageRepository::class)]

class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nom;


    #[ORM\OneToOne(mappedBy: 'image', targetEntity: Menu::class, cascade: ['persist', 'remove'])]
    private $menu;

    #[ORM\OneToOne(mappedBy: 'image', targetEntity: Complement::class, cascade: ['persist', 'remove'])]
    private $complement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getBurger(): ?Burger
    {
        return $this->burger;
    }

    public function setBurger(Burger $burger): self
    {
        $this->burger = $burger;

        return $this;
    }

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setMenu(Menu $menu): self
    {
        // set the owning side of the relation if necessary
        if ($menu->getImage() !== $this) {
            $menu->setImage($this);
        }

        $this->menu = $menu;

        return $this;
    }

    public function getComplement(): ?Complement
    {
        return $this->complement;
    }

    public function setComplement(?Complement $complement): self
    {
        // unset the owning side of the relation if necessary
        if ($complement === null && $this->complement !== null) {
            $this->complement->setImage(null);
        }

        // set the owning side of the relation if necessary
        if ($complement !== null && $complement->getImage() !== $this) {
            $complement->setImage($this);
        }

        $this->complement = $complement;

        return $this;
    }
}
