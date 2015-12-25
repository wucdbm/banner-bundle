<?php

namespace Wucdbm\Bundle\BannerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\BannerBundle\Entity\Banner;
use Wucdbm\Bundle\BannerBundle\Filter\BannerFilter;
use Wucdbm\Bundle\BannerBundle\Form\BannerFilterType;
use Wucdbm\Bundle\BannerBundle\Form\BannerType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class BannerController extends BaseController {

    public function listAction(Request $request) {
        $repo = $this->get('wucdbm_banner.repo.banners');
        $filter = new BannerFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(BannerFilterType::class, $filter);
        $filter->load($request, $filterForm);
        $banners = $repo->filter($filter);
        $data = [
            'banners'    => $banners,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmBanner/Banner/list.html.twig', $data);
    }

    public function refreshAction(Banner $banner) {
        $data = [
            'banner' => $banner
        ];

        return $this->render('@WucdbmBanner/Banner/list_row.html.twig', $data);
    }

    public function deleteAction(Banner $banner, Request $request) {
        if (!$request->request->get('is_confirmed')) {
            return $this->json([
                'success' => false
            ]);
        }

        $manager = $this->container->get('wucdbm_banner.manager.banners');
        $manager->removeBanner($banner);

        return $this->json([
            'success' => true,
            'remove'  => true,
            'witter'  => [
                'text' => 'Banner deleted'
            ]
        ]);
    }

    public function activateAction(Banner $banner) {
        return $this->activity($banner, true);
    }

    public function deactivateAction(Banner $banner) {
        return $this->activity($banner, false);
    }

    protected function activity(Banner $banner, $boolean) {
        $banner->setIsActive($boolean);
        $manager = $this->container->get('wucdbm_banner.manager.banners');
        $manager->saveBanner($banner);

        return $this->json([
            'success' => true,
            'refresh' => true
        ]);
    }

    public function previewAction(Banner $banner, Request $request) {
        $data = [
            'banner' => $banner
        ];

        if ($request->isXmlHttpRequest()) {
            return $this->mfp($this->renderView('@WucdbmBanner/Banner/preview/preview_mfp.html.twig', $data));
        }

        return $this->render('@WucdbmBanner/Banner/preview/preview.html.twig', $data);
    }

    public function editAction(Banner $banner, Request $request) {
        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_banner.manager.banners');
            $manager->saveBanner($banner);
        }

        $data = [
            'banner' => $banner,
            'form'   => $form->createView()
        ];

        return $this->render('@WucdbmBanner/Banner/edit.html.twig', $data);
    }

    public function createAction(Request $request) {
        $banner = new Banner();
        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($banner->getContent() === null) {
                $banner->setContent('');
            }

            $manager = $this->container->get('wucdbm_banner.manager.banners');
            $manager->saveBanner($banner);

            return $this->redirectToRoute('wucdbm_banner_banner_edit', [
                'id' => $banner->getId()
            ]);
        }

        $data = [
            'form' => $form->createView()
        ];

        return $this->render('@WucdbmBanner/Banner/create.html.twig', $data);
    }

}
