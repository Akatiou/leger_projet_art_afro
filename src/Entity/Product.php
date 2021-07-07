<?php

namespace App\Entity;

use App\Entity\Category;
use App\Entity\PurchaseItem;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Asserts;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Asserts\NotBlank(message="Le nom du produit est obligatoire !")
     * @Asserts\Length(min=3, max=255, minMessage="Le nom du produit doit avoir au moins 3 caractères !")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Asserts\NotBlank(message="Le prix du produit est obligatoire !")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @Asserts\NotBlank(message="Le choix de la catégorie du produit est obligatoire !")
     */
    private $category;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mainPicture;

    /**
     * @ORM\Column(type="text")
     * @Asserts\NotBlank(message="La description du produit est obligatoire !")
     * @Asserts\Length(min=10, minMessage="La description du produit doit avoir au moins 10 caractères !")
     */
    private $shortDescription;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=PurchaseItem::class, mappedBy="product")
     */
    private $purchaseItems;

    public function __construct()
    {
        $this->purchaseItems = new ArrayCollection();
    }

    // public static function loadValidatorMetadata(ClassMetadata $metadata)
    // {
    //     $metadata->addPropertyConstraints('name', [
    //         new Asserts\NotBlank(['message' => 'Le nom du produit est obligatoire']),
    //         new Asserts\Length(['min' => 3, 'max' => 255, 'minMessage' => 'Le nom du produit doit contenir au moins 3 caratères'])
    //     ]);

    //     $metadata->addPropertyConstraint('price', new Asserts\NotBlank(['message' => 'Le prix du produit est obligatoire']));

    //     $metadata->addPropertyConstraint('category', new Asserts\NotBlank(['message' => 'Le choix de la catégorie du produit est obligatoire']));

    //     $metadata->addPropertyConstraints('shortDescription', [
    //         new Asserts\NotBlank(['message' => 'La description du produit est obligatoire']),
    //         new Asserts\Length(['min' => 10, 'minMessage' => 'La description du produit doit contenir au moins 10 caratères'])
    //     ]);
    // }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;

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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getMainPicture(): ?string
    {
        return $this->mainPicture;
    }

    public function setMainPicture(string $mainPicture): self
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

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
     * @ORM\PrePersist
     */
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return Collection|PurchaseItem[]
     */
    public function getPurchaseItems(): Collection
    {
        return $this->purchaseItems;
    }

    public function addPurchaseItem(PurchaseItem $purchaseItem): self
    {
        if (!$this->purchaseItems->contains($purchaseItem)) {
            $this->purchaseItems[] = $purchaseItem;
            $purchaseItem->setProduct($this);
        }

        return $this;
    }

    public function removePurchaseItem(PurchaseItem $purchaseItem): self
    {
        if ($this->purchaseItems->removeElement($purchaseItem)) {
            // set the owning side to null (unless already changed)
            if ($purchaseItem->getProduct() === $this) {
                $purchaseItem->setProduct(null);
            }
        }

        return $this;
    }
}
