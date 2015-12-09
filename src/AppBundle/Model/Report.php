<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseReport;

class Report extends BaseReport
{
	public $data;
	
	public $Items;
	public $ItemColls;

   public function getReportEntries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collReportEntriesPartial && !$this->isNew();
        if (null === $this->collReportEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReportEntries) {
                // return empty collection
                $this->initReportEntries();
            } else {
                $collReportEntries = ReportEntryQuery::create(null, $criteria)
                    ->filterByReport($this)
                    ->orderByTreeLeft()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collReportEntriesPartial && count($collReportEntries)) {
                      $this->initReportEntries(false);

                      foreach ($collReportEntries as $obj) {
                        if (false == $this->collReportEntries->contains($obj)) {
                          $this->collReportEntries->append($obj);
                        }
                      }

                      $this->collReportEntriesPartial = true;
                    }

                    $collReportEntries->getInternalIterator()->rewind();

                    return $collReportEntries;
                }

                if ($partial && $this->collReportEntries) {
                    foreach ($this->collReportEntries as $obj) {
                        if ($obj->isNew()) {
                            $collReportEntries[] = $obj;
                        }
                    }
                }

                $this->collReportEntries = $collReportEntries;
                $this->collReportEntriesPartial = false;
            }
        }

        return $this->collReportEntries;
    }
}
