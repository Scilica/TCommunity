<?php

namespace TC\WriterBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * GuidesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GuidesRepository extends \Doctrine\ORM\EntityRepository
{
	public function FindAllGuides($page, $nbPerPage)
	{

		$query = $this
			->createQueryBuilder('gui')
			->orderBy('gui.date', 'DESC')
			->leftJoin('gui.game', 'g')
			->addSelect('g')
			->where('gui.published = :published')
			    ->setParameter('published', true)
		;

		$query
		->setFirstResult(($page-1) * $nbPerPage)
		->setMaxResults($nbPerPage)
		;

		return new Paginator($query, true);
	}

	public function FindThisGuide($jeux, $category, $id)
	{
		$queryBuilder = $this
			->createQueryBuilder('gui')
			->Join('gui.game', 'g')
			->addSelect('g')
			->Join('gui.category', 'c')
			->addSelect('c')
			->where('g.title = :title')
				->setParameter('title', $jeux)
			->andWhere('gui.id = :id')
				->setParameter('id', $id)
			->andwhere('c.title = :titlecategory')
				->setParameter('titlecategory', $category)
			->andWhere('gui.published = :published')
			    ->setParameter('published', true)
		;

		return $queryBuilder
			->getQuery()
			->getResult()
		;
	}

	public function FindThisGuideAdmin($jeux, $category, $id)
	{
		$queryBuilder = $this
			->createQueryBuilder('gui')
			->Join('gui.game', 'g')
			->addSelect('g')
			->Join('gui.category', 'c')
			->addSelect('c')
			->where('g.title = :title')
				->setParameter('title', $jeux)
			->andWhere('gui.id = :id')
				->setParameter('id', $id)
			->andwhere('c.title = :titlecategory')
				->setParameter('titlecategory', $category)
		;

		return $queryBuilder
			->getQuery()
			->getResult()
		;
	}

	public function FindGuides($maxresults)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.game', 'g')
		->addSelect('g')
		->where('gui.published = :published')
			->setParameter('published', true)
		;

		$query
		->setMaxResults($maxresults)
		;
		return $query
			->getQuery()
			->getResult()
			;
	}

			public function FindOtherGuides($jeux, $id)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.game', 'g')
		->addSelect('g')
		->where('gui.id NOT LIKE :id')
		 ->setParameter('id', $id)
		->andWhere('g.title = :title')
		 ->setParameter('title', $jeux)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
		 ;

		 return $query
			->getQuery()
			->setMaxResults(3)
			->getResult()
			;
	}

	public function FindGameGuides($jeux)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.game', 'g')
		->addSelect('g')
			->where('g.title = :title')
				->setParameter('title', $jeux)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
			;
		return $query
			->getQuery()
			->setMaxResults(4)
			->getResult()
			;
	}

	public function FindTagGuides($tag)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.tag', 't')
		->addSelect('t')
		->where('t.title = :title')
		 ->setParameter('title' , $tag)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
		 ;

		return $query
			->getQuery()
			->setMaxResults(4)
			->getResult()

		 ;
	}

		public function FindFiltredGuides($tag, $page, $nbPerPage)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.tag', 't')
		->addSelect('t')
		->where('t.title = :title')
		    ->setParameter('title' , $tag)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
		 ;

		 $query
		->setFirstResult(($page-1) * $nbPerPage)
		->setMaxResults($nbPerPage)
		;

		return new Paginator($query, true);
	}

	public function FindFiltredGuidesByGame($jeux, $page, $nbPerPage)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.game', 'g')
		->addSelect('g')
		->where('g.title = :title')
		    ->setParameter('title' , $jeux)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
		 ;

		 $query
		->setFirstResult(($page-1) * $nbPerPage)
		->setMaxResults($nbPerPage)
		;

		return new Paginator($query, true);
	}
		public function FindFiltredGuidesByCategory($jeux,$category, $page, $nbPerPage)
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.date', 'DESC')
		->leftJoin('gui.game', 'g')
		->addSelect('g')
		->leftJoin('gui.category', 'c')
		->addSelect('c')
		->where('g.title = :title')
		    ->setParameter('title' , $jeux)
		->andWhere('c.title = :titlecat')
		    ->setParameter('titlecat', $category)
		->andWhere('gui.published = :published')
			->setParameter('published', true)
		 ;

		 $query
		->setFirstResult(($page-1) * $nbPerPage)
		->setMaxResults($nbPerPage)
		;

		return new Paginator($query, true);
	}

	public function research($value)
	{
		$queryBuilder = $this
		->createQueryBuilder('gui')
		->leftJoin('gui.game', 'g')
			->addSelect('g')
		->leftJoin('gui.category', 'c')
		    ->addSelect('c')
		->where('gui.title = :title')
		->orwhere('g.title = :title')
		->orwhere('gui.id = :title')
		->orwhere('gui.author = :title')
		->orwhere('c.title = :title')
				->setParameter('title', $value)
		;

		return $queryBuilder
		    ->getQuery()
		    ->getResult()
		  ;
	}

		public function CountGuides()
	{
		$qb = $this
		->createQueryBuilder('gui')
		->where('gui.published = :published')
			    ->setParameter('published', true);

		$qb ->select($qb->expr()->count('gui'))
		;

		return (int) $qb->getQuery()->getSingleScalarResult();
		 

	}

	public function FindGuidesUnpublished()
	{
		$queryBuilder = $this
		->createQueryBuilder('gui')
		->where('gui.published = :published')
			    ->setParameter('published', false);
		return $queryBuilder
		    ->getQuery()
		    ->getResult()
		  ;
	}

	public function CountGuidesLastMonth()
	{
		$qb = $this
		->createQueryBuilder('gui')
		->where('gui.published = :published')
			    ->setParameter('published', true)
		->andWhere('gui.date BETWEEN :start AND :end')
			->setParameter('end', new \DateTime('now'))
			->setParameter('start', new \DateTime('-30days'));

		$qb ->select($qb->expr()->count('gui'))
		;

		return (int) $qb->getQuery()->getSingleScalarResult();
		 

	}

	public function CountGuidesByGame($jeux)
	{
		$qb = $this
		->createQueryBuilder('gui')
		->leftJoin('gui.game', 'g')
		    ->addSelect('g');
		$qb ->select($qb->expr()->count('gui'))
		->where('g.title = :title')
				->setParameter('title', $jeux)
		;

		return (int) $qb->getQuery()->getSingleScalarResult();
		 

	}

	public function FindGuidesByAdminGame($jeux)
	{
		$queryBuilder = $this
		->createQueryBuilder('gui')
		->leftJoin('gui.game', 'g')
		    ->addSelect('g')
		->where('g.title = :title')
				->setParameter('title', $jeux)
		;

		return $queryBuilder
		    ->getQuery()
		    ->getResult()
		  ;

	}

	public function researchFiltred($jeux, $value)
	{
		$queryBuilder = $this
		->createQueryBuilder('gui')
		->leftJoin('gui.game', 'g')
			->addSelect('g')
		->leftJoin('gui.category', 'c')
		    ->addSelect('c')
		->where('gui.title = :value AND g.title LIKE :title')
		->orwhere('gui.id = :value AND g.title LIKE :title')
		->orwhere('gui.author = :value AND g.title LIKE :title')
		->orwhere('c.title = :value AND g.title LIKE :title')
				->setParameter('value', $value)
				->setParameter('title', $jeux)
		;

		return $queryBuilder
		    ->getQuery()
		    ->getResult()
		  ;
	}

	public function FindBestGuides()
	{
		$query = $this
		->createQueryBuilder('gui')
		->orderBy('gui.singularViewCount', 'DESC')
		->where('gui.published = :published')
			    ->setParameter('published', true)
		->andWhere('gui.singularViewCount NOT LIKE :countzero')
				->setParameter('countzero', 0)

		 ;

		 return $query
			->getQuery()
			->setMaxResults(5)
			->getResult()
			;
	}
}
