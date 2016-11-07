<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Client;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadClientData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $clientsData = [
        [
            'username' => 'test01',
        ],
        [
            'username' => 'test02',
        ],
        [
            'username' => 'test03',
        ]
    ];

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $admin = new Client();
        $admin
            ->setUsername('admin')
            ->setEmail('admin@mailinator.com')
            ->addRole('ROLE_ADMIN')
            ->setPlainPassword('123456')
            ->setEnabled(true)
        ;
        $manager->persist($admin);
        $manager->flush();

        foreach($this->clientsData as $data) {
            $user = new Client();
            $user
                ->setUsername($data['username'])
                ->setEmail(sprintf('%s@mailinator.com', $data['username']))
                ->addRole('ROLE_USER')
                ->setPlainPassword('123456')
                ->setEnabled(true)
            ;
            $manager->persist($user);
            $manager->flush();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
