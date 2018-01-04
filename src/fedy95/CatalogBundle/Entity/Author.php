<?php

namespace fedy95\CatalogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Author
 *
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="fedy95\CatalogBundle\Repository\AuthorRepository")
 * @UniqueEntity(fields={"surname","name", "patronymic"}, message="Этот автор уже был добавлен")
 * @UniqueEntity(fields={"surname","name"}, message="Этот автор уже был добавлен")
 */
class Author
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
     * @ORM\Column(name="surname", type="string", length=100)
     */
    private $surname;

    /**
     * @var string
     * @ORM\Column(name="patronymic", type="string", length=100, nullable=true)
     */
    private $patronymic;


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
     * @return Author
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
     * Set surname
     * @param string $surname
     * @return Author
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Get surname
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set patronymic
     * @param string $patronymic
     * @return Author
     */
    public function setPatronymic($patronymic)
    {
        $this->patronymic = $patronymic;
        return $this;
    }

    /**
     * Get patronymic
     * @return string
     */
    public function getPatronymic()
    {
        return $this->patronymic;
    }

    //for BookType class
    public function __toString()
    {
        return $this->getSurname() . ' ' . $this->getName() . ' ' . $this->getPatronymic();
    }

    //ManyToMany entity
    /**
     * @ORM\ManyToMany(targetEntity="Book", mappedBy="authors", cascade={"persist"})
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private $books;

    /**
     * Doctrine\Common\Collections\Collection
     * @return mixed
     */
    public function getBooks()
    {
        return $this->books;
    }

    /**
     * @param mixed $books
     */
    public function setBooks($books)
    {
        $this->books = $books;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->books = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add book
     * @param Book $book
     * @return Author
     */
    public function addBook(Book $book)
    {
        $this->books[] = $book;
        return $this;
    }

    /**
     * Remove book
     * @param Book $book
     */
    public function removeBook(Book $book)
    {
        $this->books->removeElement($book);
    }
}

