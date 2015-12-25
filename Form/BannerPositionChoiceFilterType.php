<?php

namespace Wucdbm\Bundle\BannerBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\BannerBundle\Filter\BannerPositionChoiceFilter;
use Wucdbm\Bundle\WucdbmBundle\Form\Filter\BaseFilterType;

class BannerPositionChoiceFilterType extends BaseFilterType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('id', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\TextFilterType', [
                'placeholder' => 'ID'
            ])
            ->add('bannerStatus', 'Wucdbm\Bundle\WucdbmBundle\Form\Filter\ChoiceFilterType', [
                'choices'     => [
                    'With Banner'    => BannerPositionChoiceFilter::BANNER_STATUS_HAS_BANNER,
                    'Without Banner' => BannerPositionChoiceFilter::BANNER_STATUS_DOES_NOT_HAVE_BANNER
                ],
                'placeholder' => 'Status',
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => BannerPositionChoiceFilter::class
        ]);
    }

}
