<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;

final class RateAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
        $collection->remove('edit');
        $collection->remove('delete');
        $collection->remove('show');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('sourceCurrency.code');
        $list->addIdentifier('targetCurrency.code');
        $list->addIdentifier('rate');
    }
}