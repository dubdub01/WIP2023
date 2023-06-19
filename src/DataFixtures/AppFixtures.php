<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\Sector;
use App\Entity\Worker;
use DateTimeInterface;
use App\Entity\Company;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $admin = new User();
        $admin->setUsername('duboismax')
            ->setEmail('duboismax01@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword($admin,'aaaaaa'))
            ->setRoles(['ROLE_ADMIN'])
            ->setType('full')
            ->setImage('istockphoto1300845620612x6126467439d9af5f-646759f3d64e1.jpg');

        $manager->persist($admin);

        $worker = new Worker();
        $worker->setFirsname('Maxime')
            ->setLastname('Dubois')
            ->setAge(new DateTime('28-02-1996'))
            ->setGender('Homme')
            ->setDescription('lorem10')
            ->setVisibility('1')
            ->setUser($admin);

        $manager->persist($worker);

        $activity =[
            'Agriculture',
            'Agroalimentaire',
            'Art et culture',
            'Assurance',
            'Banque',
            'Bâtiment et construction',
            'Beauté et bien-être',
            'Chimie',
            'Commerce de détail',
            'Commerce de gros',
            'Communication et médias',
            'Éducation',
            'Énergie',
            'Environnement',
            'Finance',
            'Hôtellerie et restauration',
            'Industrie automobile',
            'Industrie pharmaceutique',
            "Informatique et technologies de l'information",
            'Ingénierie',
            'Logistique et transport',
            'Marketing et publicité',
            'Santé et services sociaux',
            'Services aux entreprises',
            'Services juridiques',
            'Services publics',
            'Télécommunications',
            'Tourisme et loisirs',
            'Vente et distribution',
        ];
        foreach ($activity as $name){
            $sector = new Sector ();
            $sector->setName($name);

            $manager->persist($sector);
        }
        $manager->flush();

        $company = new Company();
        $company->setName('dubdub')
            ->setEMail('dubdub@gmail.com')
            ->setCover('istockphoto-517188688-612x612.jpg')
            ->setDescription('lorem10')
            ->setVisibility('1')
            ->setUser($admin);

        $manager->persist($company);

        $manager->flush();
    }
}
