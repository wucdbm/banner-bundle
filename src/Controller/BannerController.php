<?php

/*
 * This file is part of the BannerBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\BannerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\BannerBundle\Entity\Banner;
use Wucdbm\Bundle\BannerBundle\Filter\BannerFilter;
use Wucdbm\Bundle\BannerBundle\Form\BannerFilterType;
use Wucdbm\Bundle\BannerBundle\Form\BannerType;
use Wucdbm\Bundle\BannerBundle\Manager\BannerManager;
use Wucdbm\Bundle\BannerBundle\Repository\BannerRepository;

class BannerController extends Controller {

    /** @var BannerManager */
    protected $bannerManager;

    /** @var BannerRepository */
    protected $bannerRepo;

    public function __construct(BannerManager $bannerManager, BannerRepository $bannerRepo) {
        $this->bannerManager = $bannerManager;
        $this->bannerRepo = $bannerRepo;
    }

    public function listAction(Request $request) {
        $filter = new BannerFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(BannerFilterType::class, $filter);
        $filter->load($request, $filterForm);
        $banners = $this->bannerRepo->filter($filter);
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

        $this->bannerManager->removeBanner($banner);

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
        $this->bannerManager->saveBanner($banner);

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
            return $this->json([
                'mfp' => $this->renderView('@WucdbmBanner/Banner/preview/preview_mfp.html.twig', $data)
            ]);
        }

        return $this->render('@WucdbmBanner/Banner/preview/preview.html.twig', $data);
    }

    public function editAction(Banner $banner, Request $request) {
        $form = $this->createForm(BannerType::class, $banner);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->bannerManager->saveBanner($banner);
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

            $this->bannerManager->saveBanner($banner);

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
