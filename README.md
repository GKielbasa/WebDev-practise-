# WebDev-practise-
Web dev practise 
Pasja informatyki

public function findOneByPesel(int $pesel): ?User {
    return $this->createQueryBuilder('u')
        ->where('u.pesel = :pesel')
        ->setParameter('pesel', $pesel)
        ->getQuery()
        ->getOneOrNullResult();
}
