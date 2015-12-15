<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseCost;

class Cost extends BaseCost
{
    public function getCostIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostIncomesPartial && !$this->isNew();
        if (null === $this->collCostIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostIncomes) {
                // return empty collection
                $this->initCostIncomes();
            } else {
                $collCostIncomes = CostIncomeQuery::create(null, $criteria)
					->useIncomeQuery()
						->orderBySortableRank()
					->endUse()                
                    ->filterByCost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostIncomesPartial && count($collCostIncomes)) {
                      $this->initCostIncomes(false);

                      foreach ($collCostIncomes as $obj) {
                        if (false == $this->collCostIncomes->contains($obj)) {
                          $this->collCostIncomes->append($obj);
                        }
                      }

                      $this->collCostIncomesPartial = true;
                    }

                    $collCostIncomes->getInternalIterator()->rewind();

                    return $collCostIncomes;
                }

                if ($partial && $this->collCostIncomes) {
                    foreach ($this->collCostIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collCostIncomes[] = $obj;
                        }
                    }
                }

                $this->collCostIncomes = $collCostIncomes;
                $this->collCostIncomesPartial = false;
            }
        }

        return $this->collCostIncomes;
    }	
    
 
     public function getContracts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collContractsPartial && !$this->isNew();
        if (null === $this->collContracts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContracts) {
                // return empty collection
                $this->initContracts();
            } else {
                $collContracts = ContractQuery::create(null, $criteria)
					->useMonthQuery()	
						->orderBySortableRank()
					->endUse()
					->useFileQuery()
						->orderByName()
					->endUse()                 
                    ->filterByCost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collContractsPartial && count($collContracts)) {
                      $this->initContracts(false);

                      foreach ($collContracts as $obj) {
                        if (false == $this->collContracts->contains($obj)) {
                          $this->collContracts->append($obj);
                        }
                      }

                      $this->collContractsPartial = true;
                    }

                    $collContracts->getInternalIterator()->rewind();

                    return $collContracts;
                }

                if ($partial && $this->collContracts) {
                    foreach ($this->collContracts as $obj) {
                        if ($obj->isNew()) {
                            $collContracts[] = $obj;
                        }
                    }
                }

                $this->collContracts = $collContracts;
                $this->collContractsPartial = false;
            }
        }

        return $this->collContracts;
    }
    
  public function getCostDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostDocsPartial && !$this->isNew();
        if (null === $this->collCostDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostDocs) {
                // return empty collection
                $this->initCostDocs();
            } else {
                $collCostDocs = CostDocQuery::create(null, $criteria)
					->useDocQuery()
						->useMonthQuery()	
							->orderBySortableRank()
						->endUse()					
						->orderByDocIdx()
					->endUse()
                    ->filterByCost($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostDocsPartial && count($collCostDocs)) {
                      $this->initCostDocs(false);

                      foreach ($collCostDocs as $obj) {
                        if (false == $this->collCostDocs->contains($obj)) {
                          $this->collCostDocs->append($obj);
                        }
                      }

                      $this->collCostDocsPartial = true;
                    }

                    $collCostDocs->getInternalIterator()->rewind();

                    return $collCostDocs;
                }

                if ($partial && $this->collCostDocs) {
                    foreach ($this->collCostDocs as $obj) {
                        if ($obj->isNew()) {
                            $collCostDocs[] = $obj;
                        }
                    }
                }

                $this->collCostDocs = $collCostDocs;
                $this->collCostDocsPartial = false;
            }
        }

        return $this->collCostDocs;
    }    
 
}
