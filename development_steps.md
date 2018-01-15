# Development steps

[Table of contents](https://github.com/fedy95/Catalog-AuthorsBooks/blob/master/README.md)

## Versions of program:
- [final version](https://github.com/fedy95/Catalog-AuthorsBooks).

### History steps by step
**1) Install [XAMPP](https://www.apachefriends.org/xampp-files/7.2.0/xampp-win32-7.2.0-0-VC15-installer.exe);**

**2) Changing environment variables (on Windows 10 Pro):**
- Control Panel -> System -> Additional System Parameters -> additionally (environment variables) ->
variable Path (edit) -> (edit-add) C:\xampp\php

**3) Two installation paths:**
- used for first version:
```shell
php -r "file_put_contents('symfony', file_get_contents('https://symfony.com/installer'));"
php symfony new Catalog-AuthorsBooks 3.4
```

- [used for final version](https://github.com/fedy95/Catalog-AuthorsBooks):
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

**6) Fix /composer.json (fix windows error):**
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

**7) Write info about database (if it's need) *, create database (if it's need)*:**
```yml
#app/config/parameters.yml

database_name: catalog_authorsbooks
```

**8) Generate entity *Author*:**
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

**9) Generate entity *Book*:**
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
**10) Update entity *Author*:**
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

**11) Update entity *Book*:**
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
 * @ORM\Entity(repositoryClass="fedy95\CatalogBundle\Repository\BookRepository")
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
     * @param AuthorRepository $author
     * @return Book
     */
    public function addAuthor(AuthorRepository $author)
    {
        $author->addBook($this);
        $this->authors[] = $author;
        return $this;
    }

    /**
     * Remove author
     * @param AuthorRepository $author
     */
    public function removeAuthor(AuthorRepository $author)
    {
        $this->authors->removeElement($author);
    }
```

**12) Create database schema (if it's need):**
```shell
php bin/console doctrine:schema:create
```

Result:

![database](https://github.com/fedy95/Catalog-AuthorsBooks/blob/master/_Diagrams/DataBase.jpg)

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

**15) Update Author-DefaultControllers and templates:**
```yml
#Resources/config/routing/author.yml
author_delete:
    path:     /{id}/delete
    defaults: { _controller: "fedy95CatalogBundle:Author:delete" }
    methods:  GET
```

```php
//Form/AuthorType.php
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('surname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Фамилия'
            ])
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Имя'
            ])
            ->add('patronymic', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:25px;',
                ],
                'label' => 'Отчество',
                'required' => false
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning',
                    'style' => 'margin-bottom:5px'],
                'label' => 'Внести изменения'
            ]);
    }
```
- move autogenerated author templalates from */app/Resources/views* to */src/fedy95/CatalogBundle/Resources/views*

- update base.html.twig
- update Default/index.html.twig
- update author/edit.html.twig
- update author/index.html.twig
- update author/new.html.twig
- update author/show.html.twig

- update DefaultController
- update AuthorController
```php
//example render view
@fedy95Catalog/author/index.html.twig
```

```php
//AuthorController
public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository('fedy95CatalogBundle:Author')->find($id);
        $em->remove($author);
        $em->flush();
        $this->addFlash(
            'notice',
            'Автор удален из каталога'
        );
        return $this->redirectToRoute('author_index');
    }
```

**16) Generate Book CRUD:**
```shell
php bin/console generate:doctrine:crud
The Entity shortcut name: fedy95CatalogBundle:Book
Do you want to generate the "write" actions [no]? yes
Configuration format (yml, xml, php, or annotation) [annotation]: yml
Routes prefix [/author]:
Do you confirm generation [yes]?
Updating the routing: Confirm automatic update of the Routing [yes]?
```

```yml
#src/fedy95/CatalogBundle/Resources/config/routing.yml
fedy95_catalog_book:
    resource: "@fedy95CatalogBundle/Resources/config/routing/book.yml"
    prefix:   /book
```

**17) Update BookController and templates:**
```yml
#Resources/config/routing/book.yml
book_delete:
    path:     /{id}/delete
    defaults: { _controller: "fedy95CatalogBundle:Book:delete" }
    methods:  GET
```

```yml
#app/config/config.yml:
parameters:
    locale: en
    books_directory: '%kernel.project_dir%/web/uploads/books'
    images_directory: '%kernel.project_dir%/web/uploads/images'
```

```php
use fedy95\CatalogBundle\Form\AuthorType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;',
                ],
                'label' => 'Название',
                'data_class' => null
            ])
            ->add('fileName', FileType::class, array('data_class' => null), [
                'attr' => [
                    'class' => 'file',
                    'style' => 'margin-bottom:15px;',
                    'data-allowed-file-extensions' => '["pdf"]'
                ],
                'label' => 'Произведение в формате PDF (не более 50 Мб)'
            ])
            ->add('pageNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;'
                ],
                'label' => 'Количество страниц',
                'required' => false
            ])
            ->add('yearPublication', IntegerType::class, [
                'attr' => [
                    'min' => '1400', 'max' => date('Y'),
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px'
                ],
                'label' => 'Год публикации',
                'required' => false
            ])
            ->add('ISBN', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'style' => 'margin-bottom:15px;'
                ],
                'label' => 'Международный номер книги',
                'required' => false
            ])
            ->add('imageName', FileType::class, array('data_class' => null), [
                'attr' => [
                    'class' => 'file',
                    'style' => 'margin-bottom:15px;',
                    'data-allowed-file-extensions' => '["jpg", "png"]'
                ],
                'label' => 'Изображение в формате JPG или PNG (не более 3 Мб)',
                'required' => false
            ])
            ->add('authors', EntityType::class, [
                'attr' => [
                    'multiple class' => 'form-control',
                    'style' => 'margin-bottom:25px'],
                'label' => 'Авторы произведения',
                'class' => Author::class,
                'expanded' => true,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')->orderBy('c.surname');
                }
            ])
            ->add('Save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-warning',
                    'style' => 'margin-bottom:5px'],
                'label' => 'Внести изменения'
            ]);
    }
```

```php
//AuthorController
public function newAction(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $book */
            $file = $book->getFileName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('books_directory'),
                $fileName
            );
            $book->setFileName($fileName);

            /** @var UploadedFile $image */
            if ($book->getImageName() !== null) {
                $file = $book->getImageName();
                $imageName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
                $book->setImageName($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirect($this->generateUrl('book_index'));
        }
        return $this->render('@fedy95Catalog/book/new.html.twig', array(
            'book' => $book,
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, Book $book)
    {
        $deleteForm = $this->createDeleteForm($book);
        $editForm = $this->createForm('fedy95\CatalogBundle\Form\BookType', $book);
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            /** @var UploadedFile $book */
            $file = $book->getFileName();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('books_directory'),
                $fileName
            );
            $book->setFileName($fileName);

            /** @var UploadedFile $image */
            if ($book->getImageName() !== null) {
                $file = $book->getImageName();
                $imageName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move(
                    $this->getParameter('images_directory'),
                    $imageName
                );
                $book->setImageName($imageName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('book_index', array('id' => $book->getId()));
        }
        return $this->render('@fedy95Catalog/book/edit.html.twig', array(
            'book' => $book,
            'edit_form' => $editForm->createView(),
        ));
    }
```

**17) frontend:**
```yml
# Twig Configuration
twig:
    debug: '%kernel.debug%'
    strict_variables: '%kernel.debug%'
    form_themes:
            - 'bootstrap_3_layout.html.twig'
```
