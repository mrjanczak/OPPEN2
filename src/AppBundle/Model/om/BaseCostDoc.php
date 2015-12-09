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
use AppBundle\Model\Cost;
use AppBundle\Model\CostDoc;
use AppBundle\Model\CostDocIncome;
use AppBundle\Model\CostDocIncomeQuery;
use AppBundle\Model\CostDocPeer;
use AppBundle\Model\CostDocQuery;
use AppBundle\Model\CostQuery;
use AppBundle\Model\Doc;
use AppBundle\Model\DocQuery;

abstract class BaseCostDoc extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'AppBundle\\Model\\CostDocPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        CostDocPeer
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
     * The value for the value field.
     * @var        double
     */
    protected $value;

    /**
     * The value for the desc field.
     * @var        string
     */
    protected $desc;

    /**
     * The value for the cost_id field.
     * @var        int
     */
    protected $cost_id;

    /**
     * The value for the doc_id field.
     * @var        int
     */
    protected $doc_id;

    /**
     * @var        Cost
     */
    protected $aCost;

    /**
     * @var        Doc
     */
    protected $aDoc;

    /**
     * @var        PropelObjectCollection|CostDocIncome[] Collection to store aggregation of CostDocIncome objects.
     */
    protected $collCostDocIncomes;
    protected $collCostDocIncomesPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costDocIncomesScheduledForDeletion = null;

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
     * Get the [value] column value.
     *
     * @return double
     */
    public function getValue()
    {

        return $this->value;
    }

    /**
     * Get the [desc] column value.
     *
     * @return string
     */
    public function getDesc()
    {

        return $this->desc;
    }

    /**
     * Get the [cost_id] column value.
     *
     * @return int
     */
    public function getCostId()
    {

        return $this->cost_id;
    }

    /**
     * Get the [doc_id] column value.
     *
     * @return int
     */
    public function getDocId()
    {

        return $this->doc_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return CostDoc The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = CostDocPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [value] column.
     *
     * @param  double $v new value
     * @return CostDoc The current object (for fluent API support)
     */
    public function setValue($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (double) $v;
        }

        if ($this->value !== $v) {
            $this->value = $v;
            $this->modifiedColumns[] = CostDocPeer::VALUE;
        }


        return $this;
    } // setValue()

    /**
     * Set the value of [desc] column.
     *
     * @param  string $v new value
     * @return CostDoc The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->desc !== $v) {
            $this->desc = $v;
            $this->modifiedColumns[] = CostDocPeer::DESC;
        }


        return $this;
    } // setDesc()

    /**
     * Set the value of [cost_id] column.
     *
     * @param  int $v new value
     * @return CostDoc The current object (for fluent API support)
     */
    public function setCostId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->cost_id !== $v) {
            $this->cost_id = $v;
            $this->modifiedColumns[] = CostDocPeer::COST_ID;
        }

        if ($this->aCost !== null && $this->aCost->getId() !== $v) {
            $this->aCost = null;
        }


        return $this;
    } // setCostId()

    /**
     * Set the value of [doc_id] column.
     *
     * @param  int $v new value
     * @return CostDoc The current object (for fluent API support)
     */
    public function setDocId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->doc_id !== $v) {
            $this->doc_id = $v;
            $this->modifiedColumns[] = CostDocPeer::DOC_ID;
        }

        if ($this->aDoc !== null && $this->aDoc->getId() !== $v) {
            $this->aDoc = null;
        }


        return $this;
    } // setDocId()

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
            $this->value = ($row[$startcol + 1] !== null) ? (double) $row[$startcol + 1] : null;
            $this->desc = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->cost_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->doc_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 5; // 5 = CostDocPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating CostDoc object", $e);
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

        if ($this->aCost !== null && $this->cost_id !== $this->aCost->getId()) {
            $this->aCost = null;
        }
        if ($this->aDoc !== null && $this->doc_id !== $this->aDoc->getId()) {
            $this->aDoc = null;
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
            $con = Propel::getConnection(CostDocPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = CostDocPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCost = null;
            $this->aDoc = null;
            $this->collCostDocIncomes = null;

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
            $con = Propel::getConnection(CostDocPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = CostDocQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
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
            $con = Propel::getConnection(CostDocPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                CostDocPeer::addInstanceToPool($this);
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

            if ($this->aCost !== null) {
                if ($this->aCost->isModified() || $this->aCost->isNew()) {
                    $affectedRows += $this->aCost->save($con);
                }
                $this->setCost($this->aCost);
            }

            if ($this->aDoc !== null) {
                if ($this->aDoc->isModified() || $this->aDoc->isNew()) {
                    $affectedRows += $this->aDoc->save($con);
                }
                $this->setDoc($this->aDoc);
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

            if ($this->costDocIncomesScheduledForDeletion !== null) {
                if (!$this->costDocIncomesScheduledForDeletion->isEmpty()) {
                    CostDocIncomeQuery::create()
                        ->filterByPrimaryKeys($this->costDocIncomesScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->costDocIncomesScheduledForDeletion = null;
                }
            }

            if ($this->collCostDocIncomes !== null) {
                foreach ($this->collCostDocIncomes as $referrerFK) {
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

        $this->modifiedColumns[] = CostDocPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CostDocPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CostDocPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(CostDocPeer::VALUE)) {
            $modifiedColumns[':p' . $index++]  = '`value`';
        }
        if ($this->isColumnModified(CostDocPeer::DESC)) {
            $modifiedColumns[':p' . $index++]  = '`desc`';
        }
        if ($this->isColumnModified(CostDocPeer::COST_ID)) {
            $modifiedColumns[':p' . $index++]  = '`cost_id`';
        }
        if ($this->isColumnModified(CostDocPeer::DOC_ID)) {
            $modifiedColumns[':p' . $index++]  = '`doc_id`';
        }

        $sql = sprintf(
            'INSERT INTO `cost_doc` (%s) VALUES (%s)',
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
                    case '`value`':
                        $stmt->bindValue($identifier, $this->value, PDO::PARAM_STR);
                        break;
                    case '`desc`':
                        $stmt->bindValue($identifier, $this->desc, PDO::PARAM_STR);
                        break;
                    case '`cost_id`':
                        $stmt->bindValue($identifier, $this->cost_id, PDO::PARAM_INT);
                        break;
                    case '`doc_id`':
                        $stmt->bindValue($identifier, $this->doc_id, PDO::PARAM_INT);
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

            if ($this->aCost !== null) {
                if (!$this->aCost->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aCost->getValidationFailures());
                }
            }

            if ($this->aDoc !== null) {
                if (!$this->aDoc->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDoc->getValidationFailures());
                }
            }


            if (($retval = CostDocPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collCostDocIncomes !== null) {
                    foreach ($this->collCostDocIncomes as $referrerFK) {
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
        $pos = CostDocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getValue();
                break;
            case 2:
                return $this->getDesc();
                break;
            case 3:
                return $this->getCostId();
                break;
            case 4:
                return $this->getDocId();
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
        if (isset($alreadyDumpedObjects['CostDoc'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['CostDoc'][$this->getPrimaryKey()] = true;
        $keys = CostDocPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getValue(),
            $keys[2] => $this->getDesc(),
            $keys[3] => $this->getCostId(),
            $keys[4] => $this->getDocId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCost) {
                $result['Cost'] = $this->aCost->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDoc) {
                $result['Doc'] = $this->aDoc->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collCostDocIncomes) {
                $result['CostDocIncomes'] = $this->collCostDocIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = CostDocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setValue($value);
                break;
            case 2:
                $this->setDesc($value);
                break;
            case 3:
                $this->setCostId($value);
                break;
            case 4:
                $this->setDocId($value);
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
        $keys = CostDocPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setValue($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setDesc($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setCostId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setDocId($arr[$keys[4]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(CostDocPeer::DATABASE_NAME);

        if ($this->isColumnModified(CostDocPeer::ID)) $criteria->add(CostDocPeer::ID, $this->id);
        if ($this->isColumnModified(CostDocPeer::VALUE)) $criteria->add(CostDocPeer::VALUE, $this->value);
        if ($this->isColumnModified(CostDocPeer::DESC)) $criteria->add(CostDocPeer::DESC, $this->desc);
        if ($this->isColumnModified(CostDocPeer::COST_ID)) $criteria->add(CostDocPeer::COST_ID, $this->cost_id);
        if ($this->isColumnModified(CostDocPeer::DOC_ID)) $criteria->add(CostDocPeer::DOC_ID, $this->doc_id);

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
        $criteria = new Criteria(CostDocPeer::DATABASE_NAME);
        $criteria->add(CostDocPeer::ID, $this->id);

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
     * @param object $copyObj An object of CostDoc (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setValue($this->getValue());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setCostId($this->getCostId());
        $copyObj->setDocId($this->getDocId());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getCostDocIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCostDocIncome($relObj->copy($deepCopy));
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
     * @return CostDoc Clone of current object.
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
     * @return CostDocPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new CostDocPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Cost object.
     *
     * @param                  Cost $v
     * @return CostDoc The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCost(Cost $v = null)
    {
        if ($v === null) {
            $this->setCostId(NULL);
        } else {
            $this->setCostId($v->getId());
        }

        $this->aCost = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Cost object, it will not be re-added.
        if ($v !== null) {
            $v->addCostDoc($this);
        }


        return $this;
    }


    /**
     * Get the associated Cost object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Cost The associated Cost object.
     * @throws PropelException
     */
    public function getCost(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aCost === null && ($this->cost_id !== null) && $doQuery) {
            $this->aCost = CostQuery::create()->findPk($this->cost_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCost->addCostDocs($this);
             */
        }

        return $this->aCost;
    }

    /**
     * Declares an association between this object and a Doc object.
     *
     * @param                  Doc $v
     * @return CostDoc The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDoc(Doc $v = null)
    {
        if ($v === null) {
            $this->setDocId(NULL);
        } else {
            $this->setDocId($v->getId());
        }

        $this->aDoc = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Doc object, it will not be re-added.
        if ($v !== null) {
            $v->addCostDoc($this);
        }


        return $this;
    }


    /**
     * Get the associated Doc object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Doc The associated Doc object.
     * @throws PropelException
     */
    public function getDoc(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDoc === null && ($this->doc_id !== null) && $doQuery) {
            $this->aDoc = DocQuery::create()->findPk($this->doc_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDoc->addCostDocs($this);
             */
        }

        return $this->aDoc;
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
        if ('CostDocIncome' == $relationName) {
            $this->initCostDocIncomes();
        }
    }

    /**
     * Clears out the collCostDocIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return CostDoc The current object (for fluent API support)
     * @see        addCostDocIncomes()
     */
    public function clearCostDocIncomes()
    {
        $this->collCostDocIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collCostDocIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collCostDocIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialCostDocIncomes($v = true)
    {
        $this->collCostDocIncomesPartial = $v;
    }

    /**
     * Initializes the collCostDocIncomes collection.
     *
     * By default this just sets the collCostDocIncomes collection to an empty array (like clearcollCostDocIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCostDocIncomes($overrideExisting = true)
    {
        if (null !== $this->collCostDocIncomes && !$overrideExisting) {
            return;
        }
        $this->collCostDocIncomes = new PropelObjectCollection();
        $this->collCostDocIncomes->setModel('CostDocIncome');
    }

    /**
     * Gets an array of CostDocIncome objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this CostDoc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|CostDocIncome[] List of CostDocIncome objects
     * @throws PropelException
     */
    public function getCostDocIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostDocIncomesPartial && !$this->isNew();
        if (null === $this->collCostDocIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCostDocIncomes) {
                // return empty collection
                $this->initCostDocIncomes();
            } else {
                $collCostDocIncomes = CostDocIncomeQuery::create(null, $criteria)
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

    /**
     * Sets a collection of CostDocIncome objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costDocIncomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return CostDoc The current object (for fluent API support)
     */
    public function setCostDocIncomes(PropelCollection $costDocIncomes, PropelPDO $con = null)
    {
        $costDocIncomesToDelete = $this->getCostDocIncomes(new Criteria(), $con)->diff($costDocIncomes);


        $this->costDocIncomesScheduledForDeletion = $costDocIncomesToDelete;

        foreach ($costDocIncomesToDelete as $costDocIncomeRemoved) {
            $costDocIncomeRemoved->setCostDoc(null);
        }

        $this->collCostDocIncomes = null;
        foreach ($costDocIncomes as $costDocIncome) {
            $this->addCostDocIncome($costDocIncome);
        }

        $this->collCostDocIncomes = $costDocIncomes;
        $this->collCostDocIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related CostDocIncome objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related CostDocIncome objects.
     * @throws PropelException
     */
    public function countCostDocIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostDocIncomesPartial && !$this->isNew();
        if (null === $this->collCostDocIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCostDocIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCostDocIncomes());
            }
            $query = CostDocIncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByCostDoc($this)
                ->count($con);
        }

        return count($this->collCostDocIncomes);
    }

    /**
     * Method called to associate a CostDocIncome object to this object
     * through the CostDocIncome foreign key attribute.
     *
     * @param    CostDocIncome $l CostDocIncome
     * @return CostDoc The current object (for fluent API support)
     */
    public function addCostDocIncome(CostDocIncome $l)
    {
        if ($this->collCostDocIncomes === null) {
            $this->initCostDocIncomes();
            $this->collCostDocIncomesPartial = true;
        }

        if (!in_array($l, $this->collCostDocIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCostDocIncome($l);

            if ($this->costDocIncomesScheduledForDeletion and $this->costDocIncomesScheduledForDeletion->contains($l)) {
                $this->costDocIncomesScheduledForDeletion->remove($this->costDocIncomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	CostDocIncome $costDocIncome The costDocIncome object to add.
     */
    protected function doAddCostDocIncome($costDocIncome)
    {
        $this->collCostDocIncomes[]= $costDocIncome;
        $costDocIncome->setCostDoc($this);
    }

    /**
     * @param	CostDocIncome $costDocIncome The costDocIncome object to remove.
     * @return CostDoc The current object (for fluent API support)
     */
    public function removeCostDocIncome($costDocIncome)
    {
        if ($this->getCostDocIncomes()->contains($costDocIncome)) {
            $this->collCostDocIncomes->remove($this->collCostDocIncomes->search($costDocIncome));
            if (null === $this->costDocIncomesScheduledForDeletion) {
                $this->costDocIncomesScheduledForDeletion = clone $this->collCostDocIncomes;
                $this->costDocIncomesScheduledForDeletion->clear();
            }
            $this->costDocIncomesScheduledForDeletion[]= $costDocIncome;
            $costDocIncome->setCostDoc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this CostDoc is new, it will return
     * an empty collection; or if this CostDoc has previously
     * been saved, it will retrieve related CostDocIncomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in CostDoc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostDocIncome[] List of CostDocIncome objects
     */
    public function getCostDocIncomesJoinIncome($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostDocIncomeQuery::create(null, $criteria);
        $query->joinWith('Income', $join_behavior);

        return $this->getCostDocIncomes($query, $con);
    }

    /**
     * Clears the current object and sets all attributes to their default values
     */
    public function clear()
    {
        $this->id = null;
        $this->value = null;
        $this->desc = null;
        $this->cost_id = null;
        $this->doc_id = null;
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
            if ($this->collCostDocIncomes) {
                foreach ($this->collCostDocIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aCost instanceof Persistent) {
              $this->aCost->clearAllReferences($deep);
            }
            if ($this->aDoc instanceof Persistent) {
              $this->aDoc->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collCostDocIncomes instanceof PropelCollection) {
            $this->collCostDocIncomes->clearIterator();
        }
        $this->collCostDocIncomes = null;
        $this->aCost = null;
        $this->aDoc = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CostDocPeer::DEFAULT_STRING_FORMAT);
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

}
