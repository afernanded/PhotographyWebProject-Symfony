<?php

namespace App\Entity;

use App\Repository\ImagenesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImagenesRepository::class)
 */
class Imagenes
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
     * @ORM\Column(type="string", length=255)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="integer")
     */
    private $numVisualizaciones;

    /**
     * @ORM\Column(type="integer")
     */
    private $numLikes;

    /**
     * @ORM\Column(type="integer")
     */
    private $numDownloads;

    /**
     * @ORM\ManyToOne(targetEntity=Categorias::class, inversedBy="categoria")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categoria;

    const RUTA_IMAGENES_GALLERY = 'uploads/images/';

    public function __construct($numDownloads = 0, $numLikes = 0, $numVisualizaciones = 0)
    {
        $this->numDownloads = $numDownloads;
        $this->numLikes = $numLikes;
        $this->numVisualizaciones = $numVisualizaciones;
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

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getNumVisualizaciones(): ?int
    {
        return $this->numVisualizaciones;
    }

    public function setNumVisualizaciones(int $numVisualizaciones): self
    {
        $this->numVisualizaciones = $numVisualizaciones;

        return $this;
    }

    public function getNumLikes(): ?int
    {
        return $this->numLikes;
    }

    public function setNumLikes(int $numLikes): self
    {
        $this->numLikes = $numLikes;

        return $this;
    }

    public function getNumDownloads(): ?int
    {
        return $this->numDownloads;
    }

    public function setNumDownloads(int $numDownloads): self
    {
        $this->numDownloads = $numDownloads;

        return $this;
    }

    public function getCategoria(): ?Categorias
    {
        return $this->categoria;
    }

    public function setCategoria(?Categorias $categoria): self
    {
        $this->categoria = $categoria;

        return $this;
    }

    public function getUrlGallery()
    {
        return self::RUTA_IMAGENES_GALLERY . $this->getNombre();
    }

}
