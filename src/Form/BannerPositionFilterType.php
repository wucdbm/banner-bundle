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
use Wucdbm\Bundle\BannerBundle\Filter\BannerPositionFilter;
use Wucdbm\Bundle\QuickUIBundle\Form\Filter\BaseFilterType;

class BannerPositionFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('isActive', 'Wucdbm\Bundle\QuickUIBundle\Form\Filter\ChoiceFilterType', [
                'placeholder' => 'Status',
                'choices' => [
                    'Active' => BannerPositionFilter::IS_ACTIVE_TRUE,
                    'Inactive' => BannerPositionFilter::IS_ACTIVE_FALSE,
                ],
            ])
            ->add('name', 'Wucdbm\Bundle\QuickUIBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'Name',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\BannerBundle\Filter\BannerPositionFilter',
        ]);
    }
}
