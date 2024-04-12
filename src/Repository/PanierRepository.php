<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panier>
 *
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {

          /**
     * Extracts data from Panier objects for rendering in Twig template.
     *
     * @param Panier[] $paniers
     * @return array
     */
        parent::__construct($registry, Panier::class);
    }

//    /**
//     * @return Panier[] Returns an array of Panier objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Panier
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function extractDataForTwig(array $paniers): array
{
    $extractedData = [];

    foreach ($paniers as $panier) {
        $productDetails = [
            'productName' => $panier->getIdProduit()->getNomProduit(),
            'quantity' => $panier->getQuantiteparproduit(),
            'price' => $panier->getIdProduit()->getPrixProduit(),
        ];

        $extractedData[] = $productDetails;
    }

    return $extractedData;
}
}
