<?php

namespace Wucdbm\Bundle\BannerBundle\Filter;

use Wucdbm\Bundle\BannerBundle\Entity\BannerPositionType;
use Wucdbm\Bundle\WucdbmBundle\Filter\AbstractFilter;

class BannerPositionChoiceFilter extends AbstractFilter {

    const BANNER_STATUS_HAS_BANNER = 1,
        BANNER_STATUS_DOES_NOT_HAVE_BANNER = 2;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $bannerStatus;

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getBannerStatus() {
        return $this->bannerStatus;
    }

    /**
     * @param int $bannerStatus
     */
    public function setBannerStatus($bannerStatus) {
        $this->bannerStatus = $bannerStatus;
    }
    
}