<?php

namespace AppBundle\Controller;

use AppBundle\Wot\WotApiWrapper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $authorised_clans = implode(', ', $this->container->getParameter('security_settings')['authorised_clans']);
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
            'authorised_clans' => $authorised_clans,
        ]);
    }

    /**
     * @Route("/searchclan/{page}", defaults={"page" = 1}, name="search_clan")
     *
     */
    public function searchClanAction(Request $request)
    {
        $clantag = $request->get('clan_tag');
        $panel = [
            'title' => 'Clans found',
            'small_title' => 'search criteria: '.$clantag,
        ];

        $api_wrapper = new WotApiWrapper();
        $api_data = $api_wrapper->searchClan($clantag);

        $panel['description'] = count($api_data).' clans found';
        
        $panel['data'] =
            [
            'table_header' => [ 'Tag', 'Name', 'Members', 'Created'],
            ];
        foreach ($api_data as $item){
            $panel['data']['table_rows'][] = [$item['tag'], $item['name'], $item['members_count'], (new \DateTime('@'.$item['created_at']))->format('d-m-Y')];
        }

        return $this->render('default/searchclan.html.twig', [
            'panel' => $panel,
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
    }
}
