<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\TypeEvent;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('eventName', TextType::class, [
                'label'=>"Nom de l'événement*",
            ])
            ->add('eventDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Organisé le*',
            ])
            //Ajout pour le type d'événement
            ->add('typeEvent', EntityType::class, [
                'label' => 'Type d\'événement*',
                'class'=> TypeEvent::class,
                'choice_label'=> 'typeName',
                'multiple' => false,
                'required' => true,
            ])

            ->add('eventImageFile', FileType::class,[
                'required' => false,
                'label' => 'Image de l\'événement',
                ])

            ->add('eventDescription', CKEditorType::class, [
                'label'=>"Description de l'événement*",
                'required' => true,
                'config' => [
                    'removePlugins' => 'exportpdf',
                    'editorplaceholder' => 'Pour un vide-dressing, précisez le type de vêtements disponibles (tailles, genres). Pour un atelier, précisez le matériel dont les participants auront besoin.',
                ],
            ])
            
            ->add('coordinateLat', HiddenType::class)
            ->add('coordinateLng', HiddenType::class)

            ->remove('eventSlug')
            ->remove('eventUpdatedAt', DateTimeType::class, [
                'widget'=>'single_text',
                'data'=>new \DateTimeImmutable(),
                'label' => 'Ajouté le :',
            ])
            ->add('infoLocation', CKEditorType::class, [
                'label'=>"Informations pratiques*",
                'required' => true,
                'config' => [
                    'removePlugins' => 'exportpdf',
                    'editorplaceholder' => 'Précisez comment accéder à l\'événement (adresse, interphone, numéro de téléphone...). Ces informations ne seront visibles que par les utilisateurs inscrits à l\'événement.',
                ],
            ])
            ->remove('creator')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
