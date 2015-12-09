<?php

namespace AppBundle\Model;

use AppBundle\Model\om\BaseFileCat;

class FileCat extends BaseFileCat
{
	public $select;
	public $select2;

	public function __construct () {		
		$this->select = true;
		$this->select2 = true;
	}
	public function getNextAccNo() {
		return FileQuery::create()->filterByFileCat($this)->count() + 1;
	}
	
   public function getFiles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                // return empty collection
                $this->initFiles();
            } else {
                $collFiles = FileQuery::create(null, $criteria)
					->orderByName('asc')
                    ->filterByFileCat($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collFilesPartial && count($collFiles)) {
                      $this->initFiles(false);

                      foreach ($collFiles as $obj) {
                        if (false == $this->collFiles->contains($obj)) {
                          $this->collFiles->append($obj);
                        }
                      }

                      $this->collFilesPartial = true;
                    }

                    $collFiles->getInternalIterator()->rewind();

                    return $collFiles;
                }

                if ($partial && $this->collFiles) {
                    foreach ($this->collFiles as $obj) {
                        if ($obj->isNew()) {
                            $collFiles[] = $obj;
                        }
                    }
                }

                $this->collFiles = $collFiles;
                $this->collFilesPartial = false;
            }
        }

        return $this->collFiles;
    }	
	
}
