<?php

namespace AppBundle\Model;

use \PropelPDO;
use AppBundle\Model\om\BaseYear;

class Year extends BaseYear
{
    public function getMonths($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collMonthsPartial && !$this->isNew();
        if (null === $this->collMonths || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collMonths) {
                // return empty collection
                $this->initMonths();
            } else {
                $collMonths = MonthQuery::create(null, $criteria)
                    ->filterByYear($this)
                    ->orderByRank()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collMonthsPartial && count($collMonths)) {
                      $this->initMonths(false);

                      foreach ($collMonths as $obj) {
                        if (false == $this->collMonths->contains($obj)) {
                          $this->collMonths->append($obj);
                        }
                      }

                      $this->collMonthsPartial = true;
                    }

                    $collMonths->getInternalIterator()->rewind();

                    return $collMonths;
                }

                if ($partial && $this->collMonths) {
                    foreach ($this->collMonths as $obj) {
                        if ($obj->isNew()) {
                            $collMonths[] = $obj;
                        }
                    }
                }

                $this->collMonths = $collMonths;
                $this->collMonthsPartial = false;
            }
        }

        return $this->collMonths;
    }

}
