<?php

namespace Wucdbm\Bundle\BannerBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Wucdbm\Bundle\BannerBundle\Collection\BannerCollection;
use Wucdbm\Bundle\BannerBundle\Manager\BannerManager;

class BannerExtension extends \Twig_Extension {

    /**
     * @var BannerManager
     */
    protected $manager;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var BannerCollection
     */
    protected $collection = null;

    public function __construct(BannerManager $manager, \Twig_Environment $twig, ContainerInterface $container) {
        $this->manager = $manager;
        $this->twig = $twig;
        $this->container = $container;
    }

    public function getFilters() {
        return [
            new \Twig_SimpleFilter('banner', [$this, 'banner'], [
                'is_safe' => [
                    'html'
                ]
            ]),
        ];
    }

    public function getFunctions() {
        return [
            new \Twig_SimpleFunction('banner', [$this, 'banner'], [
                'is_safe' => [
                    'html'
                ]
            ]),
            new \Twig_SimpleFunction('showBannerPositionsUrl', [$this, 'showBannerPositionsUrl']),
        ];
    }

    public function banner($name) {
        $collection = $this->getCollection();
        if ($collection->has($name)) {
            $position = $collection->get($name);
            if ($position->getIsActive()) {
                if ($position->getBanner()) {
                    if ($position->getBanner()->getIsActive()) {
                        $data = [
                            'position' => $collection->get($name),
                            'debug'    => $collection->isDebug()
                        ];

                        return $this->twig->render('@WucdbmBanner/Banner/render/banner.html.twig', $data);
                    }
                    $data = [
                        'position' => $collection->get($name)
                    ];

                    return $this->twig->render('@WucdbmBanner/Banner/render/warning_banner_inactive.html.twig', $data);
                }
                $data = [
                    'position' => $collection->get($name)
                ];

                return $this->twig->render('@WucdbmBanner/Banner/render/warning_no_banner.html.twig', $data);
            }
            $data = [
                'name' => $name
            ];

            return $this->twig->render('@WucdbmBanner/Banner/render/wraning_position_inactive.html.twig', $data);
        }
        $data = [
            'name' => $name
        ];

        return $this->twig->render('@WucdbmBanner/Banner/render/wraning_no_position.html.twig', $data);
    }

    public function showBannerPositionsUrl() {
        $stack = $this->container->get('request_stack');
        $request = $stack->getCurrentRequest();
        if ($request) {
            $route = $request->attributes->get('_route');
            $routeParams = $request->attributes->get('_route_params');
            $parameter = $this->container->getParameter('wucdbm_banner.show_positions_parameter');
            $routeParams[$parameter] = 1;
            $router = $this->container->get('router');

            return $router->generate($route, $routeParams, UrlGenerator::ABSOLUTE_URL);
        }

        return '';
    }

    /**
     * @return BannerCollection
     */
    protected function getCollection() {
        if (null === $this->collection) {
            $this->collection = $this->manager->getBanners();
        }

        return $this->collection;
    }

    public function getName() {
        return 'app_banner';
    }

    public function getAlias() {
        return 'app_banner';
    }
}