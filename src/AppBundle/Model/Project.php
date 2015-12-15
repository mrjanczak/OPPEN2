<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseProject;

class Project extends BaseProject
{
   public $DocList;
 
    /**
     * Gets an array of Task objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Task[] List of Task objects
     * @throws PropelException
     */
    public function getTasks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collTasksPartial && !$this->isNew();
        if (null === $this->collTasks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collTasks) {
                // return empty collection
                $this->initTasks();
            } else {
                $collTasks = TaskQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collTasksPartial && count($collTasks)) {
                      $this->initTasks(false);

                      foreach ($collTasks as $obj) {
                        if (false == $this->collTasks->contains($obj)) {
                          $this->collTasks->append($obj);
                        }
                      }

                      $this->collTasksPartial = true;
                    }

                    $collTasks->getInternalIterator()->rewind();

                    return $collTasks;
                }

                if ($partial && $this->collTasks) {
                    foreach ($this->collTasks as $obj) {
                        if ($obj->isNew()) {
                            $collTasks[] = $obj;
                        }
                    }
                }

                $this->collTasks = $collTasks;
                $this->collTasksPartial = false;
            }
        }

        return $this->collTasks;
    } 
   
   /**
     * Gets an array of Income objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Income[] List of Income objects
     * @throws PropelException
     */
    public function getIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomesPartial && !$this->isNew();
        if (null === $this->collIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomes) {
                // return empty collection
                $this->initIncomes();
            } else {
                $collIncomes = IncomeQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomesPartial && count($collIncomes)) {
                      $this->initIncomes(false);

                      foreach ($collIncomes as $obj) {
                        if (false == $this->collIncomes->contains($obj)) {
                          $this->collIncomes->append($obj);
                        }
                      }

                      $this->collIncomesPartial = true;
                    }

                    $collIncomes->getInternalIterator()->rewind();

                    return $collIncomes;
                }

                if ($partial && $this->collIncomes) {
                    foreach ($this->collIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collIncomes[] = $obj;
                        }
                    }
                }

                $this->collIncomes = $collIncomes;
                $this->collIncomesPartial = false;
            }
        }

        return $this->collIncomes;
    }
	
	
   /**
     * Gets an array of Cost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Project is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Cost[] List of Cost objects
     * @throws PropelException
     */
    public function getCosts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostsPartial && !$this->isNew();
        if (null === $this->collCosts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCosts) {
                // return empty collection
                $this->initCosts();
            } else {
                $collCosts = CostQuery::create(null, $criteria)
                    ->filterByProject($this)
                    ->orderBySortableRank()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostsPartial && count($collCosts)) {
                      $this->initCosts(false);

                      foreach ($collCosts as $obj) {
                        if (false == $this->collCosts->contains($obj)) {
                          $this->collCosts->append($obj);
                        }
                      }

                      $this->collCostsPartial = true;
                    }

                    $collCosts->getInternalIterator()->rewind();

                    return $collCosts;
                }

                if ($partial && $this->collCosts) {
                    foreach ($this->collCosts as $obj) {
                        if ($obj->isNew()) {
                            $collCosts[] = $obj;
                        }
                    }
                }

                $this->collCosts = $collCosts;
                $this->collCostsPartial = false;
            }
        }

        return $this->collCosts;
    }
	
	
	
}
