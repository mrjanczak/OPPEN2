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
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\Report;
use Oppen\ProjectBundle\Model\ReportQuery;
use Oppen\ProjectBundle\Model\Template;
use Oppen\ProjectBundle\Model\TemplateArchive;
use Oppen\ProjectBundle\Model\TemplateArchiveQuery;
use Oppen\ProjectBundle\Model\TemplatePeer;
use Oppen\ProjectBundle\Model\TemplateQuery;

abstract class BaseTemplate extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\TemplatePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        TemplatePeer
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
     * The value for the symbol field.
     * @var        string
     */
    protected $symbol;

    /**
     * The value for the type field.
     * @var        int
     */
    protected $type;

    /**
     * The value for the as_contract field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_contract;

    /**
     * The value for the as_report field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_report;

    /**
     * The value for the as_booking field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_booking;

    /**
     * The value for the as_transfer field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $as_transfer;

    /**
     * The value for the contents field.
     * @var        string
     */
    protected $contents;

    /**
     * The value for the data field.
     * @var        string
     */
    protected $data;

    /**
     * @var        PropelObjectCollection|Report[] Collection to store aggregation of Report objects.
     */
    protected $collReports;
    protected $collReportsPartial;

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

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $reportsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $contractsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->as_contract = false;
        $this->as_report = false;
        $this->as_booking = false;
        $this->as_transfer = false;
    }

    /**
     * Initializes internal state of BaseTemplate object.
     * @see        applyDefaults()
     */
    public function __construct()
    {
        parent::__construct();
        $this->applyDefaultValues();
    }

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
     * Get the [symbol] column value.
     *
     * @return string
     */
    public function getSymbol()
    {

        return $this->symbol;
    }

    /**
     * Get the [type] column value.
     *
     * @return int
     */
    public function getType()
    {

        return $this->type;
    }

    /**
     * Get the [as_contract] column value.
     *
     * @return boolean
     */
    public function getAsContract()
    {

        return $this->as_contract;
    }

    /**
     * Get the [as_report] column value.
     *
     * @return boolean
     */
    public function getAsReport()
    {

        return $this->as_report;
    }

    /**
     * Get the [as_booking] column value.
     *
     * @return boolean
     */
    public function getAsBooking()
    {

        return $this->as_booking;
    }

    /**
     * Get the [as_transfer] column value.
     *
     * @return boolean
     */
    public function getAsTransfer()
    {

        return $this->as_transfer;
    }

    /**
     * Get the [contents] column value.
     *
     * @return string
     */
    public function getContents()
    {

        return $this->contents;
    }

    /**
     * Get the [data] column value.
     *
     * @return string
     */
    public function getData()
    {

        return $this->data;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = TemplatePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = TemplatePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [symbol] column.
     *
     * @param  string $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setSymbol($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->symbol !== $v) {
            $this->symbol = $v;
            $this->modifiedColumns[] = TemplatePeer::SYMBOL;
        }


        return $this;
    } // setSymbol()

    /**
     * Set the value of [type] column.
     *
     * @param  int $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setType($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->type !== $v) {
            $this->type = $v;
            $this->modifiedColumns[] = TemplatePeer::TYPE;
        }


        return $this;
    } // setType()

    /**
     * Sets the value of the [as_contract] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Template The current object (for fluent API support)
     */
    public function setAsContract($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_contract !== $v) {
            $this->as_contract = $v;
            $this->modifiedColumns[] = TemplatePeer::AS_CONTRACT;
        }


        return $this;
    } // setAsContract()

    /**
     * Sets the value of the [as_report] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Template The current object (for fluent API support)
     */
    public function setAsReport($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_report !== $v) {
            $this->as_report = $v;
            $this->modifiedColumns[] = TemplatePeer::AS_REPORT;
        }


        return $this;
    } // setAsReport()

    /**
     * Sets the value of the [as_booking] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Template The current object (for fluent API support)
     */
    public function setAsBooking($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_booking !== $v) {
            $this->as_booking = $v;
            $this->modifiedColumns[] = TemplatePeer::AS_BOOKING;
        }


        return $this;
    } // setAsBooking()

    /**
     * Sets the value of the [as_transfer] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Template The current object (for fluent API support)
     */
    public function setAsTransfer($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->as_transfer !== $v) {
            $this->as_transfer = $v;
            $this->modifiedColumns[] = TemplatePeer::AS_TRANSFER;
        }


        return $this;
    } // setAsTransfer()

    /**
     * Set the value of [contents] column.
     *
     * @param  string $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setContents($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->contents !== $v) {
            $this->contents = $v;
            $this->modifiedColumns[] = TemplatePeer::CONTENTS;
        }


        return $this;
    } // setContents()

    /**
     * Set the value of [data] column.
     *
     * @param  string $v new value
     * @return Template The current object (for fluent API support)
     */
    public function setData($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (string) $v;
        }

        if ($this->data !== $v) {
            $this->data = $v;
            $this->modifiedColumns[] = TemplatePeer::DATA;
        }


        return $this;
    } // setData()

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
            if ($this->as_contract !== false) {
                return false;
            }

            if ($this->as_report !== false) {
                return false;
            }

            if ($this->as_booking !== false) {
                return false;
            }

            if ($this->as_transfer !== false) {
                return false;
            }

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
            $this->symbol = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->type = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->as_contract = ($row[$startcol + 4] !== null) ? (boolean) $row[$startcol + 4] : null;
            $this->as_report = ($row[$startcol + 5] !== null) ? (boolean) $row[$startcol + 5] : null;
            $this->as_booking = ($row[$startcol + 6] !== null) ? (boolean) $row[$startcol + 6] : null;
            $this->as_transfer = ($row[$startcol + 7] !== null) ? (boolean) $row[$startcol + 7] : null;
            $this->contents = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->data = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 10; // 10 = TemplatePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Template object", $e);
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
            $con = Propel::getConnection(TemplatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = TemplatePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->collReports = null;

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
            $con = Propel::getConnection(TemplatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = TemplateQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // archivable behavior
            if ($ret) {
                if ($this->archiveOnDelete) {
                    // do nothing yet. The object will be archived later when calling TemplateQuery::delete().
                } else {
                    $deleteQuery->setArchiveOnDelete(false);
                    $this->archiveOnDelete = true;
                }
            }

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
            $con = Propel::getConnection(TemplatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $ret = $this->preSave($con);
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                TemplatePeer::addInstanceToPool($this);
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

            if ($this->reportsScheduledForDeletion !== null) {
                if (!$this->reportsScheduledForDeletion->isEmpty()) {
                    foreach ($this->reportsScheduledForDeletion as $report) {
                        // need to save related object because we set the relation to null
                        $report->save($con);
                    }
                    $this->reportsScheduledForDeletion = null;
                }
            }

            if ($this->collReports !== null) {
                foreach ($this->collReports as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->contractsScheduledForDeletion !== null) {
                if (!$this->contractsScheduledForDeletion->isEmpty()) {
                    foreach ($this->contractsScheduledForDeletion as $contract) {
                        // need to save related object because we set the relation to null
                        $contract->save($con);
                    }
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

        $this->modifiedColumns[] = TemplatePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . TemplatePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(TemplatePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(TemplatePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(TemplatePeer::SYMBOL)) {
            $modifiedColumns[':p' . $index++]  = '`symbol`';
        }
        if ($this->isColumnModified(TemplatePeer::TYPE)) {
            $modifiedColumns[':p' . $index++]  = '`type`';
        }
        if ($this->isColumnModified(TemplatePeer::AS_CONTRACT)) {
            $modifiedColumns[':p' . $index++]  = '`as_contract`';
        }
        if ($this->isColumnModified(TemplatePeer::AS_REPORT)) {
            $modifiedColumns[':p' . $index++]  = '`as_report`';
        }
        if ($this->isColumnModified(TemplatePeer::AS_BOOKING)) {
            $modifiedColumns[':p' . $index++]  = '`as_booking`';
        }
        if ($this->isColumnModified(TemplatePeer::AS_TRANSFER)) {
            $modifiedColumns[':p' . $index++]  = '`as_transfer`';
        }
        if ($this->isColumnModified(TemplatePeer::CONTENTS)) {
            $modifiedColumns[':p' . $index++]  = '`contents`';
        }
        if ($this->isColumnModified(TemplatePeer::DATA)) {
            $modifiedColumns[':p' . $index++]  = '`data`';
        }

        $sql = sprintf(
            'INSERT INTO `template` (%s) VALUES (%s)',
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
                    case '`symbol`':
                        $stmt->bindValue($identifier, $this->symbol, PDO::PARAM_STR);
                        break;
                    case '`type`':
                        $stmt->bindValue($identifier, $this->type, PDO::PARAM_INT);
                        break;
                    case '`as_contract`':
                        $stmt->bindValue($identifier, (int) $this->as_contract, PDO::PARAM_INT);
                        break;
                    case '`as_report`':
                        $stmt->bindValue($identifier, (int) $this->as_report, PDO::PARAM_INT);
                        break;
                    case '`as_booking`':
                        $stmt->bindValue($identifier, (int) $this->as_booking, PDO::PARAM_INT);
                        break;
                    case '`as_transfer`':
                        $stmt->bindValue($identifier, (int) $this->as_transfer, PDO::PARAM_INT);
                        break;
                    case '`contents`':
                        $stmt->bindValue($identifier, $this->contents, PDO::PARAM_STR);
                        break;
                    case '`data`':
                        $stmt->bindValue($identifier, $this->data, PDO::PARAM_STR);
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


            if (($retval = TemplatePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collReports !== null) {
                    foreach ($this->collReports as $referrerFK) {
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
        $pos = TemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getSymbol();
                break;
            case 3:
                return $this->getType();
                break;
            case 4:
                return $this->getAsContract();
                break;
            case 5:
                return $this->getAsReport();
                break;
            case 6:
                return $this->getAsBooking();
                break;
            case 7:
                return $this->getAsTransfer();
                break;
            case 8:
                return $this->getContents();
                break;
            case 9:
                return $this->getData();
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
        if (isset($alreadyDumpedObjects['Template'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Template'][$this->getPrimaryKey()] = true;
        $keys = TemplatePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getSymbol(),
            $keys[3] => $this->getType(),
            $keys[4] => $this->getAsContract(),
            $keys[5] => $this->getAsReport(),
            $keys[6] => $this->getAsBooking(),
            $keys[7] => $this->getAsTransfer(),
            $keys[8] => $this->getContents(),
            $keys[9] => $this->getData(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->collReports) {
                $result['Reports'] = $this->collReports->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = TemplatePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setSymbol($value);
                break;
            case 3:
                $this->setType($value);
                break;
            case 4:
                $this->setAsContract($value);
                break;
            case 5:
                $this->setAsReport($value);
                break;
            case 6:
                $this->setAsBooking($value);
                break;
            case 7:
                $this->setAsTransfer($value);
                break;
            case 8:
                $this->setContents($value);
                break;
            case 9:
                $this->setData($value);
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
        $keys = TemplatePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setSymbol($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setType($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setAsContract($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setAsReport($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setAsBooking($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setAsTransfer($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setContents($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setData($arr[$keys[9]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(TemplatePeer::DATABASE_NAME);

        if ($this->isColumnModified(TemplatePeer::ID)) $criteria->add(TemplatePeer::ID, $this->id);
        if ($this->isColumnModified(TemplatePeer::NAME)) $criteria->add(TemplatePeer::NAME, $this->name);
        if ($this->isColumnModified(TemplatePeer::SYMBOL)) $criteria->add(TemplatePeer::SYMBOL, $this->symbol);
        if ($this->isColumnModified(TemplatePeer::TYPE)) $criteria->add(TemplatePeer::TYPE, $this->type);
        if ($this->isColumnModified(TemplatePeer::AS_CONTRACT)) $criteria->add(TemplatePeer::AS_CONTRACT, $this->as_contract);
        if ($this->isColumnModified(TemplatePeer::AS_REPORT)) $criteria->add(TemplatePeer::AS_REPORT, $this->as_report);
        if ($this->isColumnModified(TemplatePeer::AS_BOOKING)) $criteria->add(TemplatePeer::AS_BOOKING, $this->as_booking);
        if ($this->isColumnModified(TemplatePeer::AS_TRANSFER)) $criteria->add(TemplatePeer::AS_TRANSFER, $this->as_transfer);
        if ($this->isColumnModified(TemplatePeer::CONTENTS)) $criteria->add(TemplatePeer::CONTENTS, $this->contents);
        if ($this->isColumnModified(TemplatePeer::DATA)) $criteria->add(TemplatePeer::DATA, $this->data);

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
        $criteria = new Criteria(TemplatePeer::DATABASE_NAME);
        $criteria->add(TemplatePeer::ID, $this->id);

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
     * @param object $copyObj An object of Template (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setSymbol($this->getSymbol());
        $copyObj->setType($this->getType());
        $copyObj->setAsContract($this->getAsContract());
        $copyObj->setAsReport($this->getAsReport());
        $copyObj->setAsBooking($this->getAsBooking());
        $copyObj->setAsTransfer($this->getAsTransfer());
        $copyObj->setContents($this->getContents());
        $copyObj->setData($this->getData());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getReports() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReport($relObj->copy($deepCopy));
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
     * @return Template Clone of current object.
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
     * @return TemplatePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new TemplatePeer();
        }

        return self::$peer;
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
        if ('Report' == $relationName) {
            $this->initReports();
        }
        if ('Contract' == $relationName) {
            $this->initContracts();
        }
    }

    /**
     * Clears out the collReports collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Template The current object (for fluent API support)
     * @see        addReports()
     */
    public function clearReports()
    {
        $this->collReports = null; // important to set this to null since that means it is uninitialized
        $this->collReportsPartial = null;

        return $this;
    }

    /**
     * reset is the collReports collection loaded partially
     *
     * @return void
     */
    public function resetPartialReports($v = true)
    {
        $this->collReportsPartial = $v;
    }

    /**
     * Initializes the collReports collection.
     *
     * By default this just sets the collReports collection to an empty array (like clearcollReports());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReports($overrideExisting = true)
    {
        if (null !== $this->collReports && !$overrideExisting) {
            return;
        }
        $this->collReports = new PropelObjectCollection();
        $this->collReports->setModel('Report');
    }

    /**
     * Gets an array of Report objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Template is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Report[] List of Report objects
     * @throws PropelException
     */
    public function getReports($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collReportsPartial && !$this->isNew();
        if (null === $this->collReports || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collReports) {
                // return empty collection
                $this->initReports();
            } else {
                $collReports = ReportQuery::create(null, $criteria)
                    ->filterByTemplate($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collReportsPartial && count($collReports)) {
                      $this->initReports(false);

                      foreach ($collReports as $obj) {
                        if (false == $this->collReports->contains($obj)) {
                          $this->collReports->append($obj);
                        }
                      }

                      $this->collReportsPartial = true;
                    }

                    $collReports->getInternalIterator()->rewind();

                    return $collReports;
                }

                if ($partial && $this->collReports) {
                    foreach ($this->collReports as $obj) {
                        if ($obj->isNew()) {
                            $collReports[] = $obj;
                        }
                    }
                }

                $this->collReports = $collReports;
                $this->collReportsPartial = false;
            }
        }

        return $this->collReports;
    }

    /**
     * Sets a collection of Report objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $reports A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Template The current object (for fluent API support)
     */
    public function setReports(PropelCollection $reports, PropelPDO $con = null)
    {
        $reportsToDelete = $this->getReports(new Criteria(), $con)->diff($reports);


        $this->reportsScheduledForDeletion = $reportsToDelete;

        foreach ($reportsToDelete as $reportRemoved) {
            $reportRemoved->setTemplate(null);
        }

        $this->collReports = null;
        foreach ($reports as $report) {
            $this->addReport($report);
        }

        $this->collReports = $reports;
        $this->collReportsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Report objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Report objects.
     * @throws PropelException
     */
    public function countReports(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collReportsPartial && !$this->isNew();
        if (null === $this->collReports || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReports) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReports());
            }
            $query = ReportQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByTemplate($this)
                ->count($con);
        }

        return count($this->collReports);
    }

    /**
     * Method called to associate a Report object to this object
     * through the Report foreign key attribute.
     *
     * @param    Report $l Report
     * @return Template The current object (for fluent API support)
     */
    public function addReport(Report $l)
    {
        if ($this->collReports === null) {
            $this->initReports();
            $this->collReportsPartial = true;
        }

        if (!in_array($l, $this->collReports->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddReport($l);

            if ($this->reportsScheduledForDeletion and $this->reportsScheduledForDeletion->contains($l)) {
                $this->reportsScheduledForDeletion->remove($this->reportsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Report $report The report object to add.
     */
    protected function doAddReport($report)
    {
        $this->collReports[]= $report;
        $report->setTemplate($this);
    }

    /**
     * @param	Report $report The report object to remove.
     * @return Template The current object (for fluent API support)
     */
    public function removeReport($report)
    {
        if ($this->getReports()->contains($report)) {
            $this->collReports->remove($this->collReports->search($report));
            if (null === $this->reportsScheduledForDeletion) {
                $this->reportsScheduledForDeletion = clone $this->collReports;
                $this->reportsScheduledForDeletion->clear();
            }
            $this->reportsScheduledForDeletion[]= $report;
            $report->setTemplate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Template is new, it will return
     * an empty collection; or if this Template has previously
     * been saved, it will retrieve related Reports from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Template.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Report[] List of Report objects
     */
    public function getReportsJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ReportQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getReports($query, $con);
    }

    /**
     * Clears out the collContracts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Template The current object (for fluent API support)
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
     * If this Template is new, it will return
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
                    ->filterByTemplate($this)
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
     * @return Template The current object (for fluent API support)
     */
    public function setContracts(PropelCollection $contracts, PropelPDO $con = null)
    {
        $contractsToDelete = $this->getContracts(new Criteria(), $con)->diff($contracts);


        $this->contractsScheduledForDeletion = $contractsToDelete;

        foreach ($contractsToDelete as $contractRemoved) {
            $contractRemoved->setTemplate(null);
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
                ->filterByTemplate($this)
                ->count($con);
        }

        return count($this->collContracts);
    }

    /**
     * Method called to associate a Contract object to this object
     * through the Contract foreign key attribute.
     *
     * @param    Contract $l Contract
     * @return Template The current object (for fluent API support)
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
        $contract->setTemplate($this);
    }

    /**
     * @param	Contract $contract The contract object to remove.
     * @return Template The current object (for fluent API support)
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
            $contract->setTemplate(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Template is new, it will return
     * an empty collection; or if this Template has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Template.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Contract[] List of Contract objects
     */
    public function getContractsJoinCost($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ContractQuery::create(null, $criteria);
        $query->joinWith('Cost', $join_behavior);

        return $this->getContracts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Template is new, it will return
     * an empty collection; or if this Template has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Template.
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
     * Otherwise if this Template is new, it will return
     * an empty collection; or if this Template has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Template.
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
     * Otherwise if this Template is new, it will return
     * an empty collection; or if this Template has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Template.
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
        $this->symbol = null;
        $this->type = null;
        $this->as_contract = null;
        $this->as_report = null;
        $this->as_booking = null;
        $this->as_transfer = null;
        $this->contents = null;
        $this->data = null;
        $this->alreadyInSave = false;
        $this->alreadyInValidation = false;
        $this->alreadyInClearAllReferencesDeep = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
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
            if ($this->collReports) {
                foreach ($this->collReports as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContracts) {
                foreach ($this->collContracts as $o) {
                    $o->clearAllReferences($deep);
                }
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collReports instanceof PropelCollection) {
            $this->collReports->clearIterator();
        }
        $this->collReports = null;
        if ($this->collContracts instanceof PropelCollection) {
            $this->collContracts->clearIterator();
        }
        $this->collContracts = null;
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

    // archivable behavior

    /**
     * Get an archived version of the current object.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     TemplateArchive An archive object, or null if the current object was never archived
     */
    public function getArchive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            return null;
        }
        $archive = TemplateArchiveQuery::create()
            ->filterByPrimaryKey($this->getPrimaryKey())
            ->findOne($con);

        return $archive;
    }
    /**
     * Copy the data of the current object into a $archiveTablePhpName archive object.
     * The archived object is then saved.
     * If the current object has already been archived, the archived object
     * is updated and not duplicated.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object is new
     *
     * @return     TemplateArchive The archive object based on this object
     */
    public function archive(PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be archived. You must save the current object before calling archive().');
        }
        if (!$archive = $this->getArchive($con)) {
            $archive = new TemplateArchive();
            $archive->setPrimaryKey($this->getPrimaryKey());
        }
        $this->copyInto($archive, $deepCopy = false, $makeNew = false);
        $archive->setArchivedAt(time());
        $archive->save($con);

        return $archive;
    }

    /**
     * Revert the the current object to the state it had when it was last archived.
     * The object must be saved afterwards if the changes must persist.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @throws PropelException If the object has no corresponding archive.
     *
     * @return Template The current object (for fluent API support)
     */
    public function restoreFromArchive(PropelPDO $con = null)
    {
        if (!$archive = $this->getArchive($con)) {
            throw new PropelException('The current object has never been archived and cannot be restored');
        }
        $this->populateFromArchive($archive);

        return $this;
    }

    /**
     * Populates the the current object based on a $archiveTablePhpName archive object.
     *
     * @param      TemplateArchive $archive An archived object based on the same class
      * @param      Boolean $populateAutoIncrementPrimaryKeys
     *               If true, autoincrement columns are copied from the archive object.
     *               If false, autoincrement columns are left intact.
      *
     * @return     Template The current object (for fluent API support)
     */
    public function populateFromArchive($archive, $populateAutoIncrementPrimaryKeys = false) {
        if ($populateAutoIncrementPrimaryKeys) {
            $this->setId($archive->getId());
        }
        $this->setName($archive->getName());
        $this->setSymbol($archive->getSymbol());
        $this->setType($archive->getType());
        $this->setAsContract($archive->getAsContract());
        $this->setAsReport($archive->getAsReport());
        $this->setAsBooking($archive->getAsBooking());
        $this->setAsTransfer($archive->getAsTransfer());
        $this->setContents($archive->getContents());
        $this->setData($archive->getData());

        return $this;
    }

    /**
     * Removes the object from the database without archiving it.
     *
     * @param PropelPDO $con Optional connection object
     *
     * @return     Template The current object (for fluent API support)
     */
    public function deleteWithoutArchive(PropelPDO $con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

}
