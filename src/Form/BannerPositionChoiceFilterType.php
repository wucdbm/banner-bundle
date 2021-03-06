<?php

/*
 * This file is part of the BannerBundle package.
 *
 * (c) Martin Kirilov <wucdbm@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wucdbm\Bundle\BannerBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\BannerBundle\Filter\BannerPositionChoiceFilter;
use Wucdbm\Bundle\QuickUIBundle\Form\Filter\BaseFilterType;

class BannerPositionChoiceFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('id', 'Wucdbm\Bundle\QuickUIBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'ID',
            ])
            ->add('bannerStatus', 'Wucdbm\Bundle\QuickUIBundle\Form\Filter\ChoiceFilterType', [
                'choices' => [
                    'With Banner' => BannerPositionChoiceFilter::BANNER_STATUS_HAS_BANNER,
                    'Without Banner' => BannerPositionChoiceFilter::BANNER_STATUS_DOES_NOT_HAVE_BANNER,
                ],
                'placeholder' => 'Status',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => BannerPositionChoiceFilter::class,
        ]);
    }
}
