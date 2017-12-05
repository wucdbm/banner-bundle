<?php

namespace Wucdbm\Bundle\BannerBundle\Manager;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Wucdbm\Bundle\BannerBundle\Collection\BannerCollection;
use Wucdbm\Bundle\BannerBundle\Entity\Banner;
use Wucdbm\Bundle\BannerBundle\Entity\BannerPosition;
use Wucdbm\Bundle\BannerBundle\Repository\BannerPositionRepository;
use Wucdbm\Bundle\BannerBundle\Repository\BannerRepository;

class BannerManager {

    /** @var BannerRepository */
    protected $bannerRepo;

    /** @var BannerPositionRepository */
    protected $bannerPositionRepo;

    /** @var CacheItemPoolInterface */
    protected $cache;

    /** @var ArrayAdapter */
    protected $localCache;

    public function __construct(BannerRepository $bannerRepo, BannerPositionRepository $bannerPositionRepo,
                                CacheItemPoolInterface $cache) {
        $this->bannerRepo = $bannerRepo;
        $this->bannerPositionRepo = $bannerPositionRepo;
        $this->cache = $cache;
        $this->localCache = new ArrayAdapter();
    }

    public function saveBanner(Banner $banner) {
        $this->bannerRepo->save($banner);
        $this->uncachePositions();
    }

    public function savePosition(BannerPosition $position) {
        $this->bannerPositionRepo->save($position);
        $this->uncachePositions();
    }

    public function removeBanner(Banner $banner) {
        $this->bannerRepo->remove($banner);
        $this->uncachePositions();
    }

    public function removePosition(BannerPosition $position) {
        $this->bannerPositionRepo->remove($position);
        $this->uncachePositions();
    }

    public function getBanners(): BannerCollection {
        $key = 'wucdbm_banner.banners';

        $item = $this->localCache->getItem($key);

        if ($item->isHit()) {
            /** @var BannerCollection $collection */
            $collection = $item->get();
            $collection->setDebug($this->isDebug());

            return $collection;
        }

        $positions = $this->getPositions();
        $debug = $this->isDebug();
        $collection = new BannerCollection($positions, $debug);
        $item->set($collection);
        $this->localCache->save($item);

        return $collection;
    }

    protected function isDebug(): bool {
        $debug = false;
        $parameter = $this->container->getParameter('wucdbm_banner.show_positions_parameter');
        $requestStack = $this->container->get('request_stack');
        $request = $requestStack->getCurrentRequest();

        if ($request && $request->query->get($parameter, false)) {
            $debug = true;
        }

        return $debug;
    }

    public function getPositions() {
        $key = $this->getPositionsKey();

        $item = $this->cache->getItem($key);

        if ($item->isHit()) {
            return $item->get();
        }

        $positions = $this->bannerPositionRepo->findAllActive();
        $item->set($positions);
        $this->cache->save($item);

        return $positions;
    }

    public function uncachePositions() {
        $key = $this->getPositionsKey();
        $this->cache->deleteItem($key);
    }

    protected function getPositionsKey(): string {
        return 'wucdbm_banner.banners.positions';
    }

    public function getPositionByName($name): ?BannerPosition {
        return $this->bannerPositionRepo->findOneByName($name);
    }

}