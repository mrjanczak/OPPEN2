<?php

namespace AppBundle\Model\om;

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
use AppBundle\Model\Report;
use AppBundle\Model\ReportEntry;
use AppBundle\Model\ReportEntryQuery;
use AppBundle\Model\ReportPeer;
use AppBundle\Model\ReportQuery;
use AppBundle\Model\Template;
use AppBundle\Model\TemplateQuery;
use AppBundle\Model\Year;
use AppBundle\Model\YearQuery;

abstract class BaseReport extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\ReportPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        ReportPeer
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
     * The value for the shortname field.
     * @var        string
     */
    protected $shortname;

    /**
     * The value for the is_locked field.
     * Note: this column has a database default value of: false
     * @var        boolean
     */
    protected $is_locked;

    /**
     * The value for the year_id field.
     * @var        int
     */
    protected $year_id;

    /**
     * The value for the template_id field.
     * @var        int
     */
    protected $template_id;

    /**
     * The value for the sortable_rank field.
     * @var        int
     */
    protected $sortable_rank;

    /**
     * @var        Year
     */
    protected $aYear;

    /**
     * @var        Template
     */
    protected $aTemplate;

    /**
     * @var        PropelObjectCollection|ReportEntry[] Collection to store aggregation of ReportEntry objects.
     */
    protected $collReportEntries;
    protected $collReportEntriesPartial;

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
    protected $reportEntriesScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see        __construct()
     */
    public function applyDefaultValues()
    {
        $this->is_locked = false;
    }

    /**
     * Initializes internal state of BaseReport object.
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
     * Get the [shortname] column value.
     *
     * @return string
     */
    public function getShortname()
    {

        return $this->shortname;
    }

    /**
     * Get the [is_locked] column value.
     *
     * @return boolean
     */
    public function getIsLocked()
    {

        return $this->is_locked;
    }

    /**
     * Get the [year_id] column value.
     *
     * @return int
     */
    public function getYearId()
    {

        return $this->year_id;
    }

    /**
     * Get the [template_id] column value.
     *
     * @return int
     */
    public function getTemplateId()
    {

        return $this->template_id;
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
     * @return Report The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = ReportPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return Report The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = ReportPeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [shortname] column.
     *
     * @param  string $v new value
     * @return Report The current object (for fluent API support)
     */
    public function setShortname($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->shortname !== $v) {
            $this->shortname = $v;
            $this->modifiedColumns[] = ReportPeer::SHORTNAME;
        }


        return $this;
    } // setShortname()

    /**
     * Sets the value of the [is_locked] column.
     * Non-boolean arguments are converted using the following rules:
     *   * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *   * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     * Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     *
     * @param boolean|integer|string $v The new value
     * @return Report The current object (for fluent API support)
     */
    public function setIsLocked($v)
    {
        if ($v !== null) {
            if (is_string($v)) {
                $v = in_array(strtolower($v), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
            } else {
                $v = (boolean) $v;
            }
        }

        if ($this->is_locked !== $v) {
            $this->is_locked = $v;
            $this->modifiedColumns[] = ReportPeer::IS_LOCKED;
        }


        return $this;
    } // setIsLocked()

    /**
     * Set the value of [year_id] column.
     *
     * @param  int $v new value
     * @return Report The current object (for fluent API support)
     */
    public function setYearId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->year_id !== $v) {
            // sortable behavior
            $this->oldScope = $this->year_id;

            $this->year_id = $v;
            $this->modifiedColumns[] = ReportPeer::YEAR_ID;
        }

        if ($this->aYear !== null && $this->aYear->getId() !== $v) {
            $this->aYear = null;
        }


        return $this;
    } // setYearId()

    /**
     * Set the value of [template_id] column.
     *
     * @param  int $v new value
     * @return Report The current object (for fluent API support)
     */
    public function setTemplateId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->template_id !== $v) {
            $this->template_id = $v;
            $this->modifiedColumns[] = ReportPeer::TEMPLATE_ID;
        }

        if ($this->aTemplate !== null && $this->aTemplate->getId() !== $v) {
            $this->aTemplate = null;
        }


        return $this;
    } // setTemplateId()

    /**
     * Set the value of [sortable_rank] column.
     *
     * @param  int $v new value
     * @return Report The current object (for fluent API support)
     */
    public function setSortableRank($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sortable_rank !== $v) {
            $this->sortable_rank = $v;
            $this->modifiedColumns[] = ReportPeer::SORTABLE_RANK;
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
            if ($this->is_locked !== false) {
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
            $this->shortname = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->is_locked = ($row[$startcol + 3] !== null) ? (boolean) $row[$startcol + 3] : null;
            $this->year_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->template_id = ($row[$startcol + 5] !== null) ? (int) $row[$startcol + 5] : null;
            $this->sortable_rank = ($row[$startcol + 6] !== null) ? (int) $row[$startcol + 6] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 7; // 7 = ReportPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Report object", $e);
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

        if ($this->aYear !== null && $this->year_id !== $this->aYear->getId()) {
            $this->aYear = null;
        }
        if ($this->aTemplate !== null && $this->template_id !== $this->aTemplate->getId()) {
            $this->aTemplate = null;
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
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = ReportPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aYear = null;
            $this->aTemplate = null;
            $this->collReportEntries = null;

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
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = ReportQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            // sortable behavior

            ReportPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->getScopeValue(), $con);
            ReportPeer::clearInstancePool();

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
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                if (!$this->isColumnModified(ReportPeer::RANK_COL)) {
                    $this->setSortableRank(ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);
                }

            } else {
                $ret = $ret && $this->preUpdate($con);
                // sortable behavior
                // if scope has changed and rank was not modified (if yes, assuming superior action)
                // insert object to the end of new scope and cleanup old one
                if (($this->isColumnModified(ReportPeer::YEAR_ID)) && !$this->isColumnModified(ReportPeer::RANK_COL)) { ReportPeer::shiftRank(-1, $this->getSortableRank() + 1, null, $this->oldScope, $con);
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
                ReportPeer::addInstanceToPool($this);
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

            if ($this->aYear !== null) {
                if ($this->aYear->isModified() || $this->aYear->isNew()) {
                    $affectedRows += $this->aYear->save($con);
                }
                $this->setYear($this->aYear);
            }

            if ($this->aTemplate !== null) {
                if ($this->aTemplate->isModified() || $this->aTemplate->isNew()) {
                    $affectedRows += $this->aTemplate->save($con);
                }
                $this->setTemplate($this->aTemplate);
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

            if ($this->reportEntriesScheduledForDeletion !== null) {
                if (!$this->reportEntriesScheduledForDeletion->isEmpty()) {
                    ReportEntryQuery::create()
                        ->filterByPrimaryKeys($this->reportEntriesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->reportEntriesScheduledForDeletion = null;
                }
            }

            if ($this->collReportEntries !== null) {
                foreach ($this->collReportEntries as $referrerFK) {
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

        $this->modifiedColumns[] = ReportPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . ReportPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(ReportPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(ReportPeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(ReportPeer::SHORTNAME)) {
            $modifiedColumns[':p' . $index++]  = '`shortname`';
        }
        if ($this->isColumnModified(ReportPeer::IS_LOCKED)) {
            $modifiedColumns[':p' . $index++]  = '`is_locked`';
        }
        if ($this->isColumnModified(ReportPeer::YEAR_ID)) {
            $modifiedColumns[':p' . $index++]  = '`year_id`';
        }
        if ($this->isColumnModified(ReportPeer::TEMPLATE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`template_id`';
        }
        if ($this->isColumnModified(ReportPeer::SORTABLE_RANK)) {
            $modifiedColumns[':p' . $index++]  = '`sortable_rank`';
        }

        $sql = sprintf(
            'INSERT INTO `report` (%s) VALUES (%s)',
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
                    case '`shortname`':
                        $stmt->bindValue($identifier, $this->shortname, PDO::PARAM_STR);
                        break;
                    case '`is_locked`':
                        $stmt->bindValue($identifier, (int) $this->is_locked, PDO::PARAM_INT);
                        break;
                    case '`year_id`':
                        $stmt->bindValue($identifier, $this->year_id, PDO::PARAM_INT);
                        break;
                    case '`template_id`':
                        $stmt->bindValue($identifier, $this->template_id, PDO::PARAM_INT);
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

            if ($this->aYear !== null) {
                if (!$this->aYear->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aYear->getValidationFailures());
                }
            }

            if ($this->aTemplate !== null) {
                if (!$this->aTemplate->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aTemplate->getValidationFailures());
                }
            }


            if (($retval = ReportPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collReportEntries !== null) {
                    foreach ($this->collReportEntries as $referrerFK) {
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
        $pos = ReportPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getShortname();
                break;
            case 3:
                return $this->getIsLocked();
                break;
            case 4:
                return $this->getYearId();
                break;
            case 5:
                return $this->getTemplateId();
                break;
            case 6:
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
        if (isset($alreadyDumpedObjects['Report'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Report'][$this->getPrimaryKey()] = true;
        $keys = ReportPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getShortname(),
            $keys[3] => $this->getIsLocked(),
            $keys[4] => $this->getYearId(),
            $keys[5] => $this->getTemplateId(),
            $keys[6] => $this->getSortableRank(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aYear) {
                $result['Year'] = $this->aYear->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aTemplate) {
                $result['Template'] = $this->aTemplate->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collReportEntries) {
                $result['ReportEntries'] = $this->collReportEntries->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = ReportPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setShortname($value);
                break;
            case 3:
                $this->setIsLocked($value);
                break;
            case 4:
                $this->setYearId($value);
                break;
            case 5:
                $this->setTemplateId($value);
                break;
            case 6:
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
        $keys = ReportPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setShortname($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setIsLocked($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setYearId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setTemplateId($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSortableRank($arr[$keys[6]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(ReportPeer::DATABASE_NAME);

        if ($this->isColumnModified(ReportPeer::ID)) $criteria->add(ReportPeer::ID, $this->id);
        if ($this->isColumnModified(ReportPeer::NAME)) $criteria->add(ReportPeer::NAME, $this->name);
        if ($this->isColumnModified(ReportPeer::SHORTNAME)) $criteria->add(ReportPeer::SHORTNAME, $this->shortname);
        if ($this->isColumnModified(ReportPeer::IS_LOCKED)) $criteria->add(ReportPeer::IS_LOCKED, $this->is_locked);
        if ($this->isColumnModified(ReportPeer::YEAR_ID)) $criteria->add(ReportPeer::YEAR_ID, $this->year_id);
        if ($this->isColumnModified(ReportPeer::TEMPLATE_ID)) $criteria->add(ReportPeer::TEMPLATE_ID, $this->template_id);
        if ($this->isColumnModified(ReportPeer::SORTABLE_RANK)) $criteria->add(ReportPeer::SORTABLE_RANK, $this->sortable_rank);

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
        $criteria = new Criteria(ReportPeer::DATABASE_NAME);
        $criteria->add(ReportPeer::ID, $this->id);

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
     * @param object $copyObj An object of Report (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setShortname($this->getShortname());
        $copyObj->setIsLocked($this->getIsLocked());
        $copyObj->setYearId($this->getYearId());
        $copyObj->setTemplateId($this->getTemplateId());
        $copyObj->setSortableRank($this->getSortableRank());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getReportEntries() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addReportEntry($relObj->copy($deepCopy));
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
     * @return Report Clone of current object.
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
     * @return ReportPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new ReportPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Year object.
     *
     * @param                  Year $v
     * @return Report The current object (for fluent API support)
     * @throws PropelException
     */
    public function setYear(Year $v = null)
    {
        if ($v === null) {
            $this->setYearId(NULL);
        } else {
            $this->setYearId($v->getId());
        }

        $this->aYear = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Year object, it will not be re-added.
        if ($v !== null) {
            $v->addReport($this);
        }


        return $this;
    }


    /**
     * Get the associated Year object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Year The associated Year object.
     * @throws PropelException
     */
    public function getYear(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aYear === null && ($this->year_id !== null) && $doQuery) {
            $this->aYear = YearQuery::create()->findPk($this->year_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aYear->addReports($this);
             */
        }

        return $this->aYear;
    }

    /**
     * Declares an association between this object and a Template object.
     *
     * @param                  Template $v
     * @return Report The current object (for fluent API support)
     * @throws PropelException
     */
    public function setTemplate(Template $v = null)
    {
        if ($v === null) {
            $this->setTemplateId(NULL);
        } else {
            $this->setTemplateId($v->getId());
        }

        $this->aTemplate = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Template object, it will not be re-added.
        if ($v !== null) {
            $v->addReport($this);
        }


        return $this;
    }


    /**
     * Get the associated Template object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Template The associated Template object.
     * @throws PropelException
     */
    public function getTemplate(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aTemplate === null && ($this->template_id !== null) && $doQuery) {
            $this->aTemplate = TemplateQuery::create()->findPk($this->template_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aTemplate->addReports($this);
             */
        }

        return $this->aTemplate;
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
        if ('ReportEntry' == $relationName) {
            $this->initReportEntries();
        }
    }

    /**
     * Clears out the collReportEntries collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Report The current object (for fluent API support)
     * @see        addReportEntries()
     */
    public function clearReportEntries()
    {
        $this->collReportEntries = null; // important to set this to null since that means it is uninitialized
        $this->collReportEntriesPartial = null;

        return $this;
    }

    /**
     * reset is the collReportEntries collection loaded partially
     *
     * @return void
     */
    public function resetPartialReportEntries($v = true)
    {
        $this->collReportEntriesPartial = $v;
    }

    /**
     * Initializes the collReportEntries collection.
     *
     * By default this just sets the collReportEntries collection to an empty array (like clearcollReportEntries());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initReportEntries($overrideExisting = true)
    {
        if (null !== $this->collReportEntries && !$overrideExisting) {
            return;
        }
        $this->collReportEntries = new PropelObjectCollection();
        $this->collReportEntries->setModel('ReportEntry');
    }

    /**
     * Gets an array of ReportEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Report is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|ReportEntry[] List of ReportEntry objects
     * @throws PropelException
     */
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

    /**
     * Sets a collection of ReportEntry objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $reportEntries A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Report The current object (for fluent API support)
     */
    public function setReportEntries(PropelCollection $reportEntries, PropelPDO $con = null)
    {
        $reportEntriesToDelete = $this->getReportEntries(new Criteria(), $con)->diff($reportEntries);


        $this->reportEntriesScheduledForDeletion = $reportEntriesToDelete;

        foreach ($reportEntriesToDelete as $reportEntryRemoved) {
            $reportEntryRemoved->setReport(null);
        }

        $this->collReportEntries = null;
        foreach ($reportEntries as $reportEntry) {
            $this->addReportEntry($reportEntry);
        }

        $this->collReportEntries = $reportEntries;
        $this->collReportEntriesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related ReportEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related ReportEntry objects.
     * @throws PropelException
     */
    public function countReportEntries(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collReportEntriesPartial && !$this->isNew();
        if (null === $this->collReportEntries || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collReportEntries) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getReportEntries());
            }
            $query = ReportEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByReport($this)
                ->count($con);
        }

        return count($this->collReportEntries);
    }

    /**
     * Method called to associate a ReportEntry object to this object
     * through the ReportEntry foreign key attribute.
     *
     * @param    ReportEntry $l ReportEntry
     * @return Report The current object (for fluent API support)
     */
    public function addReportEntry(ReportEntry $l)
    {
        if ($this->collReportEntries === null) {
            $this->initReportEntries();
            $this->collReportEntriesPartial = true;
        }

        if (!in_array($l, $this->collReportEntries->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddReportEntry($l);

            if ($this->reportEntriesScheduledForDeletion and $this->reportEntriesScheduledForDeletion->contains($l)) {
                $this->reportEntriesScheduledForDeletion->remove($this->reportEntriesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	ReportEntry $reportEntry The reportEntry object to add.
     */
    protected function doAddReportEntry($reportEntry)
    {
        $this->collReportEntries[]= $reportEntry;
        $reportEntry->setReport($this);
    }

    /**
     * @param	ReportEntry $reportEntry The reportEntry object to remove.
     * @return Report The current object (for fluent API support)
     */
    public function removeReportEntry($reportEntry)
    {
        if ($this->getReportEntries()->contains($reportEntry)) {
            $this->collReportEntries->remove($this->collReportEntries->search($reportEntry));
            if (null === $this->reportEntriesScheduledForDeletion) {
                $this->reportEntriesScheduledForDeletion = clone $this->collReportEntries;
                $this->reportEntriesScheduledForDeletion->clear();
            }
            $this->reportEntriesScheduledForDeletion[]= $reportEntry;
            $reportEntry->setReport(null);
        }

        return $this;
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->name = null;
        $this->shortname = null;
        $this->is_locked = null;
        $this->year_id = null;
        $this->template_id = null;
        $this->sortable_rank = null;
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
            if ($this->collReportEntries) {
                foreach ($this->collReportEntries as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aYear instanceof Persistent) {
              $this->aYear->clearAllReferences($deep);
            }
            if ($this->aTemplate instanceof Persistent) {
              $this->aTemplate->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collReportEntries instanceof PropelCollection) {
            $this->collReportEntries->clearIterator();
        }
        $this->collReportEntries = null;
        $this->aYear = null;
        $this->aTemplate = null;
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
     * @return    Report
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


        return $this->getYearId();

    }

    /**
     * Wrap the setter for scope value
     *
     * @param     mixed A array or a native type
     * @return    Report
     */
    public function setScopeValue($v)
    {


        return $this->setYearId($v);

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
        return $this->getSortableRank() == ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
    }

    /**
     * Get the next item in the list, i.e. the one for which rank is immediately higher
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Report
     */
    public function getNext(PropelPDO $con = null)
    {

        $query = ReportQuery::create();

        $scope = $this->getScopeValue();

        $query->filterByRank($this->getSortableRank() + 1, $scope);


        return $query->findOne($con);
    }

    /**
     * Get the previous item in the list, i.e. the one for which rank is immediately lower
     *
     * @param     PropelPDO  $con      optional connection
     *
     * @return    Report
     */
    public function getPrevious(PropelPDO $con = null)
    {

        $query = ReportQuery::create();

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
     * @return    Report the current object
     *
     * @throws    PropelException
     */
    public function insertAtRank($rank, PropelPDO $con = null)
    {
        $maxRank = ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    Report the current object
     *
     * @throws    PropelException
     */
    public function insertAtBottom(PropelPDO $con = null)
    {
        $this->setSortableRank(ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con) + 1);

        return $this;
    }

    /**
     * Insert in the first rank
     * The modifications are not persisted until the object is saved.
     *
     * @return    Report the current object
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
     * @return    Report the current object
     *
     * @throws    PropelException
     */
    public function moveToRank($newRank, PropelPDO $con = null)
    {
        if ($this->isNew()) {
            throw new PropelException('New objects cannot be moved. Please use insertAtRank() instead');
        }
        if ($con === null) {
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME);
        }
        if ($newRank < 1 || $newRank > ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con)) {
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
            ReportPeer::shiftRank($delta, min($oldRank, $newRank), max($oldRank, $newRank), $this->getScopeValue(), $con);

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
     * @param     Report $object
     * @param     PropelPDO $con optional connection
     *
     * @return    Report the current object
     *
     * @throws Exception if the database cannot execute the two updates
     */
    public function swapWith($object, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME);
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
     * @return    Report the current object
     */
    public function moveUp(PropelPDO $con = null)
    {
        if ($this->isFirst()) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME);
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
     * @return    Report the current object
     */
    public function moveDown(PropelPDO $con = null)
    {
        if ($this->isLast($con)) {
            return $this;
        }
        if ($con === null) {
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME);
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
     * @return    Report the current object
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
            $con = Propel::getConnection(ReportPeer::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $bottom = ReportQuery::create()->getMaxRankArray($this->getScopeValue(), $con);
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
     * @return    Report the current object
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
