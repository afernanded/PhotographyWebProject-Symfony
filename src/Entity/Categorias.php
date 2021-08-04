<?php

namespace App\Entity;

use App\Repository\CategoriasRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoriasRepository::class)
 */
class Categorias
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\Column(type="integer")
     */
    private $numImagenes;

    /**
     * @ORM\OneToMany(targetEntity=Imagenes::class, mappedBy="categoria")
     */
    private $categoria;

    public function __construct()
    {
        $this->categoria = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getNumImagenes(): ?int
    {
        return $this->numImagenes;
    }

    public function setNumImagenes(int $numImagenes): self
    {
        $this->numImagenes = $numImagenes;

        return $this;
    }

    /**
     * @return Collection|Imagenes[]
     */
    public function getCategoria(): Collection
    {
        return $this->categoria;
    }

    public function addCategorium(Imagenes $categorium): self
    {
        if (!$this->categoria->contains($categorium)) {
            $this->categoria[] = $categorium;
            $categorium->setCategoria($this);
        }

        return $this;
    }

    public function removeCategorium(Imagenes $categorium): self
    {
        if ($this->categoria->removeElement($categorium)) {
            // set the owning side to null (unless already changed)
            if ($categorium->getCategoria() === $this) {
                $categorium->setCategoria(null);
            }
        }

        return $this;
    }


    //Funcion toString usada para que nos saque las categorias en el formulario
    public function __toString()
    {
        return $this->getNombre();
    }
}
