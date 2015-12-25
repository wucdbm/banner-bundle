<?php

namespace Wucdbm\Bundle\BannerBundle\Filter;

use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class BannerFilter extends AbstractFilter {

    const IS_ACTIVE_TRUE = 1,
        IS_ACTIVE_FALSE = 0;

    protected $id;

    protected $name;

    protected $isActive;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getIsActive() {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive) {
        $this->isActive = $isActive;
    }

}