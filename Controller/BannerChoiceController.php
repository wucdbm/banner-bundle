<?php

namespace Wucdbm\Bundle\BannerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Wucdbm\Bundle\BannerBundle\Entity\BannerPosition;
use Wucdbm\Bundle\BannerBundle\Filter\BannerPositionChoiceFilter;
use Wucdbm\Bundle\BannerBundle\Form\BannerPositionChoiceFilterType;
use Wucdbm\Bundle\BannerBundle\Form\BannerPositionChooseType;
use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class BannerChoiceController extends BaseController {

    public function chooseAction(Request $request) {
        $filter = new BannerPositionChoiceFilter();
        $pagination = $filter->getPagination()->enable();
        $filter->loadFromRequest($request);
        $filterForm = $this->createForm(BannerPositionChoiceFilterType::class, $filter);
        $filter->load($request, $filterForm);
        $repo = $this->get('wucdbm_banner.repo.banner_positions');

        $positions = $repo->filterForChoose($filter);

        $forms = [];
        /** @var BannerPosition $position */
        foreach ($positions as $position) {
            $forms[$position->getId()] = $this->createForm(BannerPositionChooseType::class, $position, [
                'attr'   => [
                    'class' => 'position-form'
                ],
                'action' => $this->generateUrl('wucdbm_banner_choice_update_banner', [
                    'id' => $position->getId()
                ])
            ])->createView();
        }

        $data = [
            'filter'     => $filter,
            'pagination' => $pagination,
            'filterForm' => $filterForm->createView(),
            'forms'      => $forms
        ];

        return $this->render('@WucdbmBanner/BannerChoice/choose.html.twig', $data);
    }

    public function updatePositionBannerAction(BannerPosition $position, Request $request) {
        $form = $this->createForm(BannerPositionChooseType::class, $position);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager = $this->container->get('wucdbm_banner.manager.banners');
            $manager->savePosition($position);

            $banner = $position->getBanner();

            if ($banner) {
                $bannerAddon = sprintf('will display Banner <b>%s</b>', $banner->getName());
            } else {
                $bannerAddon = 'will <b>no longer</b> show a banner.';
            }

            $msg = sprintf('Banner Position <b>%s</b> %s', $position->getName(), $bannerAddon);

            return $this->json([
                'message' => [
                    'text' => $msg
                ]
            ]);
        }

        return $this->json([
            'message' => [
                'title' => 'Error',
                'text'  => 'There was an error trying to save the selected banner'
            ]
        ]);
    }

}
