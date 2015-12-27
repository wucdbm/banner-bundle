<?php

namespace Wucdbm\Bundle\BannerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Wucdbm\Bundle\BannerBundle\Repository\BannerRepository")
 * @ORM\Table(name="_wucdbm__banners",
 *      options={"collate"="utf8_general_ci"},
 *      indexes={
 *          @ORM\Index(name="is_active", columns={"is_active"})
 *      }
 * )
 */
class Banner {

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @ORM\Column(name="content", type="text", nullable=false)
     */
    protected $content;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    protected $isActive = true;
    
    /**
     * @ORM\OneToMany(targetEntity="Wucdbm\Bundle\BannerBundle\Entity\BannerPosition", mappedBy="banner")
     */
    protected $positions;

    /**
     * Constructor
     */
    public function __construct() {
        $this->positions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Banner
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Banner
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Banner
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * Add positions
     *
     * @param \Wucdbm\Bundle\BannerBundle\Entity\BannerPosition $positions
     * @return Banner
     */
    public function addPosition(\Wucdbm\Bundle\BannerBundle\Entity\BannerPosition $positions) {
        $this->positions[] = $positions;

        return $this;
    }

    /**
     * Remove positions
     *
     * @param \Wucdbm\Bundle\BannerBundle\Entity\BannerPosition $positions
     */
    public function removePosition(\Wucdbm\Bundle\BannerBundle\Entity\BannerPosition $positions) {
        $this->positions->removeElement($positions);
    }

    /**
     * Get positions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPositions() {
        return $this->positions;
    }
}
