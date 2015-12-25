<?php

namespace Wucdbm\Bundle\BannerBundle\Repository;

use Wucdbm\Bundle\BannerBundle\Entity\Banner;
use Wucdbm\Bundle\BannerBundle\Filter\BannerFilter;
use Doctrine\ORM\NoResultException;
use Wucdbm\Bundle\WucdbmBundle\Repository\AbstractRepository;

class BannerRepository extends AbstractRepository {

    public function filter(BannerFilter $filter) {
        $builder = $this->createQueryBuilder('banners')
                        ->select('banners');

        if ($filter->getId()) {
            $builder->andWhere('banners.id = :id')
                ->setParameter('id', $filter->getId());
        }

        if ($filter->getName()) {
            $builder->andWhere('banners.name LIKE :name');
            $builder->setParameter('name', '%' . $filter->getName() . '%');
        }

        if (null !== $filter->getIsActive()) {
            $builder->andWhere('banners.isActive = :isActive');
            $builder->setParameter('isActive', $filter->getIsActive());
        }

        return $this->returnFilteredEntities($builder, $filter, 'banners.id');
    }

    public function getActiveBanners() {
        $builder = $this->createQueryBuilder('b')
                        ->where('b.isActive = :active')
                        ->setParameter('active', 1);
        return $builder;
    }
    
    public function findOneById($id) {
        $q = $this->createQueryBuilder('banners')
                  ->select('banners')
                  ->where('banners.id = :id')
                  ->setParameter('id', $id)
                  ->getQuery();
        try {
            $banner = $q->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
        return $banner;
    }

    public function remove(Banner $banner) {
        $em = $this->getEntityManager();
        $em->remove($banner);
        $em->flush();
    }

    public function save(Banner $banner) {
        $em = $this->getEntityManager();
        $em->persist($banner);
        $em->flush();
    }

}
