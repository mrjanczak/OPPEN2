<?php

namespace AppBundle\Model;

use \PropelPDO;
use \PropelObjectCollection;
use AppBundle\Model\om\BaseBookk;

class Bookk extends BaseBookk
{
	protected $BookkEntries;

    public function __construct()
    {
        $this->BookkEntries = new PropelObjectCollection();
    }	

    /**
     * Gets an array of BookkEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Bookk is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     * @throws PropelException
     */
    public function getBookkEntries($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesPartial && !$this->isNew();
        if (null === $this->collBookkEntries || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookkEntries) {
                // return empty collection
                $this->initBookkEntries();
            } else {
                $collBookkEntries = BookkEntryQuery::create(null, $criteria)
                    ->filterByBookk($this)
                    ->orderById()
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookkEntriesPartial && count($collBookkEntries)) {
                      $this->initBookkEntries(false);

                      foreach ($collBookkEntries as $obj) {
                        if (false == $this->collBookkEntries->contains($obj)) {
                          $this->collBookkEntries->append($obj);
                        }
                      }

                      $this->collBookkEntriesPartial = true;
                    }

                    $collBookkEntries->getInternalIterator()->rewind();

                    return $collBookkEntries;
                }

                if ($partial && $this->collBookkEntries) {
                    foreach ($this->collBookkEntries as $obj) {
                        if ($obj->isNew()) {
                            $collBookkEntries[] = $obj;
                        }
                    }
                }

                $this->collBookkEntries = $collBookkEntries;
                $this->collBookkEntriesPartial = false;
            }
        }

        return $this->collBookkEntries;
    }

	public function setNewNo() {
		$No = BookkQuery::create()
			->filterByDoc($this->getDoc())
			->filterByIsAccepted(1)
			->count(); //this Bookk is already included
		return $this->setNo($No);
	}
}
