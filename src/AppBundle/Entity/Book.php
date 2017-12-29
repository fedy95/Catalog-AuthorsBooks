<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 */
class Book
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fileName", type="string", length=100)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="ISBN", type="string", length=20, nullable=true)
     */
    private $iSBN;

    /**
     * @var int
     *
     * @ORM\Column(name="pageNumber", type="integer", nullable=true)
     */
    private $pageNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="yearPublication", type="date", nullable=true)
     */
    private $yearPublication;

    /**
     * @var string
     *
     * @ORM\Column(name="imageName", type="string", length=100, nullable=true)
     */
    private $imageName;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Book
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return Book
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set iSBN
     *
     * @param string $iSBN
     *
     * @return Book
     */
    public function setISBN($iSBN)
    {
        $this->iSBN = $iSBN;

        return $this;
    }

    /**
     * Get iSBN
     *
     * @return string
     */
    public function getISBN()
    {
        return $this->iSBN;
    }

    /**
     * Set pageNumber
     *
     * @param integer $pageNumber
     *
     * @return Book
     */
    public function setPageNumber($pageNumber)
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * Get pageNumber
     *
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set yearPublication
     *
     * @param \DateTime $yearPublication
     *
     * @return Book
     */
    public function setYearPublication($yearPublication)
    {
        $this->yearPublication = $yearPublication;

        return $this;
    }

    /**
     * Get yearPublication
     *
     * @return \DateTime
     */
    public function getYearPublication()
    {
        return $this->yearPublication;
    }

    /**
     * Set imageName
     *
     * @param string $imageName
     *
     * @return Book
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * Get imageName
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    //ManyToMany
    /**
     * @ORM\ManyToMany(targetEntity="Author", mappedBy="books")
     */
    private $authors;

    /**
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
}

