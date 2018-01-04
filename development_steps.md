# Development steps

[Table of contents](https://github.com/fedy95/Catalog-AuthorsBooks/blob/master/README.md)

## Versions of program:
- [version with maximum handmade](https://github.com/fedy95/Catalog-AuthorsBooks/tree/master/_handmade);
- [version with maximum autogeneration](https://github.com/fedy95/Catalog-AuthorsBooks).

### History steps by step
**1) Install [XAMPP](https://www.apachefriends.org/xampp-files/7.2.0/xampp-win32-7.2.0-0-VC15-installer.exe);**

**2) Changing environment variables:**
- Control Panel -> System -> Additional System Parameters -> additionally (environment variables) ->
variable Path (edit) -> (edit-add) C:\xampp\php

**3) Two installation paths:**
- [used for first version](https://github.com/fedy95/Catalog-AuthorsBooks/tree/master/_handmade):
```shell
php -r "file_put_contents('symfony', file_get_contents('https://symfony.com/installer'));"
php symfony new Catalog-AuthorsBooks 3.4
```

- [used for second version](https://github.com/fedy95/Catalog-AuthorsBooks):
```shell
php -r "file_put_contents('symfony', file_get_contents('https://symfony.com/installer'));"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '544e09ee996cdf60ece3804abc52599c22b1f40f4323403c44d44fdfdd586475ca9813a858088ffbc1f233e9b180f061') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
composer create-project symfony/framework-standard-edition Catalog-AuthorsBooks 3.4
```

**4) Change systems configurate files:**
- change C:\xampp\apache\conf\extra\httpd-vhosts.conf file:
```txt
NameVirtualHost *:80 //uncommented
<VirtualHost *:80> //add block
    DocumentRoot "C:/xampp/htdocs/Catalog-AuthorsBooks/web/app_dev.php"
    ServerName Catalog-AuthorsBooks
</VirtualHost>
```
- added strings to C:\Windows\System32\drivers\etc\hosts file:
```txt
127.0.0.1 localhost
127.0.0.1 Catalog-AuthorsBooks
```

**5) Creating new bundle:**
```shell
php bin/console generate:bundle
Are you planning on sharing this bundle across multiple applications? [no]:
Bundle name: fedy95\CatalogBundle
Target Directory [src/]:
Configuration format (annotation, yml, xml, php) [annotation]: yml
```

**6) Delete AppBundle:**
- remove folder AppBundle from src/
- remove string *"new AppBundle\AppBundle()"* in app/AppKernel.php
- copy to src/fedy95/CatalogBundle/Resources/views and remove folder default (or all files) from app/Resources/views
- remove strings with AppBundle from:
```yml
### app/config/routing.yml

app:
    resource: '@AppBundle/Controller/'
    type: annotation
```
```yml
### app/config/services.yml

# makes classes in src/AppBundle available to be used as services
  # this creates a service per class whose id is the fully-qualified class name
  AppBundle\:
      resource: '../../src/AppBundle/*'
      # you can exclude directories or files
      # but if a service is unused, it's removed anyway
      exclude: '../../src/AppBundle/{Entity,Repository,Tests}'

  # controllers are imported separately to make sure they're public
  # and have a tag that allows actions to type-hint services
  AppBundle\Controller\:
      resource: '../../src/AppBundle/Controller'
      public: true
      tags: ['controller.service_arguments']

  # add more services, or override services that need manual wiring
  # AppBundle\Service\ExampleService:
  #     arguments:
  #         $someArgument: 'some_value'
```
- remove folder AppBundle from tests/

**7) Fix /composer.json (fix windows error):**
- Change:
```json
"psr-4": {
            "AppBundle\\": "src/AppBundle"
        },
```
to
```json
"psr-4": {
            "": "src/"
        },
```
- cmd:
```shell
composer dump-autoload
```
**8) Write info about database (if it's need) *, create database (if it's need)*:**
```yml
#app/config/parameters.yml

database_name: catalog_authorsbooks
```
**9) Generate entity *Author*:**
```shell
php bin/console doctrine:generate:entity
The Entity shortcut name: fedy95CatalogBundle:Author
Configuration format (yml, xml, php, or annotation) [annotation]:
New field name (press <return> to stop adding fields): name

Field type [string]:
Field length [255]: 100
Is nullable [false]:
Unique [false]:

New field name (press <return> to stop adding fields): surname
Field type [string]:
Field length [255]: 100
Is nullable [false]:
Unique [false]:

New field name (press <return> to stop adding fields): patronymic
Field type [string]:
Field length [255]: 100
Is nullable [false]: true
Unique [false]:
```

**10) Generate entity *Book*:**
```shell
php bin/console doctrine:generate:entity
The Entity shortcut name: fedy95CatalogBundle:Book
Configuration format (yml, xml, php, or annotation) [annotation]:
New field name (press <return> to stop adding fields): title
Field type [string]:
Field length [255]: 100
Is nullable [false]:
Unique [false]:

New field name (press <return> to stop adding fields): fileName
Field type [string]:
Field length [255]: 100
Is nullable [false]:
Unique [false]:

New field name (press <return> to stop adding fields): ISBN
Field type [string]:
Field length [255]: 30
Is nullable [false]: true
Unique [false]:

New field name (press <return> to stop adding fields): pageNumber
Field type [string]: integer
Is nullable [false]: true
Unique [false]:

New field name (press <return> to stop adding fields): yearPublication
Field type [string]: integer
Is nullable [false]: true
Unique [false]:

New field name (press <return> to stop adding fields): imageName
Field type [string]:
Field length [255]: 100
Is nullable [false]: true
Unique [false]:
```
**11) Update entity *Author*:**
- Turn on unique variables:
```php
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Author
 * @ORM\Table(name="author")
 * @ORM\Entity(repositoryClass="fedy95\CatalogBundle\Repository\AuthorRepository")
 * @UniqueEntity(fields={"surname","name", "patronymic"}, message="Этот автор уже был добавлен")
 * @UniqueEntity(fields={"surname","name"}, message="Этот автор уже был добавлен")
 */
```
- Function get full author name:
```php
    //for BookType class
    public function __toString()
    {
        return $this->getSurname() . ' ' . $this->getName() . ' ' . $this->getPatronymic();
    }
```
- Add many-to-many relations:
```php
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
```
**12) Update entity *Book*:**
- Turn on unique variables:
```php
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Book
 *
 * @ORM\Table(name="book")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BookRepository")
 * @UniqueEntity(fields={"title","yearPublication"}, message="Эта книга уже добавлена в каталог")
 * @UniqueEntity(fields={"iSBN"}, message="Эта книга уже добавлена в каталог")
 */
```

- Files properties:
```php
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
    ...
    /**
     * @var string
     * @ORM\Column(name="imageName", type="string", length=100, nullable=true)
     * @Assert\Image(
     *     maxSize = "3M",
     *     mimeTypes={"image/jpeg", "image/png"}
     *     )
     */
    private $imageName;
```

- Add many-to-many relations:
```php
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
```

**12) Create database schema (if it's need):**
```shell
php bin/console doctrine:schema:create
```

**13) Insert example rows into tables (if it's need):**
```sql
INSERT INTO `Book` (`title`,`fileName`,`ISBN`,`pageNumber`,`yearPublication`,`imageName`) VALUES ('Компьютерные сети','Computer_Networks_2012','978-5-459-00342-0','960','2012','network.jpg');
INSERT INTO `Book` (`title`,`fileName`,`ISBN`,`pageNumber`,`yearPublication`,`imageName`) VALUES ('Современные операционные системы','Modern_OS_2015','978-5-496-01395-6','1120','2015','systems.jpg');

INSERT INTO `Author` (`name`,`surname`,`patronymic`) VALUES ('Эндрю','Таненбаум','Стюарт');
INSERT INTO `Author` (`name`,`surname`) VALUES ('Ганс','Бос');
INSERT INTO `Author` (`name`,`surname`) VALUES ('Дэвид','Уэзеролл');

INSERT INTO `Author_Book` (`book_id`,`author_id`) VALUES ('1','1');
INSERT INTO `Author_Book` (`book_id`,`author_id`) VALUES ('1','3');
INSERT INTO `Author_Book` (`book_id`,`author_id`) VALUES ('2','1');
INSERT INTO `Author_Book` (`book_id`,`author_id`) VALUES ('2','2');
```

**14) Generate Author CRUD:**
```shell
php bin/console generate:doctrine:crud
The Entity shortcut name: fedy95CatalogBundle:Author
Do you want to generate the "write" actions [no]? yes
Configuration format (yml, xml, php, or annotation) [annotation]: yml
Routes prefix [/author]:
Do you confirm generation [yes]?
Updating the routing: Confirm automatic update of the Routing [yes]?
```
