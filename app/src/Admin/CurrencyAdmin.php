<?php

namespace App\Admin;

use App\Entity\Rate;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

final class CurrencyAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
        $collection->remove('delete');
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form->add('code', TextType::class, ['disabled' => true]);
        $form->add('isActive', CheckboxType::class, ['required' => false]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('name');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('name');
        $list->addIdentifier('code');
        $list->addIdentifier('isActive');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('name');
        $show->add('code');
        $show->add('isActive');
        $show->add('rates', CollectionType::class, [
            'template' => 'admin/rates-list.html.twig',
            'entry_type' => Rate::class,
            'allow_add' => false,
            'by_reference' => false,
        ]);
    }
}