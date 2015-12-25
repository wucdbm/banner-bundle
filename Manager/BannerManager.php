<?php

namespace Wucdbm\Bundle\BannerBundle\Manager;

use Wucdbm\Bundle\BannerBundle\Collection\BannerCollection;
use Wucdbm\Bundle\BannerBundle\Entity\Banner;
use Wucdbm\Bundle\BannerBundle\Entity\BannerPosition;
use Wucdbm\Bundle\WucdbmBundle\Cache\Exception\CacheMissException;
use Wucdbm\Bundle\WucdbmBundle\Manager\AbstractManager;

class BannerManager extends AbstractManager {

    public function saveBanner(Banner $banner) {
        $repo = $this->container->get('wucdbm_banner.repo.banners');
        $repo->save($banner);
        $this->uncachePositions();
    }

    public function savePosition(BannerPosition $position) {
        $repo = $this->container->get('wucdbm_banner.repo.banner_positions');
        $repo->save($position);
        $this->uncachePositions();
    }

    public function removeBanner(Banner $banner) {
        $repo = $this->container->get('wucdbm_banner.repo.banners');
        $repo->remove($banner);
        $this->uncachePositions();
    }

    public function removePosition(BannerPosition $position) {
        $repo = $this->container->get('wucdbm_banner.repo.banner_positions');
        $repo->remove($position);
        $this->uncachePositions();
    }

    /**
     * Returns all active banner positions
     *
     * @return BannerCollection
     */
    public function getBanners() {
        $key = $this->localCache->generateKey('banners');
        try {
            return $this->localCache->get($key);
        } catch (CacheMissException $ex) {
            $positions = $this->getPositions();
            $debug = false;
            $parameter = $this->container->getParameter('wucdbm_banner.show_positions_parameter');
            $requestStack = $this->container->get('request_stack');
            $request = $requestStack->getCurrentRequest();
            if ($request) {
                $debug = $request->query->get($parameter, false);
            }

            $collection = new BannerCollection($positions, $debug);
            $this->localCache->forever($key, $collection);

            return $collection;
        }
    }

    public function getPositions() {
        $key = $this->getPositionsKey();
        try {
            return $this->cache->get($key);
        } catch (CacheMissException $e) {
            $repo = $this->container->get('wucdbm_banner.repo.banner_positions');
            $positions = $repo->findAllActive();
            $this->cache->forever($key, $positions);

            return $positions;
        }
    }

    public function uncachePositions() {
        $key = $this->getPositionsKey();
        $this->cache->remove($key);
    }

    protected function getPositionsKey() {
        return $this->cache->generateKey('banners.positions');
    }

    /**
     * @param $name
     * @return BannerPosition|null
     */
    public function getPositionByName($name) {
        return $this->container->get('wucdbm_banner.repo.banner_positions')->findOneByName($name);
    }

    public function uncacheBannerPositions() {
        $key = $this->cache->generateKey('banners.positions');
        $this->cache->remove($key);
    }

}