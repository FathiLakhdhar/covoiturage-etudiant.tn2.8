<?php
/**
 * Created by PhpStorm.
 * User: TL50
 * Date: 23/01/2017
 * Time: 22:35
 */

namespace Wanasni\WebsocketBundle\Topic;

use Doctrine\ORM\EntityManager;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Symfony\Component\DependencyInjection\Container;

class AcmeTopic implements TopicInterface
{

    private $container;
    private $em;
    protected $clientManipulator;
    /**
     * AcmeTopic constructor.
     */
    public function __construct(Container $container, EntityManager $em, ClientManipulatorInterface $clientManipulator)
    {
        $this->container= $container;
        $this->em= $em;
        $this->clientManipulator = $clientManipulator;
    }


    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        //$user1 = $this->clientManipulator->findByUsername($topic, 'fathilakhdhar');


        $topic->broadcast(['getAllClient' => $this->clientManipulator->getClient($connection)]);

    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $topic->broadcast(['msg' => $connection->resourceId . " has left " . $topic->getId()]);
    }

    /**
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param WampRequest $request
     * @param $event
     * @param  array $exclude
     * @param  array $eligible
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $topic->broadcast([
            'msg' => $event,
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'acme.topic';
    }


}