<?php

namespace nacholibre\CategoryBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
class BaseCategory {
    /**
    * @ORM\Column(name="id", type="integer", nullable=false)
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="IDENTITY")
    */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="Category", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @Gedmo\SortablePosition
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $position;

    /**
     * @Gedmo\SortableGroup
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    ///**
    // * @ORM\ManyToMany(targetEntity="nacholibre\CategoryBundle\Model\CategoryMemberInterface", mappedBy="categories")
    // */
    //protected $members;

    ///**
    // * @ORM\OneToMany(targetEntity="nacholibre\CategoryBundle\Model\CategoryMemberInterface", mappedBy="mainCategory")
    // */
    //protected $mainMembers;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="FOS\UserBundle\Model\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $createdBy;

    /**
     * @Gedmo\Blameable(on="change", field={"name", "parent"})
     * @ORM\ManyToOne(targetEntity="FOS\UserBundle\Model\UserInterface")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    protected $updatedBy;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    protected $level;

    function __construct() {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
        $this->mainMembers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getID() {
        return $this->id;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }

    public function setParent($category) {
        $this->parent = $category;
    }

    public function getParent() {
        return $this->parent;
    }

    public function getChildren() {
        return $this->children;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function getUpdatedBy() {
        return $this->updatedBy;
    }

    public function getCreated() {
        return $this->created;
    }

    public function getUpdated() {
        return $this->created;
    }

    public function addMember($member)
    {
        $this->members[] = $member;

        return $this;
    }

    public function removeMember($member)
    {
        $this->members->removeElement($member);
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function getChildrenCategoryIDs() {
        $id = $this->getID();

        $ids = [];

        $ids[] = $id;

        foreach($this->getChildren() as $child) {
            $ids[] = $child->getID();

            foreach($child->getChildren() as $child2) {
                $ids[] = $child2->getID();

                foreach($child2->getChildren() as $child3) {
                    $ids[] = $child3->getID();
                }
            }
        }

        return $ids;
    }

    public function addMainMember($member)
    {
        $this->mainMembers[] = $member;

        return $this;
    }

    public function removeMainMember($member)
    {
        $this->mainMembers->removeElement($member);
    }

    public function getMainMembers()
    {
        return $this->mainMembers;
    }

    public function getLevel() {
        //$level = 1;

        //while($this->getParent()) {
        //    $level++;
        //}

        return $this->level;
    }

    public function setLevel($level) {
        $this->level = $level;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setPosition($position) {
        $this->position = $position;
    }
}
