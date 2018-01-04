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
