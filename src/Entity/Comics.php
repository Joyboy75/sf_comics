<?php

namespace App\Entity;

use App\Repository\ComicsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ComicsRepository::class)
 */
class Comics
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=Licence::class, inversedBy="comics")
     */
    private $licence;

    /**
     * @ORM\ManyToOne(targetEntity=Editor::class, inversedBy="comics")
     */
    private $editor;

    /**
     * @ORM\ManyToOne(targetEntity=Writer::class, inversedBy="comics")
     */
    private $writer;

    /**
     * @ORM\ManyToOne(targetEntity=Designer::class, inversedBy="comics")
     */
    private $designer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getLicence(): ?Licence
    {
        return $this->licence;
    }

    public function setLicence(?Licence $licence): self
    {
        $this->licence = $licence;

        return $this;
    }

    public function getEditor(): ?Editor
    {
        return $this->editor;
    }

    public function setEditor(?Editor $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    public function getWriter(): ?Writer
    {
        return $this->writer;
    }

    public function setWriter(?Writer $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

    public function getDesigner(): ?Designer
    {
        return $this->designer;
    }

    public function setDesigner(?Designer $designer): self
    {
        $this->designer = $designer;

        return $this;
    }
}
