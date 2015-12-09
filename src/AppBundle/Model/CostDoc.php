<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseCostDoc;

class CostDoc extends BaseCostDoc
{
	public function getCostDocIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostDocIncomesPartial && !$this->isNew();
        if (null === $this->collCostDocIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostDocIncomes) {
                // return empty collection
                $this->initCostDocIncomes();
            } else {
                $collCostDocIncomes = CostDocIncomeQuery::create(null, $criteria)
					->useIncomeQuery()
						->orderBySortableRank()
					->endUse()
                    ->filterByCostDoc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostDocIncomesPartial && count($collCostDocIncomes)) {
                      $this->initCostDocIncomes(false);

                      foreach ($collCostDocIncomes as $obj) {
                        if (false == $this->collCostDocIncomes->contains($obj)) {
                          $this->collCostDocIncomes->append($obj);
                        }
                      }

                      $this->collCostDocIncomesPartial = true;
                    }

                    $collCostDocIncomes->getInternalIterator()->rewind();

                    return $collCostDocIncomes;
                }

                if ($partial && $this->collCostDocIncomes) {
                    foreach ($this->collCostDocIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collCostDocIncomes[] = $obj;
                        }
                    }
                }

                $this->collCostDocIncomes = $collCostDocIncomes;
                $this->collCostDocIncomesPartial = false;
            }
        }

        return $this->collCostDocIncomes;
    }

}
