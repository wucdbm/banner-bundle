<?php

namespace Wucdbm\Bundle\BannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Wucdbm\Bundle\BannerBundle\Repository\BannerRepository;

class BannerPositionChooseType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('banner', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'         => 'Wucdbm\Bundle\BannerBundle\Entity\Banner',
                'query_builder' => function (BannerRepository $repo) {
                    return $repo->getActiveBanners();
                },
                'choice_label'  => 'name',
                'placeholder'   => 'Choose Banner',
                'empty_data'    => null,
                'attr'          => [
                    'class' => 'select2'
                ],
                'label'         => 'Банер'
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class'      => 'Wucdbm\Bundle\BannerBundle\Entity\BannerPosition',
            'csrf_protection' => false
        ]);
    }
}
