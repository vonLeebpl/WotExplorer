<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class BattleDatatable
 *
 * @package AppBundle\Datatables
 */
class BattleDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $router = $this->router;
        $formatter = function($line) use ($router) {
//            $route = $router->generate('battle', array('battle' => $line['id']));
//            $line['datePlayed'] = '<a href="'.$route.'">'.$line['datePlayed'].'</a>';
            if ($line['result'] == 1)
                $line['result'] = '<span style="color: #00AA00"><i class="fa fa-check-square fa-1x"></i> Win</span>';
            elseif ($line['result'] == -1)
                $line['result'] = '<span style="color: red"><i class="fa fa-minus-square fa-1x"></i> Lost</span>';
            else
                $line['result'] ='<span style="color: orangered"><i class="fa fa-dot-circle-o fa-1x"></i> Draw</span>';

            return $line;
        };
        return $formatter;
    }

    private function canDeleteBattle()
    {
        return $this->authorizationChecker->isGranted('ROLE_DELETE_BATTLE');
    }
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-3">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('new_battle_from_replay'),
                    'label' => 'Add battle',
                    'icon' => 'glyphicon glyphicon-plus',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'New battle from replay',
                        'class' => 'btn btn-primary',
                        'role' => 'button'
                    ),
                )
            )
        ));

        $this->features->set(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => false,
            'scroll_y' => '',
            'searching' => true,
            'state_save' => false,
            'delay' => 0,
            'extensions' => array()
        ));

        $this->ajax->set(array(
            'url' => $options['ajaxUrl'],
            'type' => 'GET',
            'pipeline' => 3,
        ));

        if ($this->canDeleteBattle())
            $ord = 2;
        else
            $ord = 1;
        $this->options->set(array(
            'display_start' => 0,
            'defer_loading' => -1,
            'dom' => 'lfrtip',
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => array(array($ord, 'desc')),
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '',
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'class' => Style::BOOTSTRAP_3_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head',
            'use_integration_options' => true,
            'force_dom' => false
        ));

        $this->columnBuilder
            ->add(null, 'multiselect', array(
                'start_html' => '<div class="wrapper" id="testwrapper">',
                'end_html' => '</div>',
                'attributes' => array(
                    'class' => 'testclass',
                    'name' => 'testname',
                ),
               'add_if' => function() {
                    return $this->canDeleteBattle();
                },
                'actions' => array(
                    array(
                        'route' => 'battle_bulk_delete',
                        'render_if' => function() {
                            return $this->canDeleteBattle();
                        },
                        'label' => 'Delete',
                        'icon' => 'fa fa-times',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Delete',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
            ->add('id', 'column', array(
                'title' => 'Id',
                'visible' => false,
            ))
            ->add('datePlayed', 'datetime', array(
                'title' => 'Date',
                'date_format' => 'DD-MM-YYYY HH:mm'
            ))
            ->add('result', 'column', array(
                'title' => 'Result',
            ))
            ->add('commanderName', 'column', array(
                'title' => 'Commander',
            ))
            ->add('enemyClan', 'column', array(
                'title' => 'Enemy',
            ))
            ->add('score', 'column', array(
                'title' => 'Score',
            ))
            ->add('mapName', 'column', array(
                'title' => 'Map',
            ))
            ->add('stronghold', 'boolean', array(
                'title' => 'SH',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => 'glyphicon glyphicon-remove',
            ))
            ->add(null, 'action', array(
                'title' => 'Actions',
                'actions' => array(
                    array(
                        'route' => 'battle',
                        'route_parameters' => array(
                            'battle' => 'id'
                        ),
                        'label' => 'Details',
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Show',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                   /* array(
                        'route' => 'battle_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )*/
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Battle';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'battle_datatable';
    }
}
