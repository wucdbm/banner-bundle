<?php

namespace Wucdbm\Bundle\BannerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\BannerBundle\Entity\BannerPosition;
use Wucdbm\Bundle\BannerBundle\Filter\BannerPositionFilter;
use Wucdbm\Bundle\BannerBundle\Form\BannerPositionFilterType;
use Wucdbm\Bundle\BannerBundle\Form\BannerPositionType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class BannerPositionController extends BaseController {

    public function listAction(Request $request) {
        $repo = $this->get('wucdbm_banner.repo.banner_positions');
        $filter = new BannerPositionFilter();
        $pagination = $filter->getPagination()->enable();
        $filterForm = $this->createForm(BannerPositionFilterType::class, $filter);
        $filter->load($request, $filterForm);
        $positions = $repo->filter($filter);
        $data = [
            'positions'  => $positions,
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView()
        ];

        return $this->render('@WucdbmBanner/BannerPosition/list.html.twig', $data);
    }

    public function refreshAction(BannerPosition $position) {
        $data = [
            'position' => $position
        ];

        return $this->render('@WucdbmBanner/BannerPosition/list_row.html.twig', $data);
    }

    public function activateAction(BannerPosition $position) {
        return $this->activity($position, true);
    }

    public function deactivateAction(BannerPosition $position) {
        return $this->activity($position, false);
    }

    protected function activity(BannerPosition $position, $boolean) {
        $position->setIsActive($boolean);

        $manager = $this->container->get('wucdbm_banner.manager.banners');
        $manager->savePosition($position);

        return $this->json([
            'success' => true,
            'refresh' => true
        ]);
    }

    public function editAction(BannerPosition $position, Request $request) {
        $form = $this->createForm(BannerPositionType::class, $position);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_banner.manager.banners');
            $manager->savePosition($position);
        }

        $data = [
            'position' => $position,
            'form'     => $form->createView()
        ];

        return $this->render('@WucdbmBanner/BannerPosition/edit.html.twig', $data);
    }

    public function createAction(Request $request) {
        $position = new BannerPosition();
        $name = $request->query->get('name');
        if ($name) {
            $position->setName($name);
        }
        $form = $this->createForm(BannerPositionType::class, $position);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_banner.manager.banners');
            $manager->savePosition($position);

            return $this->redirectToRoute('wucdbm_banner_position_edit', [
                'id' => $position->getId()
            ]);
        }

        $data = [
            'form' => $form->createView()
        ];

        return $this->render('@WucdbmBanner/BannerPosition/create.html.twig', $data);
    }

    public function deleteAction(BannerPosition $position, Request $request) {
        if (!$request->request->get('is_confirmed')) {
            return $this->json([
                'success' => false
            ]);
        }

        $manager = $this->container->get('wucdbm_banner.manager.banners');
        $manager->removePosition($position);

        return $this->json([
            'success' => true,
            'remove'  => true,
            'witter'  => [
                'text' => 'Position deleted'
            ]
        ]);
    }

}
