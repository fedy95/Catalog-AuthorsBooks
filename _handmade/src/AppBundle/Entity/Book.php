<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 * @UniqueEntity(fields={"name","yearPublication"}, message="Эта книга уже добавлена в каталог")
 * @UniqueEntity(fields={"iSBN"}, message="Эта книга уже добавлена в каталог")
 */
class Book
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="fileName", type="string", length=255)
     * @Assert\NotBlank(message="Пожалуйста, загрузите произведение как PDF-файл")
     * @Assert\File(
     *     maxSize = "50M",
     *     mimeTypes={ "application/pdf" }
     *     )
     */
    private $fileName;

    /**
     * @var string
     * @ORM\Column(name="ISBN", type="string", length=20, nullable=true)
     */
    private $iSBN;

    /**
     * @var int
     * @ORM\Column(name="pageNumber", type="integer", nullable=true)
     */
    private $pageNumber;

    /**
     * @var int
     * @ORM\Column(name="yearPublication", type="integer", nullable=true)
     */
    private $yearPublication;

    /**
     * @var string
     * @ORM\Column(name="imageName", type="string", length=100, nullable=true)
     * @Assert\Image(
     *     maxSize = "3M",
     *     mimeTypes={"image/jpeg", "image/png"}
     *     )
     */
    private $imageName;

    /**
     * Get id
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Book
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fileName
     * @param string $fileName
     * @return Book
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set iSBN
     * @param string $iSBN
     * @return Book
     */
    public function setISBN($iSBN)
    {
        $this->iSBN = $iSBN;
        return $this;
    }

    /**
     * Get iSBN
     * @return string
     */
    public function getISBN()
    {
        return $this->iSBN;
    }

    /**
     * Set pageNumber
     * @param integer $pageNumber
     * @return Book
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;
        return $this;
    }

    /**
     * Get pageNumber
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set yearPublication
     * @param \int $yearPublication
     * @return Book
     */
    public function setYearPublication($yearPublication)
    {
        $this->yearPublication = $yearPublication;
        return $this;
    }

    /**
     * Get yearPublication
     * @return \int
     */
    public function getYearPublication()
    {
        return $this->yearPublication;
    }

    /**
     * Set imageName
     * @param string $imageName
     * @return Book
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    //ManyToMany entity
    /**
     * @ORM\ManyToMany(targetEntity="Author", inversedBy="books", cascade={"persist"})
     * @ORM\JoinTable(name="author_book",
     * joinColumns={@ORM\JoinColumn(name="book_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="author_id", referencedColumnName="id")}
     *     )
     */
    private $authors;

    /**
     * Doctrine\Common\Collections\Collection
     * @return mixed
     */
    public function getAuthors()
    {
        return $this->authors;
    }

    /**
     * @param mixed $authors
     */
    public function setAuthors($authors)
    {
        $this->authors = $authors;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->authors = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add author
     * @param \AppBundle\Entity\Author $author
     * @return Book
     */
    public function addAuthor(\AppBundle\Entity\Author $author)
    {
        $author->addBook($this);
        $this->authors[] = $author;
        return $this;
    }

    /**
     * Remove author
     * @param \AppBundle\Entity\Author $author
     */
    public function removeAuthor(\AppBundle\Entity\Author $author)
    {
        $this->authors->removeElement($author);
    }
}
