<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocQuery;
use Oppen\ProjectBundle\Model\CostIncome;
use Oppen\ProjectBundle\Model\CostIncomeQuery;
use Oppen\ProjectBundle\Model\CostPeer;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\ProjectQuery;

abstract class BaseCost extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\CostPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CostPeer
     */
    protected static $peer;

    /**
     * The flag var to prevent infinite loop in deep copy
     * @var       boolean
     */
    protected $startCopy = false;

    /**
     * The value for the id field.
     * @var        int
     */
    protected $id;

    /**
     * The value for the name field.
     * @var        string
     */
    protected $name;

    /**
     * The value for the value field.
     * @var        double
     */
    protected $value;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * The value for the project_id field.
     * @var        int
     */
    protected $project_id;

    /**
     * The value for the file_id field.
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the bank_acc_id field.
     * @var        int
     */
    protected $bank_acc_id;

    /**
     * The value for the cost_acc_id field.
     * @var        int
     */
    protected $cost_acc_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Project
     */
    protected $aProject;

    /**
     * @var        File
     */
    protected $aFile;

    /**
     * @var        Account
     */
    protected $aBankAcc;

    /**
     * @var        Account
     */
    protected $aCostAcc;

    /**
     * @var        PropelObjectCollection|CostIncome[] Collection to store aggregation of CostIncome objects.
     */
    protected $collCostIncomes;
    protected $collCostIncomesPartial;

    /**
     * @var        PropelObjectCollection|CostDoc[] Collection to store aggregation of CostDoc objects.
     */
    protected $collCostDocs;
    protected $collCostDocsPartial;

    /**
     * @var        PropelObjectCollection|Contract[] Collection to store aggregation of Contract objects.
     */
    protected $collContracts;
    protected $collContractsPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInSave = false;

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * Flag to prevent endless clearAllReferences($deep=true) loop, if this object is referenced
     * @var        boolean
     */
    protected $alreadyInClearAllReferencesDeep = false;

    // sortable behavior

    /**
     * Queries to be executed in the save transaction
     * @var        array
     */
    protected $sortableQueries = array();

    /**
     * The old scope value.
     * @var        int
     */
    protected $oldScope;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costIncomesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costDocsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contractsScheduledForDeletion = null;

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {

        return $this->id;
    }

    /**
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {

        return $this->name;
    }

    /**
     * Get the [value] column value.
     *
     * @return double
     */
    public function getValue()
    {

        return $this->value;
    }

    /**
     * Get the [comment] column value.
     *
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
    }

    /**
     * Get the [project_id] column value.
     *
     * @return int
     */
    public function getProjectId()
    {

        return $this->project_id;
    }

    /**
     * Get the [file_id] column value.
     *
     * @return int
     */
    public function getFileId()
    {

        return $this->file_id;
    }

    /**
     * Get the [bank_acc_id] column value.
     *
     * @return int
     */
    public function getBankAccId()
    {

        return $this->bank_acc_id;
    }

    /**
     * Get the [cost_acc_id] column value.
     *
     * @return int
     */
    public function getCostAccId()
    {

        return $this->cost_acc_id;
    }

    /**
     * Get the [sortable_rank] column value.
     *
     * @return int
     */
    public function getSortableRank()
    {

        return $this->sortable_rank;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CostPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = CostPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [value] column.
     *
     * @param  double $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setValue($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->value !== $v) {
            $this->value = $v;
            $this->modifiedColumns[] = CostPeer::VALUE;
        }


        return $this;
    } // setValue()

    /**
     * Set the value of [comment] column.
     *
     * @param  string $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = CostPeer::COMMENT;
        }


        return $this;
    } // setComment()

    /**
     * Set the value of [project_id] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setProjectId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->project_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->project_id;

            $this->project_id = $v;
            $this->modifiedColumns[] = CostPeer::PROJECT_ID;
        }

        if ($this->aProject !== null && $this->aProject->getId() !== $v) {
            $this->aProject = null;
        }


        return $this;
    } // setProjectId()

    /**
     * Set the value of [file_id] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[] = CostPeer::FILE_ID;
        }

        if ($this->aFile !== null && $this->aFile->getId() !== $v) {
            $this->aFile = null;
        }


        return $this;
    } // setFileId()

    /**
     * Set the value of [bank_acc_id] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setBankAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->bank_acc_id !== $v) {
            $this->bank_acc_id = $v;
            $this->modifiedColumns[] = CostPeer::BANK_ACC_ID;
        }

        if ($this->aBankAcc !== null && $this->aBankAcc->getId() !== $v) {
            $this->aBankAcc = null;
        }


        return $this;
    } // setBankAccId()

    /**
     * Set the value of [cost_acc_id] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setCostAccId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->cost_acc_id !== $v) {
            $this->cost_acc_id = $v;
            $this->modifiedColumns[] = CostPeer::COST_ACC_ID;
        }

        if ($this->aCostAcc !== null && $this->aCostAcc->getId() !== $v) {
            $this->aCostAcc = null;
        }


        return $this;
    } // setCostAccId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Cost The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = CostPeer::SORTABLE_RANK;
        }


        return $this;
    } // setSortableRank()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
        // otherwise, everything was equal, so return true
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array $row The row returned by PDOStatement->fetch(PDO::FETCH_NUM)
     * @param int $startcol 0-based offset column which indicates which resultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false)
    {
        try {

            $this->id = ($row[$startcol + 0] !== null) ? (int) $row[$startcol + 0] : null;
            $this->name = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->value = ($row[$startcol + 2] !== null) ? (double) $row[$startcol + 2] : null;
            $this->comment = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->project_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->file_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->bank_acc_id = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->cost_acc_id = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->sortable_rank = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 9; // 9 = CostPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Cost object", $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {

        if ($this->aProject !== null && $this->project_id !== $this->aProject->getId()) {
            $this->aProject = null;
        }
        if ($this->aFile !== null && $this->file_id !== $this->aFile->getId()) {
            $this->aFile = null;
        }
        if ($this->aBankAcc !== null && $this->bank_acc_id !== $this->aBankAcc->getId()) {
            $this->aBankAcc = null;
        }
        if ($this->aCostAcc !== null && $this->cost_acc_id !== $this->aCostAcc->getId()) {
            $this->aCostAcc = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param boolean $deep (optional) Whether to also de-associated any related objects.
     * @param PropelPDO $con (optional) The PropelPDO connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CostPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aProject = null;
            $this->aFile = null;
            $this->aBankAcc = null;
            $this->aCostAcc = null;
            $this->collCostIncomes = null;

            $this->collCostDocs = null;

            $this->collContracts = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param PropelPDO $con
     * @return void
     * @throws PropelException
     * @throws Exception
     * @see        BaseObject::setDeleted()
     * @see        BaseObject::isDeleted()
     */
    public function delete(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CostQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            CostPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            CostPeer::clearInstancePool();

            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $con->commit();
                $this->setDeleted(true);
            } else {
                $con->commit();
            }
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @throws Exception
     * @see        doSave()
     */
    public function save(PropelPDO $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            // sortable behavior
            $this->processSortableQueries($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // sortable behavior
                if (!$this->isColumnModified(CostPeer::RANK_COL)) {
                    $this->setSortableRank(CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(CostPeer::PROJECT_ID)) && !$this->isColumnModified(CostPeer::RANK_COL)) { CostPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
                    $this->insertAtBottom($con);
                }

            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CostPeer::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param PropelPDO $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see        save()
     */
    protected function doSave(PropelPDO $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aProject !== null) {
                if ($this->aProject->isModified() || $this->aProject->isNew()) {
                    $affectedRows += $this->aProject->save($con);
                }
                $this->setProject($this->aProject);
            }

            if ($this->aFile !== null) {
                if ($this->aFile->isModified() || $this->aFile->isNew()) {
                    $affectedRows += $this->aFile->save($con);
                }
                $this->setFile($this->aFile);
            }

            if ($this->aBankAcc !== null) {
                if ($this->aBankAcc->isModified() || $this->aBankAcc->isNew()) {
                    $affectedRows += $this->aBankAcc->save($con);
                }
                $this->setBankAcc($this->aBankAcc);
            }

            if ($this->aCostAcc !== null) {
                if ($this->aCostAcc->isModified() || $this->aCostAcc->isNew()) {
                    $affectedRows += $this->aCostAcc->save($con);
                }
                $this->setCostAcc($this->aCostAcc);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }

            if ($this->costIncomesScheduledForDeletion !== null) {
                if (!$this->costIncomesScheduledForDeletion->isEmpty()) {
                    CostIncomeQuery::create()
                        ->filterByPrimaryKeys($this->costIncomesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costIncomesScheduledForDeletion = null;
                }
            }

            if ($this->collCostIncomes !== null) {
                foreach ($this->collCostIncomes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costDocsScheduledForDeletion !== null) {
                if (!$this->costDocsScheduledForDeletion->isEmpty()) {
                    CostDocQuery::create()
                        ->filterByPrimaryKeys($this->costDocsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costDocsScheduledForDeletion = null;
                }
            }

            if ($this->collCostDocs !== null) {
                foreach ($this->collCostDocs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contractsScheduledForDeletion !== null) {
                if (!$this->contractsScheduledForDeletion->isEmpty()) {
                    ContractQuery::create()
                        ->filterByPrimaryKeys($this->contractsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->contractsScheduledForDeletion = null;
                }
            }

            if ($this->collContracts !== null) {
                foreach ($this->collContracts as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param PropelPDO $con
     *
     * @throws PropelException
     * @see        doSave()
     */
    protected function doInsert(PropelPDO $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[] = CostPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CostPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CostPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CostPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(CostPeer::VALUE)) {
            $modifiedColumns[':p' . $index++]  = '`value`';
        }
        if ($this->isColumnModified(CostPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`comment`';
        }
        if ($this->isColumnModified(CostPeer::PROJECT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`project_id`';
        }
        if ($this->isColumnModified(CostPeer::FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_id`';
        }
        if ($this->isColumnModified(CostPeer::BANK_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`bank_acc_id`';
        }
        if ($this->isColumnModified(CostPeer::COST_ACC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`cost_acc_id`';
        }
        if ($this->isColumnModified(CostPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `cost` (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case '`id`':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case '`name`':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case '`value`':
                        $stmt->bindValue($identifier, $this->value, PDO::PARAM_STR);
                        break;
                    case '`comment`':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                    case '`project_id`':
                        $stmt->bindValue($identifier, $this->project_id, PDO::PARAM_INT);
                        break;
                    case '`file_id`':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);
                        break;
                    case '`bank_acc_id`':
                        $stmt->bindValue($identifier, $this->bank_acc_id, PDO::PARAM_INT);
                        break;
                    case '`cost_acc_id`':
                        $stmt->bindValue($identifier, $this->cost_acc_id, PDO::PARAM_INT);
                        break;
                    case '`sortable_rank`':
                        $stmt->bindValue($identifier, $this->sortable_rank, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param PropelPDO $con
     *
     * @see        doSave()
     */
    protected function doUpdate(PropelPDO $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        BasePeer::doUpdate($selectCriteria, $valuesCriteria, $con);
    }

    /**
     * Array of ValidationFailed objects.
     * @var        array ValidationFailed[]
     */
    protected $validationFailures = array();

    /**
     * Gets any ValidationFailed objects that resulted from last call to validate().
     *
     *
     * @return array ValidationFailed[]
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
    }

    /**
     * Validates the objects modified field values and all objects related to this table.
     *
     * If $columns is either a column name or an array of column names
     * only those columns are validated.
     *
     * @param mixed $columns Column name or an array of column names.
     * @return boolean Whether all columns pass validation.
     * @see        doValidate()
     * @see        getValidationFailures()
     */
    public function validate($columns = null)
    {
        $res = $this->doValidate($columns);
        if ($res === true) {
            $this->validationFailures = array();

            return true;
        }

        $this->validationFailures = $res;

        return false;
    }

    /**
     * This function performs the validation work for complex object models.
     *
     * In addition to checking the current object, all related objects will
     * also be validated.  If all pass then <code>true</code> is returned; otherwise
     * an aggregated array of ValidationFailed objects will be returned.
     *
     * @param array $columns Array of column names to validate.
     * @return mixed <code>true</code> if all validations pass; array of <code>ValidationFailed</code> objects otherwise.
     */
    protected function doValidate($columns = null)
    {
        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            $failureMap = array();


            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aProject !== null) {
                if (!$this->aProject->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aProject->getValidationFailures());
                }
            }

            if ($this->aFile !== null) {
                if (!$this->aFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFile->getValidationFailures());
                }
            }

            if ($this->aBankAcc !== null) {
                if (!$this->aBankAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aBankAcc->getValidationFailures());
                }
            }

            if ($this->aCostAcc !== null) {
                if (!$this->aCostAcc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCostAcc->getValidationFailures());
                }
            }


            if (($retval = CostPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCostIncomes !== null) {
                    foreach ($this->collCostIncomes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCostDocs !== null) {
                    foreach ($this->collCostDocs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collContracts !== null) {
                    foreach ($this->collContracts as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }


            $this->alreadyInValidation = false;
        }

        return (!empty($failureMap) ? $failureMap : true);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param string $name name
     * @param string $type The type of fieldname the $name is of:
     *               one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *               BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *               Defaults to BasePeer::TYPE_PHPNAME
     * @return mixed Value of field.
     */
    public function getByName($name, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CostPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getName();
                break;
            case 2:
                return $this->getValue();
                break;
            case 3:
                return $this->getComment();
                break;
            case 4:
                return $this->getProjectId();
                break;
            case 5:
                return $this->getFileId();
                break;
            case 6:
                return $this->getBankAccId();
                break;
            case 7:
                return $this->getCostAccId();
                break;
            case 8:
                return $this->getSortableRank();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     *                    BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                    Defaults to BasePeer::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to true.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = BasePeer::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {
        if (isset($alreadyDumpedObjects['Cost'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Cost'][$this->getPrimaryKey()] = true;
        $keys = CostPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getValue(),
            $keys[3] => $this->getComment(),
            $keys[4] => $this->getProjectId(),
            $keys[5] => $this->getFileId(),
            $keys[6] => $this->getBankAccId(),
            $keys[7] => $this->getCostAccId(),
            $keys[8] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aProject) {
                $result['Project'] = $this->aProject->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFile) {
                $result['File'] = $this->aFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aBankAcc) {
                $result['BankAcc'] = $this->aBankAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aCostAcc) {
                $result['CostAcc'] = $this->aCostAcc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCostIncomes) {
                $result['CostIncomes'] = $this->collCostIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCostDocs) {
                $result['CostDocs'] = $this->collCostDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collContracts) {
                $result['Contracts'] = $this->collContracts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param string $name peer name
     * @param mixed $value field value
     * @param string $type The type of fieldname the $name is of:
     *                     one of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                     BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     *                     Defaults to BasePeer::TYPE_PHPNAME
     * @return void
     */
    public function setByName($name, $value, $type = BasePeer::TYPE_PHPNAME)
    {
        $pos = CostPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

        $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param int $pos position in xml schema
     * @param mixed $value field value
     * @return void
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setValue($value);
                break;
            case 3:
                $this->setComment($value);
                break;
            case 4:
                $this->setProjectId($value);
                break;
            case 5:
                $this->setFileId($value);
                break;
            case 6:
                $this->setBankAccId($value);
                break;
            case 7:
                $this->setCostAccId($value);
                break;
            case 8:
                $this->setSortableRank($value);
                break;
        } // switch()
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME,
     * BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM.
     * The default key type is the column's BasePeer::TYPE_PHPNAME
     *
     * @param array  $arr     An array to populate the object from.
     * @param string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = BasePeer::TYPE_PHPNAME)
    {
        $keys = CostPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setValue($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setComment($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setProjectId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFileId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setBankAccId($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setCostAccId($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setSortableRank($arr[$keys[8]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CostPeer::DATABASE_NAME);

        if ($this->isColumnModified(CostPeer::ID)) $criteria->add(CostPeer::ID, $this->id);
        if ($this->isColumnModified(CostPeer::NAME)) $criteria->add(CostPeer::NAME, $this->name);
        if ($this->isColumnModified(CostPeer::VALUE)) $criteria->add(CostPeer::VALUE, $this->value);
        if ($this->isColumnModified(CostPeer::COMMENT)) $criteria->add(CostPeer::COMMENT, $this->comment);
        if ($this->isColumnModified(CostPeer::PROJECT_ID)) $criteria->add(CostPeer::PROJECT_ID, $this->project_id);
        if ($this->isColumnModified(CostPeer::FILE_ID)) $criteria->add(CostPeer::FILE_ID, $this->file_id);
        if ($this->isColumnModified(CostPeer::BANK_ACC_ID)) $criteria->add(CostPeer::BANK_ACC_ID, $this->bank_acc_id);
        if ($this->isColumnModified(CostPeer::COST_ACC_ID)) $criteria->add(CostPeer::COST_ACC_ID, $this->cost_acc_id);
        if ($this->isColumnModified(CostPeer::SORTABLE_RANK)) $criteria->add(CostPeer::SORTABLE_RANK, $this->sortable_rank);

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(CostPeer::DATABASE_NAME);
        $criteria->add(CostPeer::ID, $this->id);

        return $criteria;
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param  int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {

        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param object $copyObj An object of Cost (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setValue($this->getValue());
        $copyObj->setComment($this->getComment());
        $copyObj->setProjectId($this->getProjectId());
        $copyObj->setFileId($this->getFileId());
        $copyObj->setBankAccId($this->getBankAccId());
        $copyObj->setCostAccId($this->getCostAccId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCostIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostIncome($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCostDocs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostDoc($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getContracts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addContract($relObj->copy($deepCopy));
                }
            }

            //unflag object copy
            $this->startCopy = false;
        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return Cost Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Returns a peer instance associated with this om.
     *
     * Since Peer classes are not to have any instance attributes, this method returns the
     * same instance for all member of this class. The method could therefore
     * be static, but this would prevent one from overriding the behavior.
     *
     * @return CostPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CostPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Project object.
     *
     * @param                  Project $v
     * @return Cost The current object (for fluent API support)
     * @throws PropelException
     */
    public function setProject(Project $v = null)
    {
        if ($v === null) {
            $this->setProjectId(NULL);
        } else {
            $this->setProjectId($v->getId());
        }

        $this->aProject = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Project object, it will not be re-added.
        if ($v !== null) {
            $v->addCost($this);
        }


        return $this;
    }


    /**
     * Get the associated Project object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Project The associated Project object.
     * @throws PropelException
     */
    public function getProject(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aProject === null && ($this->project_id !== null) && $doQuery) {
            $this->aProject = ProjectQuery::create()->findPk($this->project_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aProject->addCosts($this);
             */
        }

        return $this->aProject;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return Cost The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFile(File $v = null)
    {
        if ($v === null) {
            $this->setFileId(NULL);
        } else {
            $this->setFileId($v->getId());
        }

        $this->aFile = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addCost($this);
        }


        return $this;
    }


    /**
     * Get the associated File object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return File The associated File object.
     * @throws PropelException
     */
    public function getFile(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFile === null && ($this->file_id !== null) && $doQuery) {
            $this->aFile = FileQuery::create()->findPk($this->file_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFile->addCosts($this);
             */
        }

        return $this->aFile;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Cost The current object (for fluent API support)
     * @throws PropelException
     */
    public function setBankAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setBankAccId(NULL);
        } else {
            $this->setBankAccId($v->getId());
        }

        $this->aBankAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addCostRelatedByBankAccId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getBankAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aBankAcc === null && ($this->bank_acc_id !== null) && $doQuery) {
            $this->aBankAcc = AccountQuery::create()->findPk($this->bank_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aBankAcc->addCostsRelatedByBankAccId($this);
             */
        }

        return $this->aBankAcc;
    }

    /**
     * Declares an association between this object and a Account object.
     *
     * @param                  Account $v
     * @return Cost The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCostAcc(Account $v = null)
    {
        if ($v === null) {
            $this->setCostAccId(NULL);
        } else {
            $this->setCostAccId($v->getId());
        }

        $this->aCostAcc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Account object, it will not be re-added.
        if ($v !== null) {
            $v->addCostRelatedByCostAccId($this);
        }


        return $this;
    }


    /**
     * Get the associated Account object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Account The associated Account object.
     * @throws PropelException
     */
    public function getCostAcc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCostAcc === null && ($this->cost_acc_id !== null) && $doQuery) {
            $this->aCostAcc = AccountQuery::create()->findPk($this->cost_acc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCostAcc->addCostsRelatedByCostAccId($this);
             */
        }

        return $this->aCostAcc;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('CostIncome' == $relationName) {
            $this->initCostIncomes();
        }
        if ('CostDoc' == $relationName) {
            $this->initCostDocs();
        }
        if ('Contract' == $relationName) {
            $this->initContracts();
        }
    }

    /**
     * Clears out the collCostIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Cost The current object (for fluent API support)
     * @see        addCostIncomes()
     */
    public function clearCostIncomes()
    {
        $this->collCostIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collCostIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collCostIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostIncomes($v = true)
    {
        $this->collCostIncomesPartial = $v;
    }

    /**
     * Initializes the collCostIncomes collection.
     *
     * By default this just sets the collCostIncomes collection to an empty array (like clearcollCostIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostIncomes($overrideExisting = true)
    {
        if (null !== $this->collCostIncomes && !$overrideExisting) {
            return;
        }
        $this->collCostIncomes = new PropelObjectCollection();
        $this->collCostIncomes->setModel('CostIncome');
    }

    /**
     * Gets an array of CostIncome objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Cost is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CostIncome[] List of CostIncome objects
     * @throws PropelException
     */
    public function getCostIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostIncomesPartial && !$this->isNew();
        if (null === $this->collCostIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostIncomes) {
                // return empty collection
                $this->initCostIncomes();
            } else {
                $collCostIncomes = CostIncomeQuery::create(null, $criteria)
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

    /**
     * Sets a collection of CostIncome objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costIncomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Cost The current object (for fluent API support)
     */
    public function setCostIncomes(PropelCollection $costIncomes, PropelPDO $con = null)
    {
        $costIncomesToDelete = $this->getCostIncomes(new Criteria(), $con)->diff($costIncomes);


        $this->costIncomesScheduledForDeletion = $costIncomesToDelete;

        foreach ($costIncomesToDelete as $costIncomeRemoved) {
            $costIncomeRemoved->setCost(null);
        }

        $this->collCostIncomes = null;
        foreach ($costIncomes as $costIncome) {
            $this->addCostIncome($costIncome);
        }

        $this->collCostIncomes = $costIncomes;
        $this->collCostIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CostIncome objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CostIncome objects.
     * @throws PropelException
     */
    public function countCostIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostIncomesPartial && !$this->isNew();
        if (null === $this->collCostIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostIncomes());
            }
            $query = CostIncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCost($this)
                ->count($con);
        }

        return count($this->collCostIncomes);
    }

    /**
     * Method called to associate a CostIncome object to this object
     * through the CostIncome foreign key attribute.
     *
     * @param    CostIncome $l CostIncome
     * @return Cost The current object (for fluent API support)
     */
    public function addCostIncome(CostIncome $l)
    {
        if ($this->collCostIncomes === null) {
            $this->initCostIncomes();
            $this->collCostIncomesPartial = true;
        }

        if (!in_array($l, $this->collCostIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostIncome($l);

            if ($this->costIncomesScheduledForDeletion and $this->costIncomesScheduledForDeletion->contains($l)) {
                $this->costIncomesScheduledForDeletion->remove($this->costIncomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostIncome $costIncome The costIncome object to add.
     */
    protected function doAddCostIncome($costIncome)
    {
        $this->collCostIncomes[]= $costIncome;
        $costIncome->setCost($this);
    }

    /**
     * @param	CostIncome $costIncome The costIncome object to remove.
     * @return Cost The current object (for fluent API support)
     */
    public function removeCostIncome($costIncome)
    {
        if ($this->getCostIncomes()->contains($costIncome)) {
            $this->collCostIncomes->remove($this->collCostIncomes->search($costIncome));
            if (null === $this->costIncomesScheduledForDeletion) {
                $this->costIncomesScheduledForDeletion = clone $this->collCostIncomes;
                $this->costIncomesScheduledForDeletion->clear();
            }
            $this->costIncomesScheduledForDeletion[]= $costIncome;
            $costIncome->setCost(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related CostIncomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostIncome[] List of CostIncome objects
     */
    public function getCostIncomesJoinIncome($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostIncomeQuery::create(null, $criteria);
        $query->joinWith('Income', $join_behavior);

        return $this->getCostIncomes($query, $con);
    }

    /**
     * Clears out the collCostDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Cost The current object (for fluent API support)
     * @see        addCostDocs()
     */
    public function clearCostDocs()
    {
        $this->collCostDocs = null; // important to set this to null since that means it is uninitialized
        $this->collCostDocsPartial = null;

        return $this;
    }

    /**
     * reset is the collCostDocs collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostDocs($v = true)
    {
        $this->collCostDocsPartial = $v;
    }

    /**
     * Initializes the collCostDocs collection.
     *
     * By default this just sets the collCostDocs collection to an empty array (like clearcollCostDocs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostDocs($overrideExisting = true)
    {
        if (null !== $this->collCostDocs && !$overrideExisting) {
            return;
        }
        $this->collCostDocs = new PropelObjectCollection();
        $this->collCostDocs->setModel('CostDoc');
    }

    /**
     * Gets an array of CostDoc objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Cost is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CostDoc[] List of CostDoc objects
     * @throws PropelException
     */
    public function getCostDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostDocsPartial && !$this->isNew();
        if (null === $this->collCostDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostDocs) {
                // return empty collection
                $this->initCostDocs();
            } else {
                $collCostDocs = CostDocQuery::create(null, $criteria)
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

    /**
     * Sets a collection of CostDoc objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costDocs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Cost The current object (for fluent API support)
     */
    public function setCostDocs(PropelCollection $costDocs, PropelPDO $con = null)
    {
        $costDocsToDelete = $this->getCostDocs(new Criteria(), $con)->diff($costDocs);


        $this->costDocsScheduledForDeletion = $costDocsToDelete;

        foreach ($costDocsToDelete as $costDocRemoved) {
            $costDocRemoved->setCost(null);
        }

        $this->collCostDocs = null;
        foreach ($costDocs as $costDoc) {
            $this->addCostDoc($costDoc);
        }

        $this->collCostDocs = $costDocs;
        $this->collCostDocsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CostDoc objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CostDoc objects.
     * @throws PropelException
     */
    public function countCostDocs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostDocsPartial && !$this->isNew();
        if (null === $this->collCostDocs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostDocs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostDocs());
            }
            $query = CostDocQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCost($this)
                ->count($con);
        }

        return count($this->collCostDocs);
    }

    /**
     * Method called to associate a CostDoc object to this object
     * through the CostDoc foreign key attribute.
     *
     * @param    CostDoc $l CostDoc
     * @return Cost The current object (for fluent API support)
     */
    public function addCostDoc(CostDoc $l)
    {
        if ($this->collCostDocs === null) {
            $this->initCostDocs();
            $this->collCostDocsPartial = true;
        }

        if (!in_array($l, $this->collCostDocs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostDoc($l);

            if ($this->costDocsScheduledForDeletion and $this->costDocsScheduledForDeletion->contains($l)) {
                $this->costDocsScheduledForDeletion->remove($this->costDocsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostDoc $costDoc The costDoc object to add.
     */
    protected function doAddCostDoc($costDoc)
    {
        $this->collCostDocs[]= $costDoc;
        $costDoc->setCost($this);
    }

    /**
     * @param	CostDoc $costDoc The costDoc object to remove.
     * @return Cost The current object (for fluent API support)
     */
    public function removeCostDoc($costDoc)
    {
        if ($this->getCostDocs()->contains($costDoc)) {
            $this->collCostDocs->remove($this->collCostDocs->search($costDoc));
            if (null === $this->costDocsScheduledForDeletion) {
                $this->costDocsScheduledForDeletion = clone $this->collCostDocs;
                $this->costDocsScheduledForDeletion->clear();
            }
            $this->costDocsScheduledForDeletion[]= $costDoc;
            $costDoc->setCost(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related CostDocs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostDoc[] List of CostDoc objects
     */
    public function getCostDocsJoinDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostDocQuery::create(null, $criteria);
        $query->joinWith('Doc', $join_behavior);

        return $this->getCostDocs($query, $con);
    }

    /**
     * Clears out the collContracts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Cost The current object (for fluent API support)
     * @see        addContracts()
     */
    public function clearContracts()
    {
        $this->collContracts = null; // important to set this to null since that means it is uninitialized
        $this->collContractsPartial = null;

        return $this;
    }

    /**
     * reset is the collContracts collection loaded partially
     *
     * @return void
     */
    public function resetPartialContracts($v = true)
    {
        $this->collContractsPartial = $v;
    }

    /**
     * Initializes the collContracts collection.
     *
     * By default this just sets the collContracts collection to an empty array (like clearcollContracts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initContracts($overrideExisting = true)
    {
        if (null !== $this->collContracts && !$overrideExisting) {
            return;
        }
        $this->collContracts = new PropelObjectCollection();
        $this->collContracts->setModel('Contract');
    }

    /**
     * Gets an array of Contract objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Cost is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Contract[] List of Contract objects
     * @throws PropelException
     */
    public function getContracts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collContractsPartial && !$this->isNew();
        if (null === $this->collContracts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collContracts) {
                // return empty collection
                $this->initContracts();
            } else {
                $collContracts = ContractQuery::create(null, $criteria)
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

    /**
     * Sets a collection of Contract objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $contracts A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Cost The current object (for fluent API support)
     */
    public function setContracts(PropelCollection $contracts, PropelPDO $con = null)
    {
        $contractsToDelete = $this->getContracts(new Criteria(), $con)->diff($contracts);


        $this->contractsScheduledForDeletion = $contractsToDelete;

        foreach ($contractsToDelete as $contractRemoved) {
            $contractRemoved->setCost(null);
        }

        $this->collContracts = null;
        foreach ($contracts as $contract) {
            $this->addContract($contract);
        }

        $this->collContracts = $contracts;
        $this->collContractsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Contract objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Contract objects.
     * @throws PropelException
     */
    public function countContracts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collContractsPartial && !$this->isNew();
        if (null === $this->collContracts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collContracts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getContracts());
            }
            $query = ContractQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCost($this)
                ->count($con);
        }

        return count($this->collContracts);
    }

    /**
     * Method called to associate a Contract object to this object
     * through the Contract foreign key attribute.
     *
     * @param    Contract $l Contract
     * @return Cost The current object (for fluent API support)
     */
    public function addContract(Contract $l)
    {
        if ($this->collContracts === null) {
            $this->initContracts();
            $this->collContractsPartial = true;
        }

        if (!in_array($l, $this->collContracts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddContract($l);

            if ($this->contractsScheduledForDeletion and $this->contractsScheduledForDeletion->contains($l)) {
                $this->contractsScheduledForDeletion->remove($this->contractsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Contract $contract The contract object to add.
     */
    protected function doAddContract($contract)
    {
        $this->collContracts[]= $contract;
        $contract->setCost($this);
    }

    /**
     * @param	Contract $contract The contract object to remove.
     * @return Cost The current object (for fluent API support)
     */
    public function removeContract($contract)
    {
        if ($this->getContracts()->contains($contract)) {
            $this->collContracts->remove($this->collContracts->search($contract));
            if (null === $this->contractsScheduledForDeletion) {
                $this->contractsScheduledForDeletion = clone $this->collContracts;
                $this->contractsScheduledForDeletion->clear();
            }
            $this->contractsScheduledForDeletion[]= $contract;
            $contract->setCost(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinTemplate($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Template', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinFile($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('File', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinDoc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Doc', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Cost is new, it will return
     * an empty collection; or if this Cost has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Cost.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinMonth($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Month', $join_behavior);

        return $this->getContracts($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->value = null;
        $this->comment = null;
        $this->project_id = null;
        $this->file_id = null;
        $this->bank_acc_id = null;
        $this->cost_acc_id = null;
        $this->sortable_rank = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references to other model objects or collections of model objects.
     *
     * This method is a user-space workaround for PHP's inability to garbage collect
     * objects with circular references (even in PHP 5.3). This is currently necessary
     * when using Propel in certain daemon or large-volume/high-memory operations.
     *
     * @param boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep && !$this->alreadyInClearAllReferencesDeep) {
            $this->alreadyInClearAllReferencesDeep = true;
            if ($this->collCostIncomes) {
                foreach ($this->collCostIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCostDocs) {
                foreach ($this->collCostDocs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContracts) {
                foreach ($this->collContracts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aProject instanceof Persistent) {
              $this->aProject->clearAllReferences($deep);
            }
            if ($this->aFile instanceof Persistent) {
              $this->aFile->clearAllReferences($deep);
            }
            if ($this->aBankAcc instanceof Persistent) {
              $this->aBankAcc->clearAllReferences($deep);
            }
            if ($this->aCostAcc instanceof Persistent) {
              $this->aCostAcc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCostIncomes instanceof PropelCollection) {
            $this->collCostIncomes->clearIterator();
        }
        $this->collCostIncomes = null;
        if ($this->collCostDocs instanceof PropelCollection) {
            $this->collCostDocs->clearIterator();
        }
        $this->collCostDocs = null;
        if ($this->collContracts instanceof PropelCollection) {
            $this->collContracts->clearIterator();
        }
        $this->collContracts = null;
        $this->aProject = null;
        $this->aFile = null;
        $this->aBankAcc = null;
        $this->aCostAcc = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'name' column
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    /**
     * return true is the object is in saving state
     *
     * @return boolean
     */
    public function isAlreadyInSave()
    {
        return $this->alreadyInSave;
    }

    // sortable behavior

    /**
     * Wrap the getter for rank value
     *
     * @return    int
     */
    public function getRank()
    {
        return $this->sortable_rank;
    }

    /**
     * Wrap the setter for rank value
     *
     * @param     int
     * @return    Cost
     */
    public function setRank($v)
    {
        return $this->setSortableRank($v);
    }


    /**
     * Wrap the getter for scope value
     *
     * @param boolean $returnNulls If true and all scope values are null, this will return null instead of a array full with nulls
     *
     * @return    mixed A array or a native type
     */
    public function getScopeValue($returnNulls = true)
    {


        return $this->getProjectId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    Cost
     */
    public function setScopeValue($v)
    {


        return $this->setProjectId($v);

    }

    /**
     * Check if the object is first in the list, i.e. if it has 1 for rank
     *
     * @return    boolean
     */
    public function isFirst()
    {
        return $this->getSortableRank() == 1;
    }

    /**
     * Check if the object is last in the list, i.e. if its rank is the highest rank
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    boolean
     */
    public function isLast(PropelPDO $con = null)
    {
        return $this->getSortableRank() == CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Cost
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = CostQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Cost
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = CostQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() - 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Insert at specified rank
     * The modifications are not persisted until the object is saved.
     *
     * @param     integer    $rank rank value
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Cost the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
        if ($rank < 1 || $rank > $maxRank + 1) {
            throw new PropelException('Invalid rank ' . $rank);
        }
        // move the object in the list, at the given rank
        $this->setSortableRank($rank);
        if ($rank != $maxRank + 1) {
            // Keep the list modification query for the save() transaction
            $this->sortableQueries []= array(
                'callable'  => array(self::PEER, 'shiftRank'),
                'arguments' => array(1, $rank, null, $this->getScopeValue())
            );
        }

        return $this;
    }

    /**
     * Insert in the last rank
     * The modifications are not persisted until the object is saved.
     *
     * @param PropelPDO $con optional connection
     *
     * @return    Cost the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Cost the current object
     */
    public function insertAtTop()
    {
        return $this->insertAtRank(1);
    }

    /**
     * Move the object to a new rank, and shifts the rank
     * Of the objects inbetween the old and new rank accordingly
     *
     * @param     integer   $newRank rank value
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
            throw new PropelException('Invalid rank ' . $newRank);
        }

        $oldRank = $this->getSortableRank();
        if ($oldRank == $newRank) {
            return $this;
        }

        $con->beginTransaction();
        try {
            // shift the objects between the old and the new rank
            $delta = ($oldRank < $newRank) ? -1 : 1;
            CostPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

            // move the object to its new rank
            $this->setSortableRank($newRank);
            $this->save($con);

            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Exchange the rank of the object with the one passed as argument, and saves both objects
     *
     * @param     Cost $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $oldScope = $this->getScopeValue();
            $newScope = $object->getScopeValue();
            if ($oldScope != $newScope) {
                $this->setScopeValue($newScope);
                $object->setScopeValue($oldScope);
            }
            $oldRank = $this->getSortableRank();
            $newRank = $object->getSortableRank();
            $this->setSortableRank($newRank);
            $this->save($con);
            $object->setSortableRank($oldRank);
            $object->save($con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the previous object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $prev = $this->getPrevious($con);
            $this->swapWith($prev, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object higher in the list, i.e. exchanges its rank with the one of the next object
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $next = $this->getNext($con);
            $this->swapWith($next, $con);
            $con->commit();

            return $this;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Move the object to the top of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     */
    public function moveToTop(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }

        return $this->moveToRank(1, $con);
    }

    /**
     * Move the object to the bottom of the list
     *
     * @param     PropelPDO $con optional connection
     *
     * @return integer the old object's rank
     */
    public function moveToBottom(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return false;
        }
        if ($con === null) {
            $con = Propel::getConnection(CostPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = CostQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
            $res = $this->moveToRank($bottom, $con);
            $con->commit();

            return $res;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Removes the current object from the list (moves it to the null scope).
     * The modifications are not persisted until the object is saved.
     *
     * @param     PropelPDO $con optional connection
     *
     * @return    Cost the current object
     */
    public function removeFromList(PropelPDO $con = null)
    {
        // check if object is already removed
        if ($this->getScopeValue() === null) {
            throw new PropelException('Object is already removed (has null scope)');
        }

        // move the object to the end of null scope
        $this->setScopeValue(null);
    //    $this->insertAtBottom($con);

        return $this;
    }

    /**
     * Execute queries that were saved to be run inside the save transaction
     */
    protected function processSortableQueries($con)
    {
        foreach ($this->sortableQueries as $query) {
            $query['arguments'][]= $con;
            call_user_func_array($query['callable'], $query['arguments']);
        }
        $this->sortableQueries = array();
    }

}
