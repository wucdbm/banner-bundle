<?php

namespace Wucdbm\Bundle\BannerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Wucdbm\Bundle\BannerBundle\Entity\BannerPosition;
use Wucdbm\Bundle\BannerBundle\Manager\BannerManager;

class BannerPositionType extends AbstractType {

    /**
     * @var BannerManager
     */
    protected $manager;

    /**
     * BannerPositionType constructor.
     * @param BannerManager $manager
     */
    public function __construct(BannerManager $manager) {
        $this->manager = $manager;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $callback = function ($object, ExecutionContextInterface $context) use ($builder) {
            $position = $this->manager->getPositionByName($object);
            /** @var BannerPosition $data */
            $data = $builder->getData();
            if ($data->getId() && $position && $data->getId() == $position->getId()) {
                return;
            }
            if ($position) {
                $context->buildViolation('A position with this name already exists')->addViolation();
            }
        };
        $builder
            ->add('name', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label'       => 'Name',
                'attr'        => [
                    'placeholder' => 'Name'
                ],
                'constraints' => [
                    new NotBlank(),
                    new Callback([
                        'callback' => $callback
                    ])
                ]
            ])
            ->add('description', 'Symfony\Component\Form\Extension\Core\Type\TextType', [
                'label'       => 'Position description',
                'attr'        => [
                    'placeholder' => 'Position description'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('banner', 'Symfony\Bridge\Doctrine\Form\Type\EntityType', [
                'class'        => 'Wucdbm\Bundle\BannerBundle\Entity\Banner',
                'choice_label' => 'name',
                'placeholder'  => 'Banner',
                'attr'         => [
                    'class' => 'select2'
                ],
                'required'     => false
            ])
            ->add('isActive', 'Symfony\Component\Form\Extension\Core\Type\CheckboxType', [
                'label'    => 'Active',
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => 'Wucdbm\Bundle\BannerBundle\Entity\BannerPosition'
        ]);
    }

}
