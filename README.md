# category-bundle

#How to use this bundle.

##Step 1
Install this bundle by adding
```
"repositories": [
  {
      "type": "vcs",
      "url":  "git@github.com:nachosymfony/category-bundle.git"
  },
...
```
and require
```
"require": {
  "nacholibre/category-bundle": "dev-master",
...
```
in your composer.json file.

##Step 2
Add this bundle in your `AppKernel.php` file like so:
```
...
$bundles[] = new nacholibre\CategoryBundle\nacholibreCategoryBundle();
...
```

##Step 3
Create your category entity, here is example:
```
namespace AppBundle\Entity;

use nacholibre\CategoryBundle\Model\BaseCategory as BaseCategory;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="nacholibre\CategoryBundle\Repository\CategoryRepository")
 * @UniqueEntity(fields={"name"}, errorPath="name", message="category.name.duplicate")
 * @UniqueEntity(fields={"parent"}, errorPath="name", message="category.name.duplicate")
 * @ORM\Table(name="nacholibre_category_product",uniqueConstraints={
 *     @ORM\UniqueConstraint(name="name", columns={"slug", "parent_id"})},
 *     indexes={@ORM\Index(name="position", columns={"position"})})
 */
class Category extends BaseCategory {
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Product", mappedBy="categories")
     */
    protected $members;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="mainCategory")
     */
    protected $mainMembers;

    public function __construct() {
        parent::__construct();
    }
}
```
Replace `AppBundle\Entity\Product` with the entity you want to keep in your categories.

##Step 4
Configure the bundle in config.yml

```
nacholibre_category:
    types:
        product:
            max_levels: 3
            entity_class: AppBundle\Entity\Category
```
