<?php

namespace AppBundle\Controller;

use AppBundle\Wot\WoTReplayParser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends Controller
{

    /**
     * @Route("/test", name="test")
     */
    public function indexAction()
    {
        return $this->render('test/index.html.twig');
/*        $response = new StreamedResponse(function() {
            for ($i = 1; $i <= 10; $i++)
            {
                $message = "";
                $message .= "data: ".'iteration:'.$i.PHP_EOL;
                $message .= PHP_EOL;
                echo $message;
                ob_flush();
                flush();
                sleep(3);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        return $response;*/
    }


    /**
     * @Route("/test/sse", name="testsse")
     */
    public function sseAction()
    {
        $cnt = 0;
        $response = new StreamedResponse(function() use (&$cnt) {
            for ($i = 0; $i <= 100; $i= $i + 2)
            {
                $cnt++;
                $message = "data: ".$i.PHP_EOL;
                $message .= PHP_EOL;
                echo $message;
                ob_flush();
                flush();
                sleep(1);
        }});

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        return $response;
    }

    /**
     * @Route("/test/parse", name="parsereplay")
     */
    public function parseReplayAction()
    {
        $parser = new WoTReplayParser(true);
        $parser->parse('../data/test.wotreplay');

        return'OK';
    }

    /**
     * @Route("/test/package", name="parsepackage")
     */
    public function parsePackageAction()
    {
        $parser = new WoTReplayParser(true);
        $parser->parsePackages('../data/test.wotreplay.tmp.out');

        return'OK';
    }
}
