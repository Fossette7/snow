<?php

namespace App\Entity;

use App\Repository\TrickRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TrickRepository::class)
 * @UniqueEntity(fields={"name"}, message="Cette figure existe déjà dans notre liste")
 */
class Trick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ajoutez un nom de figure.")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="trick",orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="tricks")
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="tricks")
     * @Assert\NotBlank(message = "Le contenu ne peut être vide.")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank(message = "Le contenu ne peut être vide.")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=Video::class, mappedBy="trick", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $video;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;


    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
        $this->image = new ArrayCollection();
        $this->video = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function setImage($image):self
    {
        $this->image = $image;

        return $this;
    }

    public function addImage(Image $oneImage): self
    {
        if (!$this->image->contains($oneImage)) {
            $this->image[] = $oneImage;
            $oneImage->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $oneImage): self
    {
        if ($this->image->contains($oneImage)) {
            $this->image->removeElement($oneImage);
            // set the owning side to null (unless already changed)
            if ($oneImage->getTrick() === $this) {
              $oneImage->setTrick(null);
            }
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }


    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }


    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }
    public function setComments($comments):self
    {
      $this->comments = $comments;

      return $this;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }


  /**
   * @return Collection|video[]
   */
  public function getVideo(): Collection
  {
    return $this->video;
  }

  public function setVideo($video):self
  {
    $this->video = $video;

    return $this;
  }

  public function addVideo(Video $oneVideo): self
  {
    if (!$this->video->contains($oneVideo)) {
      $this->video[] = $oneVideo;
      $oneVideo->setTrick($this);
    }

    return $this;
  }

  public function removeVideo(Video $oneVideo): self
  {
    if ($this->video->contains($oneVideo)) {
      $this->video->removeElement($oneVideo);
      // set the owning side to null (unless already changed)
      if ($oneVideo->getTrick() === $this) {
        $oneVideo->setTrick(null);
      }
    }

    return $this;
  }

  public function getSlug(): ?string
  {
      return $this->slug;
  }

  public function setSlug(string $slug): self
  {
      $this->slug = $slug;

      return $this;
  }

}
