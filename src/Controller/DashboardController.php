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

class DashboardController extends Controller {

    public function dashboardAction() {
        return $this->render('@WucdbmBanner/Dashboard/dashboard.html.twig');
    }

}