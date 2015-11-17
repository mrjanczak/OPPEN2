<?php

namespace Oppen\ProjectBundle\Model\om;

use \BaseObject;
use \BasePeer;
use \Criteria;
use \DateTime;
use \Exception;
use \PDO;
use \Persistent;
use \Propel;
use \PropelCollection;
use \PropelDateTime;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use FOS\UserBundle\Propel\User;
use FOS\UserBundle\Propel\UserQuery;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkQuery;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\CostDoc;
use Oppen\ProjectBundle\Model\CostDocQuery;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\DocCatQuery;
use Oppen\ProjectBundle\Model\DocPeer;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\IncomeDoc;
use Oppen\ProjectBundle\Model\IncomeDocQuery;
use Oppen\ProjectBundle\Model\Month;
use Oppen\ProjectBundle\Model\MonthQuery;

abstract class BaseDoc extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\DocPeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        DocPeer
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
     * The value for the document_date field.
     * @var        string
     */
    protected $document_date;

    /**
     * The value for the operation_date field.
     * @var        string
     */
    protected $operation_date;

    /**
     * The value for the receipt_date field.
     * @var        string
     */
    protected $receipt_date;

    /**
     * The value for the bookking_date field.
     * @var        string
     */
    protected $bookking_date;

    /**
     * The value for the payment_deadline_date field.
     * @var        string
     */
    protected $payment_deadline_date;

    /**
     * The value for the payment_date field.
     * @var        string
     */
    protected $payment_date;

    /**
     * The value for the payment_method field.
     * @var        int
     */
    protected $payment_method;

    /**
     * The value for the month_id field.
     * @var        int
     */
    protected $month_id;

    /**
     * The value for the doc_cat_id field.
     * @var        int
     */
    protected $doc_cat_id;

    /**
     * The value for the reg_idx field.
     * @var        int
     */
    protected $reg_idx;

    /**
     * The value for the reg_no field.
     * @var        string
     */
    protected $reg_no;

    /**
     * The value for the doc_idx field.
     * @var        int
     */
    protected $doc_idx;

    /**
     * The value for the doc_no field.
     * @var        string
     */
    protected $doc_no;

    /**
     * The value for the file_id field.
     * @var        int
     */
    protected $file_id;

    /**
     * The value for the user_id field.
     * @var        int
     */
    protected $user_id;

    /**
     * The value for the desc field.
     * @var        string
     */
    protected $desc;

    /**
     * The value for the comment field.
     * @var        string
     */
    protected $comment;

    /**
     * @var        Month
     */
    protected $aMonth;

    /**
     * @var        DocCat
     */
    protected $aDocCat;

    /**
     * @var        File
     */
    protected $aFile;

    /**
     * @var        User
     */
    protected $aUser;

    /**
     * @var        PropelObjectCollection|Bookk[] Collection to store aggregation of Bookk objects.
     */
    protected $collBookks;
    protected $collBookksPartial;

    /**
     * @var        PropelObjectCollection|IncomeDoc[] Collection to store aggregation of IncomeDoc objects.
     */
    protected $collIncomeDocs;
    protected $collIncomeDocsPartial;

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

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookksScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomeDocsScheduledForDeletion = null;

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
     * Get the [optionally formatted] temporal [document_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDocumentDate($format = null)
    {
        if ($this->document_date === null) {
            return null;
        }

        if ($this->document_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->document_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->document_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [operation_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getOperationDate($format = null)
    {
        if ($this->operation_date === null) {
            return null;
        }

        if ($this->operation_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->operation_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->operation_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [receipt_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getReceiptDate($format = null)
    {
        if ($this->receipt_date === null) {
            return null;
        }

        if ($this->receipt_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->receipt_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->receipt_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [bookking_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBookkingDate($format = null)
    {
        if ($this->bookking_date === null) {
            return null;
        }

        if ($this->bookking_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->bookking_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->bookking_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [payment_deadline_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPaymentDeadlineDate($format = null)
    {
        if ($this->payment_deadline_date === null) {
            return null;
        }

        if ($this->payment_deadline_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->payment_deadline_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->payment_deadline_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [optionally formatted] temporal [payment_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getPaymentDate($format = null)
    {
        if ($this->payment_date === null) {
            return null;
        }

        if ($this->payment_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->payment_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->payment_date, true), $x);
        }

        if ($format === null) {
            // Because propel.useDateTimeClass is true, we return a DateTime object.
            return $dt;
        }

        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }

        return $dt->format($format);

    }

    /**
     * Get the [payment_method] column value.
     *
     * @return int
     */
    public function getPaymentMethod()
    {

        return $this->payment_method;
    }

    /**
     * Get the [month_id] column value.
     *
     * @return int
     */
    public function getMonthId()
    {

        return $this->month_id;
    }

    /**
     * Get the [doc_cat_id] column value.
     *
     * @return int
     */
    public function getDocCatId()
    {

        return $this->doc_cat_id;
    }

    /**
     * Get the [reg_idx] column value.
     *
     * @return int
     */
    public function getRegIdx()
    {

        return $this->reg_idx;
    }

    /**
     * Get the [reg_no] column value.
     *
     * @return string
     */
    public function getRegNo()
    {

        return $this->reg_no;
    }

    /**
     * Get the [doc_idx] column value.
     *
     * @return int
     */
    public function getDocIdx()
    {

        return $this->doc_idx;
    }

    /**
     * Get the [doc_no] column value.
     *
     * @return string
     */
    public function getDocNo()
    {

        return $this->doc_no;
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
     * Get the [user_id] column value.
     *
     * @return int
     */
    public function getUserId()
    {

        return $this->user_id;
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
     * Get the [comment] column value.
     *
     * @return string
     */
    public function getComment()
    {

        return $this->comment;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = DocPeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Sets the value of [document_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setDocumentDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->document_date !== null || $dt !== null) {
            $currentDateAsString = ($this->document_date !== null && $tmpDt = new DateTime($this->document_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->document_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::DOCUMENT_DATE;
            }
        } // if either are not null


        return $this;
    } // setDocumentDate()

    /**
     * Sets the value of [operation_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setOperationDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->operation_date !== null || $dt !== null) {
            $currentDateAsString = ($this->operation_date !== null && $tmpDt = new DateTime($this->operation_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->operation_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::OPERATION_DATE;
            }
        } // if either are not null


        return $this;
    } // setOperationDate()

    /**
     * Sets the value of [receipt_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setReceiptDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->receipt_date !== null || $dt !== null) {
            $currentDateAsString = ($this->receipt_date !== null && $tmpDt = new DateTime($this->receipt_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->receipt_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::RECEIPT_DATE;
            }
        } // if either are not null


        return $this;
    } // setReceiptDate()

    /**
     * Sets the value of [bookking_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setBookkingDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->bookking_date !== null || $dt !== null) {
            $currentDateAsString = ($this->bookking_date !== null && $tmpDt = new DateTime($this->bookking_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->bookking_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::BOOKKING_DATE;
            }
        } // if either are not null


        return $this;
    } // setBookkingDate()

    /**
     * Sets the value of [payment_deadline_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setPaymentDeadlineDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->payment_deadline_date !== null || $dt !== null) {
            $currentDateAsString = ($this->payment_deadline_date !== null && $tmpDt = new DateTime($this->payment_deadline_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->payment_deadline_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::PAYMENT_DEADLINE_DATE;
            }
        } // if either are not null


        return $this;
    } // setPaymentDeadlineDate()

    /**
     * Sets the value of [payment_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return Doc The current object (for fluent API support)
     */
    public function setPaymentDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->payment_date !== null || $dt !== null) {
            $currentDateAsString = ($this->payment_date !== null && $tmpDt = new DateTime($this->payment_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->payment_date = $newDateAsString;
                $this->modifiedColumns[] = DocPeer::PAYMENT_DATE;
            }
        } // if either are not null


        return $this;
    } // setPaymentDate()

    /**
     * Set the value of [payment_method] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setPaymentMethod($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->payment_method !== $v) {
            $this->payment_method = $v;
            $this->modifiedColumns[] = DocPeer::PAYMENT_METHOD;
        }


        return $this;
    } // setPaymentMethod()

    /**
     * Set the value of [month_id] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setMonthId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->month_id !== $v) {
            $this->month_id = $v;
            $this->modifiedColumns[] = DocPeer::MONTH_ID;
        }

        if ($this->aMonth !== null && $this->aMonth->getId() !== $v) {
            $this->aMonth = null;
        }


        return $this;
    } // setMonthId()

    /**
     * Set the value of [doc_cat_id] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setDocCatId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->doc_cat_id !== $v) {
            $this->doc_cat_id = $v;
            $this->modifiedColumns[] = DocPeer::DOC_CAT_ID;
        }

        if ($this->aDocCat !== null && $this->aDocCat->getId() !== $v) {
            $this->aDocCat = null;
        }


        return $this;
    } // setDocCatId()

    /**
     * Set the value of [reg_idx] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setRegIdx($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->reg_idx !== $v) {
            $this->reg_idx = $v;
            $this->modifiedColumns[] = DocPeer::REG_IDX;
        }


        return $this;
    } // setRegIdx()

    /**
     * Set the value of [reg_no] column.
     *
     * @param  string $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setRegNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->reg_no !== $v) {
            $this->reg_no = $v;
            $this->modifiedColumns[] = DocPeer::REG_NO;
        }


        return $this;
    } // setRegNo()

    /**
     * Set the value of [doc_idx] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setDocIdx($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->doc_idx !== $v) {
            $this->doc_idx = $v;
            $this->modifiedColumns[] = DocPeer::DOC_IDX;
        }


        return $this;
    } // setDocIdx()

    /**
     * Set the value of [doc_no] column.
     *
     * @param  string $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setDocNo($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->doc_no !== $v) {
            $this->doc_no = $v;
            $this->modifiedColumns[] = DocPeer::DOC_NO;
        }


        return $this;
    } // setDocNo()

    /**
     * Set the value of [file_id] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_id !== $v) {
            $this->file_id = $v;
            $this->modifiedColumns[] = DocPeer::FILE_ID;
        }

        if ($this->aFile !== null && $this->aFile->getId() !== $v) {
            $this->aFile = null;
        }


        return $this;
    } // setFileId()

    /**
     * Set the value of [user_id] column.
     *
     * @param  int $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setUserId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->user_id !== $v) {
            $this->user_id = $v;
            $this->modifiedColumns[] = DocPeer::USER_ID;
        }

        if ($this->aUser !== null && $this->aUser->getId() !== $v) {
            $this->aUser = null;
        }


        return $this;
    } // setUserId()

    /**
     * Set the value of [desc] column.
     *
     * @param  string $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setDesc($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->desc !== $v) {
            $this->desc = $v;
            $this->modifiedColumns[] = DocPeer::DESC;
        }


        return $this;
    } // setDesc()

    /**
     * Set the value of [comment] column.
     *
     * @param  string $v new value
     * @return Doc The current object (for fluent API support)
     */
    public function setComment($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[] = DocPeer::COMMENT;
        }


        return $this;
    } // setComment()

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
            $this->document_date = ($row[$startcol + 1] !== null) ? (string) $row[$startcol + 1] : null;
            $this->operation_date = ($row[$startcol + 2] !== null) ? (string) $row[$startcol + 2] : null;
            $this->receipt_date = ($row[$startcol + 3] !== null) ? (string) $row[$startcol + 3] : null;
            $this->bookking_date = ($row[$startcol + 4] !== null) ? (string) $row[$startcol + 4] : null;
            $this->payment_deadline_date = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->payment_date = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->payment_method = ($row[$startcol + 7] !== null) ? (int) $row[$startcol + 7] : null;
            $this->month_id = ($row[$startcol + 8] !== null) ? (int) $row[$startcol + 8] : null;
            $this->doc_cat_id = ($row[$startcol + 9] !== null) ? (int) $row[$startcol + 9] : null;
            $this->reg_idx = ($row[$startcol + 10] !== null) ? (int) $row[$startcol + 10] : null;
            $this->reg_no = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->doc_idx = ($row[$startcol + 12] !== null) ? (int) $row[$startcol + 12] : null;
            $this->doc_no = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->file_id = ($row[$startcol + 14] !== null) ? (int) $row[$startcol + 14] : null;
            $this->user_id = ($row[$startcol + 15] !== null) ? (int) $row[$startcol + 15] : null;
            $this->desc = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->comment = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 18; // 18 = DocPeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating Doc object", $e);
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

        if ($this->aMonth !== null && $this->month_id !== $this->aMonth->getId()) {
            $this->aMonth = null;
        }
        if ($this->aDocCat !== null && $this->doc_cat_id !== $this->aDocCat->getId()) {
            $this->aDocCat = null;
        }
        if ($this->aFile !== null && $this->file_id !== $this->aFile->getId()) {
            $this->aFile = null;
        }
        if ($this->aUser !== null && $this->user_id !== $this->aUser->getId()) {
            $this->aUser = null;
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
            $con = Propel::getConnection(DocPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = DocPeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aMonth = null;
            $this->aDocCat = null;
            $this->aFile = null;
            $this->aUser = null;
            $this->collBookks = null;

            $this->collIncomeDocs = null;

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
            $con = Propel::getConnection(DocPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = DocQuery::create()
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
            $con = Propel::getConnection(DocPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                DocPeer::addInstanceToPool($this);
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

            if ($this->aMonth !== null) {
                if ($this->aMonth->isModified() || $this->aMonth->isNew()) {
                    $affectedRows += $this->aMonth->save($con);
                }
                $this->setMonth($this->aMonth);
            }

            if ($this->aDocCat !== null) {
                if ($this->aDocCat->isModified() || $this->aDocCat->isNew()) {
                    $affectedRows += $this->aDocCat->save($con);
                }
                $this->setDocCat($this->aDocCat);
            }

            if ($this->aFile !== null) {
                if ($this->aFile->isModified() || $this->aFile->isNew()) {
                    $affectedRows += $this->aFile->save($con);
                }
                $this->setFile($this->aFile);
            }

            if ($this->aUser !== null) {
                if ($this->aUser->isModified() || $this->aUser->isNew()) {
                    $affectedRows += $this->aUser->save($con);
                }
                $this->setUser($this->aUser);
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

            if ($this->bookksScheduledForDeletion !== null) {
                if (!$this->bookksScheduledForDeletion->isEmpty()) {
                    BookkQuery::create()
                        ->filterByPrimaryKeys($this->bookksScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->bookksScheduledForDeletion = null;
                }
            }

            if ($this->collBookks !== null) {
                foreach ($this->collBookks as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->incomeDocsScheduledForDeletion !== null) {
                if (!$this->incomeDocsScheduledForDeletion->isEmpty()) {
                    IncomeDocQuery::create()
                        ->filterByPrimaryKeys($this->incomeDocsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->incomeDocsScheduledForDeletion = null;
                }
            }

            if ($this->collIncomeDocs !== null) {
                foreach ($this->collIncomeDocs as $referrerFK) {
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

        $this->modifiedColumns[] = DocPeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . DocPeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(DocPeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(DocPeer::DOCUMENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`document_date`';
        }
        if ($this->isColumnModified(DocPeer::OPERATION_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`operation_date`';
        }
        if ($this->isColumnModified(DocPeer::RECEIPT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`receipt_date`';
        }
        if ($this->isColumnModified(DocPeer::BOOKKING_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`bookking_date`';
        }
        if ($this->isColumnModified(DocPeer::PAYMENT_DEADLINE_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`payment_deadline_date`';
        }
        if ($this->isColumnModified(DocPeer::PAYMENT_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`payment_date`';
        }
        if ($this->isColumnModified(DocPeer::PAYMENT_METHOD)) {
            $modifiedColumns[':p' . $index++]  = '`payment_method`';
        }
        if ($this->isColumnModified(DocPeer::MONTH_ID)) {
            $modifiedColumns[':p' . $index++]  = '`month_id`';
        }
        if ($this->isColumnModified(DocPeer::DOC_CAT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`doc_cat_id`';
        }
        if ($this->isColumnModified(DocPeer::REG_IDX)) {
            $modifiedColumns[':p' . $index++]  = '`reg_idx`';
        }
        if ($this->isColumnModified(DocPeer::REG_NO)) {
            $modifiedColumns[':p' . $index++]  = '`reg_no`';
        }
        if ($this->isColumnModified(DocPeer::DOC_IDX)) {
            $modifiedColumns[':p' . $index++]  = '`doc_idx`';
        }
        if ($this->isColumnModified(DocPeer::DOC_NO)) {
            $modifiedColumns[':p' . $index++]  = '`doc_no`';
        }
        if ($this->isColumnModified(DocPeer::FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_id`';
        }
        if ($this->isColumnModified(DocPeer::USER_ID)) {
            $modifiedColumns[':p' . $index++]  = '`user_id`';
        }
        if ($this->isColumnModified(DocPeer::DESC)) {
            $modifiedColumns[':p' . $index++]  = '`desc`';
        }
        if ($this->isColumnModified(DocPeer::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = '`comment`';
        }

        $sql = sprintf(
            'INSERT INTO `doc` (%s) VALUES (%s)',
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
                    case '`document_date`':
                        $stmt->bindValue($identifier, $this->document_date, PDO::PARAM_STR);
                        break;
                    case '`operation_date`':
                        $stmt->bindValue($identifier, $this->operation_date, PDO::PARAM_STR);
                        break;
                    case '`receipt_date`':
                        $stmt->bindValue($identifier, $this->receipt_date, PDO::PARAM_STR);
                        break;
                    case '`bookking_date`':
                        $stmt->bindValue($identifier, $this->bookking_date, PDO::PARAM_STR);
                        break;
                    case '`payment_deadline_date`':
                        $stmt->bindValue($identifier, $this->payment_deadline_date, PDO::PARAM_STR);
                        break;
                    case '`payment_date`':
                        $stmt->bindValue($identifier, $this->payment_date, PDO::PARAM_STR);
                        break;
                    case '`payment_method`':
                        $stmt->bindValue($identifier, $this->payment_method, PDO::PARAM_INT);
                        break;
                    case '`month_id`':
                        $stmt->bindValue($identifier, $this->month_id, PDO::PARAM_INT);
                        break;
                    case '`doc_cat_id`':
                        $stmt->bindValue($identifier, $this->doc_cat_id, PDO::PARAM_INT);
                        break;
                    case '`reg_idx`':
                        $stmt->bindValue($identifier, $this->reg_idx, PDO::PARAM_INT);
                        break;
                    case '`reg_no`':
                        $stmt->bindValue($identifier, $this->reg_no, PDO::PARAM_STR);
                        break;
                    case '`doc_idx`':
                        $stmt->bindValue($identifier, $this->doc_idx, PDO::PARAM_INT);
                        break;
                    case '`doc_no`':
                        $stmt->bindValue($identifier, $this->doc_no, PDO::PARAM_STR);
                        break;
                    case '`file_id`':
                        $stmt->bindValue($identifier, $this->file_id, PDO::PARAM_INT);
                        break;
                    case '`user_id`':
                        $stmt->bindValue($identifier, $this->user_id, PDO::PARAM_INT);
                        break;
                    case '`desc`':
                        $stmt->bindValue($identifier, $this->desc, PDO::PARAM_STR);
                        break;
                    case '`comment`':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
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

            if ($this->aMonth !== null) {
                if (!$this->aMonth->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aMonth->getValidationFailures());
                }
            }

            if ($this->aDocCat !== null) {
                if (!$this->aDocCat->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aDocCat->getValidationFailures());
                }
            }

            if ($this->aFile !== null) {
                if (!$this->aFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFile->getValidationFailures());
                }
            }

            if ($this->aUser !== null) {
                if (!$this->aUser->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aUser->getValidationFailures());
                }
            }


            if (($retval = DocPeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collBookks !== null) {
                    foreach ($this->collBookks as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collIncomeDocs !== null) {
                    foreach ($this->collIncomeDocs as $referrerFK) {
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
        $pos = DocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getDocumentDate();
                break;
            case 2:
                return $this->getOperationDate();
                break;
            case 3:
                return $this->getReceiptDate();
                break;
            case 4:
                return $this->getBookkingDate();
                break;
            case 5:
                return $this->getPaymentDeadlineDate();
                break;
            case 6:
                return $this->getPaymentDate();
                break;
            case 7:
                return $this->getPaymentMethod();
                break;
            case 8:
                return $this->getMonthId();
                break;
            case 9:
                return $this->getDocCatId();
                break;
            case 10:
                return $this->getRegIdx();
                break;
            case 11:
                return $this->getRegNo();
                break;
            case 12:
                return $this->getDocIdx();
                break;
            case 13:
                return $this->getDocNo();
                break;
            case 14:
                return $this->getFileId();
                break;
            case 15:
                return $this->getUserId();
                break;
            case 16:
                return $this->getDesc();
                break;
            case 17:
                return $this->getComment();
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
        if (isset($alreadyDumpedObjects['Doc'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Doc'][$this->getPrimaryKey()] = true;
        $keys = DocPeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getDocumentDate(),
            $keys[2] => $this->getOperationDate(),
            $keys[3] => $this->getReceiptDate(),
            $keys[4] => $this->getBookkingDate(),
            $keys[5] => $this->getPaymentDeadlineDate(),
            $keys[6] => $this->getPaymentDate(),
            $keys[7] => $this->getPaymentMethod(),
            $keys[8] => $this->getMonthId(),
            $keys[9] => $this->getDocCatId(),
            $keys[10] => $this->getRegIdx(),
            $keys[11] => $this->getRegNo(),
            $keys[12] => $this->getDocIdx(),
            $keys[13] => $this->getDocNo(),
            $keys[14] => $this->getFileId(),
            $keys[15] => $this->getUserId(),
            $keys[16] => $this->getDesc(),
            $keys[17] => $this->getComment(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aMonth) {
                $result['Month'] = $this->aMonth->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aDocCat) {
                $result['DocCat'] = $this->aDocCat->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aFile) {
                $result['File'] = $this->aFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aUser) {
                $result['User'] = $this->aUser->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collBookks) {
                $result['Bookks'] = $this->collBookks->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIncomeDocs) {
                $result['IncomeDocs'] = $this->collIncomeDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = DocPeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setDocumentDate($value);
                break;
            case 2:
                $this->setOperationDate($value);
                break;
            case 3:
                $this->setReceiptDate($value);
                break;
            case 4:
                $this->setBookkingDate($value);
                break;
            case 5:
                $this->setPaymentDeadlineDate($value);
                break;
            case 6:
                $this->setPaymentDate($value);
                break;
            case 7:
                $this->setPaymentMethod($value);
                break;
            case 8:
                $this->setMonthId($value);
                break;
            case 9:
                $this->setDocCatId($value);
                break;
            case 10:
                $this->setRegIdx($value);
                break;
            case 11:
                $this->setRegNo($value);
                break;
            case 12:
                $this->setDocIdx($value);
                break;
            case 13:
                $this->setDocNo($value);
                break;
            case 14:
                $this->setFileId($value);
                break;
            case 15:
                $this->setUserId($value);
                break;
            case 16:
                $this->setDesc($value);
                break;
            case 17:
                $this->setComment($value);
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
        $keys = DocPeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setDocumentDate($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setOperationDate($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setReceiptDate($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setBookkingDate($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setPaymentDeadlineDate($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setPaymentDate($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setPaymentMethod($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMonthId($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setDocCatId($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setRegIdx($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setRegNo($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setDocIdx($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setDocNo($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setFileId($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setUserId($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setDesc($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setComment($arr[$keys[17]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(DocPeer::DATABASE_NAME);

        if ($this->isColumnModified(DocPeer::ID)) $criteria->add(DocPeer::ID, $this->id);
        if ($this->isColumnModified(DocPeer::DOCUMENT_DATE)) $criteria->add(DocPeer::DOCUMENT_DATE, $this->document_date);
        if ($this->isColumnModified(DocPeer::OPERATION_DATE)) $criteria->add(DocPeer::OPERATION_DATE, $this->operation_date);
        if ($this->isColumnModified(DocPeer::RECEIPT_DATE)) $criteria->add(DocPeer::RECEIPT_DATE, $this->receipt_date);
        if ($this->isColumnModified(DocPeer::BOOKKING_DATE)) $criteria->add(DocPeer::BOOKKING_DATE, $this->bookking_date);
        if ($this->isColumnModified(DocPeer::PAYMENT_DEADLINE_DATE)) $criteria->add(DocPeer::PAYMENT_DEADLINE_DATE, $this->payment_deadline_date);
        if ($this->isColumnModified(DocPeer::PAYMENT_DATE)) $criteria->add(DocPeer::PAYMENT_DATE, $this->payment_date);
        if ($this->isColumnModified(DocPeer::PAYMENT_METHOD)) $criteria->add(DocPeer::PAYMENT_METHOD, $this->payment_method);
        if ($this->isColumnModified(DocPeer::MONTH_ID)) $criteria->add(DocPeer::MONTH_ID, $this->month_id);
        if ($this->isColumnModified(DocPeer::DOC_CAT_ID)) $criteria->add(DocPeer::DOC_CAT_ID, $this->doc_cat_id);
        if ($this->isColumnModified(DocPeer::REG_IDX)) $criteria->add(DocPeer::REG_IDX, $this->reg_idx);
        if ($this->isColumnModified(DocPeer::REG_NO)) $criteria->add(DocPeer::REG_NO, $this->reg_no);
        if ($this->isColumnModified(DocPeer::DOC_IDX)) $criteria->add(DocPeer::DOC_IDX, $this->doc_idx);
        if ($this->isColumnModified(DocPeer::DOC_NO)) $criteria->add(DocPeer::DOC_NO, $this->doc_no);
        if ($this->isColumnModified(DocPeer::FILE_ID)) $criteria->add(DocPeer::FILE_ID, $this->file_id);
        if ($this->isColumnModified(DocPeer::USER_ID)) $criteria->add(DocPeer::USER_ID, $this->user_id);
        if ($this->isColumnModified(DocPeer::DESC)) $criteria->add(DocPeer::DESC, $this->desc);
        if ($this->isColumnModified(DocPeer::COMMENT)) $criteria->add(DocPeer::COMMENT, $this->comment);

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
        $criteria = new Criteria(DocPeer::DATABASE_NAME);
        $criteria->add(DocPeer::ID, $this->id);

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
     * @param object $copyObj An object of Doc (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setDocumentDate($this->getDocumentDate());
        $copyObj->setOperationDate($this->getOperationDate());
        $copyObj->setReceiptDate($this->getReceiptDate());
        $copyObj->setBookkingDate($this->getBookkingDate());
        $copyObj->setPaymentDeadlineDate($this->getPaymentDeadlineDate());
        $copyObj->setPaymentDate($this->getPaymentDate());
        $copyObj->setPaymentMethod($this->getPaymentMethod());
        $copyObj->setMonthId($this->getMonthId());
        $copyObj->setDocCatId($this->getDocCatId());
        $copyObj->setRegIdx($this->getRegIdx());
        $copyObj->setRegNo($this->getRegNo());
        $copyObj->setDocIdx($this->getDocIdx());
        $copyObj->setDocNo($this->getDocNo());
        $copyObj->setFileId($this->getFileId());
        $copyObj->setUserId($this->getUserId());
        $copyObj->setDesc($this->getDesc());
        $copyObj->setComment($this->getComment());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getBookks() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookk($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIncomeDocs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncomeDoc($relObj->copy($deepCopy));
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
     * @return Doc Clone of current object.
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
     * @return DocPeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new DocPeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a Month object.
     *
     * @param                  Month $v
     * @return Doc The current object (for fluent API support)
     * @throws PropelException
     */
    public function setMonth(Month $v = null)
    {
        if ($v === null) {
            $this->setMonthId(NULL);
        } else {
            $this->setMonthId($v->getId());
        }

        $this->aMonth = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the Month object, it will not be re-added.
        if ($v !== null) {
            $v->addDoc($this);
        }


        return $this;
    }


    /**
     * Get the associated Month object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return Month The associated Month object.
     * @throws PropelException
     */
    public function getMonth(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aMonth === null && ($this->month_id !== null) && $doQuery) {
            $this->aMonth = MonthQuery::create()->findPk($this->month_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aMonth->addDocs($this);
             */
        }

        return $this->aMonth;
    }

    /**
     * Declares an association between this object and a DocCat object.
     *
     * @param                  DocCat $v
     * @return Doc The current object (for fluent API support)
     * @throws PropelException
     */
    public function setDocCat(DocCat $v = null)
    {
        if ($v === null) {
            $this->setDocCatId(NULL);
        } else {
            $this->setDocCatId($v->getId());
        }

        $this->aDocCat = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the DocCat object, it will not be re-added.
        if ($v !== null) {
            $v->addDoc($this);
        }


        return $this;
    }


    /**
     * Get the associated DocCat object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return DocCat The associated DocCat object.
     * @throws PropelException
     */
    public function getDocCat(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aDocCat === null && ($this->doc_cat_id !== null) && $doQuery) {
            $this->aDocCat = DocCatQuery::create()->findPk($this->doc_cat_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aDocCat->addDocs($this);
             */
        }

        return $this->aDocCat;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return Doc The current object (for fluent API support)
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
            $v->addDoc($this);
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
                $this->aFile->addDocs($this);
             */
        }

        return $this->aFile;
    }

    /**
     * Declares an association between this object and a User object.
     *
     * @param                  User $v
     * @return Doc The current object (for fluent API support)
     * @throws PropelException
     */
    public function setUser(User $v = null)
    {
        if ($v === null) {
            $this->setUserId(NULL);
        } else {
            $this->setUserId($v->getId());
        }

        $this->aUser = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the User object, it will not be re-added.
        if ($v !== null) {
            $v->addDoc($this);
        }


        return $this;
    }


    /**
     * Get the associated User object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return User The associated User object.
     * @throws PropelException
     */
    public function getUser(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aUser === null && ($this->user_id !== null) && $doQuery) {
            $this->aUser = UserQuery::create()->findPk($this->user_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aUser->addDocs($this);
             */
        }

        return $this->aUser;
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
        if ('Bookk' == $relationName) {
            $this->initBookks();
        }
        if ('IncomeDoc' == $relationName) {
            $this->initIncomeDocs();
        }
        if ('CostDoc' == $relationName) {
            $this->initCostDocs();
        }
        if ('Contract' == $relationName) {
            $this->initContracts();
        }
    }

    /**
     * Clears out the collBookks collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Doc The current object (for fluent API support)
     * @see        addBookks()
     */
    public function clearBookks()
    {
        $this->collBookks = null; // important to set this to null since that means it is uninitialized
        $this->collBookksPartial = null;

        return $this;
    }

    /**
     * reset is the collBookks collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookks($v = true)
    {
        $this->collBookksPartial = $v;
    }

    /**
     * Initializes the collBookks collection.
     *
     * By default this just sets the collBookks collection to an empty array (like clearcollBookks());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookks($overrideExisting = true)
    {
        if (null !== $this->collBookks && !$overrideExisting) {
            return;
        }
        $this->collBookks = new PropelObjectCollection();
        $this->collBookks->setModel('Bookk');
    }

    /**
     * Gets an array of Bookk objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Doc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Bookk[] List of Bookk objects
     * @throws PropelException
     */
    public function getBookks($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookksPartial && !$this->isNew();
        if (null === $this->collBookks || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookks) {
                // return empty collection
                $this->initBookks();
            } else {
                $collBookks = BookkQuery::create(null, $criteria)
                    ->filterByDoc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookksPartial && count($collBookks)) {
                      $this->initBookks(false);

                      foreach ($collBookks as $obj) {
                        if (false == $this->collBookks->contains($obj)) {
                          $this->collBookks->append($obj);
                        }
                      }

                      $this->collBookksPartial = true;
                    }

                    $collBookks->getInternalIterator()->rewind();

                    return $collBookks;
                }

                if ($partial && $this->collBookks) {
                    foreach ($this->collBookks as $obj) {
                        if ($obj->isNew()) {
                            $collBookks[] = $obj;
                        }
                    }
                }

                $this->collBookks = $collBookks;
                $this->collBookksPartial = false;
            }
        }

        return $this->collBookks;
    }

    /**
     * Sets a collection of Bookk objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookks A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Doc The current object (for fluent API support)
     */
    public function setBookks(PropelCollection $bookks, PropelPDO $con = null)
    {
        $bookksToDelete = $this->getBookks(new Criteria(), $con)->diff($bookks);


        $this->bookksScheduledForDeletion = $bookksToDelete;

        foreach ($bookksToDelete as $bookkRemoved) {
            $bookkRemoved->setDoc(null);
        }

        $this->collBookks = null;
        foreach ($bookks as $bookk) {
            $this->addBookk($bookk);
        }

        $this->collBookks = $bookks;
        $this->collBookksPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Bookk objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Bookk objects.
     * @throws PropelException
     */
    public function countBookks(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookksPartial && !$this->isNew();
        if (null === $this->collBookks || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookks) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookks());
            }
            $query = BookkQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDoc($this)
                ->count($con);
        }

        return count($this->collBookks);
    }

    /**
     * Method called to associate a Bookk object to this object
     * through the Bookk foreign key attribute.
     *
     * @param    Bookk $l Bookk
     * @return Doc The current object (for fluent API support)
     */
    public function addBookk(Bookk $l)
    {
        if ($this->collBookks === null) {
            $this->initBookks();
            $this->collBookksPartial = true;
        }

        if (!in_array($l, $this->collBookks->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookk($l);

            if ($this->bookksScheduledForDeletion and $this->bookksScheduledForDeletion->contains($l)) {
                $this->bookksScheduledForDeletion->remove($this->bookksScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Bookk $bookk The bookk object to add.
     */
    protected function doAddBookk($bookk)
    {
        $this->collBookks[]= $bookk;
        $bookk->setDoc($this);
    }

    /**
     * @param	Bookk $bookk The bookk object to remove.
     * @return Doc The current object (for fluent API support)
     */
    public function removeBookk($bookk)
    {
        if ($this->getBookks()->contains($bookk)) {
            $this->collBookks->remove($this->collBookks->search($bookk));
            if (null === $this->bookksScheduledForDeletion) {
                $this->bookksScheduledForDeletion = clone $this->collBookks;
                $this->bookksScheduledForDeletion->clear();
            }
            $this->bookksScheduledForDeletion[]= $bookk;
            $bookk->setDoc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related Bookks from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Bookk[] List of Bookk objects
     */
    public function getBookksJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getBookks($query, $con);
    }

    /**
     * Clears out the collIncomeDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Doc The current object (for fluent API support)
     * @see        addIncomeDocs()
     */
    public function clearIncomeDocs()
    {
        $this->collIncomeDocs = null; // important to set this to null since that means it is uninitialized
        $this->collIncomeDocsPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomeDocs collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomeDocs($v = true)
    {
        $this->collIncomeDocsPartial = $v;
    }

    /**
     * Initializes the collIncomeDocs collection.
     *
     * By default this just sets the collIncomeDocs collection to an empty array (like clearcollIncomeDocs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomeDocs($overrideExisting = true)
    {
        if (null !== $this->collIncomeDocs && !$overrideExisting) {
            return;
        }
        $this->collIncomeDocs = new PropelObjectCollection();
        $this->collIncomeDocs->setModel('IncomeDoc');
    }

    /**
     * Gets an array of IncomeDoc objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this Doc is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|IncomeDoc[] List of IncomeDoc objects
     * @throws PropelException
     */
    public function getIncomeDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomeDocsPartial && !$this->isNew();
        if (null === $this->collIncomeDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomeDocs) {
                // return empty collection
                $this->initIncomeDocs();
            } else {
                $collIncomeDocs = IncomeDocQuery::create(null, $criteria)
                    ->filterByDoc($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomeDocsPartial && count($collIncomeDocs)) {
                      $this->initIncomeDocs(false);

                      foreach ($collIncomeDocs as $obj) {
                        if (false == $this->collIncomeDocs->contains($obj)) {
                          $this->collIncomeDocs->append($obj);
                        }
                      }

                      $this->collIncomeDocsPartial = true;
                    }

                    $collIncomeDocs->getInternalIterator()->rewind();

                    return $collIncomeDocs;
                }

                if ($partial && $this->collIncomeDocs) {
                    foreach ($this->collIncomeDocs as $obj) {
                        if ($obj->isNew()) {
                            $collIncomeDocs[] = $obj;
                        }
                    }
                }

                $this->collIncomeDocs = $collIncomeDocs;
                $this->collIncomeDocsPartial = false;
            }
        }

        return $this->collIncomeDocs;
    }

    /**
     * Sets a collection of IncomeDoc objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomeDocs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return Doc The current object (for fluent API support)
     */
    public function setIncomeDocs(PropelCollection $incomeDocs, PropelPDO $con = null)
    {
        $incomeDocsToDelete = $this->getIncomeDocs(new Criteria(), $con)->diff($incomeDocs);


        $this->incomeDocsScheduledForDeletion = $incomeDocsToDelete;

        foreach ($incomeDocsToDelete as $incomeDocRemoved) {
            $incomeDocRemoved->setDoc(null);
        }

        $this->collIncomeDocs = null;
        foreach ($incomeDocs as $incomeDoc) {
            $this->addIncomeDoc($incomeDoc);
        }

        $this->collIncomeDocs = $incomeDocs;
        $this->collIncomeDocsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related IncomeDoc objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related IncomeDoc objects.
     * @throws PropelException
     */
    public function countIncomeDocs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomeDocsPartial && !$this->isNew();
        if (null === $this->collIncomeDocs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomeDocs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomeDocs());
            }
            $query = IncomeDocQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByDoc($this)
                ->count($con);
        }

        return count($this->collIncomeDocs);
    }

    /**
     * Method called to associate a IncomeDoc object to this object
     * through the IncomeDoc foreign key attribute.
     *
     * @param    IncomeDoc $l IncomeDoc
     * @return Doc The current object (for fluent API support)
     */
    public function addIncomeDoc(IncomeDoc $l)
    {
        if ($this->collIncomeDocs === null) {
            $this->initIncomeDocs();
            $this->collIncomeDocsPartial = true;
        }

        if (!in_array($l, $this->collIncomeDocs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncomeDoc($l);

            if ($this->incomeDocsScheduledForDeletion and $this->incomeDocsScheduledForDeletion->contains($l)) {
                $this->incomeDocsScheduledForDeletion->remove($this->incomeDocsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	IncomeDoc $incomeDoc The incomeDoc object to add.
     */
    protected function doAddIncomeDoc($incomeDoc)
    {
        $this->collIncomeDocs[]= $incomeDoc;
        $incomeDoc->setDoc($this);
    }

    /**
     * @param	IncomeDoc $incomeDoc The incomeDoc object to remove.
     * @return Doc The current object (for fluent API support)
     */
    public function removeIncomeDoc($incomeDoc)
    {
        if ($this->getIncomeDocs()->contains($incomeDoc)) {
            $this->collIncomeDocs->remove($this->collIncomeDocs->search($incomeDoc));
            if (null === $this->incomeDocsScheduledForDeletion) {
                $this->incomeDocsScheduledForDeletion = clone $this->collIncomeDocs;
                $this->incomeDocsScheduledForDeletion->clear();
            }
            $this->incomeDocsScheduledForDeletion[]= $incomeDoc;
            $incomeDoc->setDoc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related IncomeDocs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|IncomeDoc[] List of IncomeDoc objects
     */
    public function getIncomeDocsJoinIncome($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeDocQuery::create(null, $criteria);
        $query->joinWith('Income', $join_behavior);

        return $this->getIncomeDocs($query, $con);
    }

    /**
     * Clears out the collCostDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Doc The current object (for fluent API support)
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
     * If this Doc is new, it will return
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
                    ->filterByDoc($this)
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
     * @return Doc The current object (for fluent API support)
     */
    public function setCostDocs(PropelCollection $costDocs, PropelPDO $con = null)
    {
        $costDocsToDelete = $this->getCostDocs(new Criteria(), $con)->diff($costDocs);


        $this->costDocsScheduledForDeletion = $costDocsToDelete;

        foreach ($costDocsToDelete as $costDocRemoved) {
            $costDocRemoved->setDoc(null);
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
                ->filterByDoc($this)
                ->count($con);
        }

        return count($this->collCostDocs);
    }

    /**
     * Method called to associate a CostDoc object to this object
     * through the CostDoc foreign key attribute.
     *
     * @param    CostDoc $l CostDoc
     * @return Doc The current object (for fluent API support)
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
        $costDoc->setDoc($this);
    }

    /**
     * @param	CostDoc $costDoc The costDoc object to remove.
     * @return Doc The current object (for fluent API support)
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
            $costDoc->setDoc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related CostDocs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|CostDoc[] List of CostDoc objects
     */
    public function getCostDocsJoinCost($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostDocQuery::create(null, $criteria);
        $query->joinWith('Cost', $join_behavior);

        return $this->getCostDocs($query, $con);
    }

    /**
     * Clears out the collContracts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return Doc The current object (for fluent API support)
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
     * If this Doc is new, it will return
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
                    ->filterByDoc($this)
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
     * @return Doc The current object (for fluent API support)
     */
    public function setContracts(PropelCollection $contracts, PropelPDO $con = null)
    {
        $contractsToDelete = $this->getContracts(new Criteria(), $con)->diff($contracts);


        $this->contractsScheduledForDeletion = $contractsToDelete;

        foreach ($contractsToDelete as $contractRemoved) {
            $contractRemoved->setDoc(null);
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
                ->filterByDoc($this)
                ->count($con);
        }

        return count($this->collContracts);
    }

    /**
     * Method called to associate a Contract object to this object
     * through the Contract foreign key attribute.
     *
     * @param    Contract $l Contract
     * @return Doc The current object (for fluent API support)
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
        $contract->setDoc($this);
    }

    /**
     * @param	Contract $contract The contract object to remove.
     * @return Doc The current object (for fluent API support)
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
            $contract->setDoc(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
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
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
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
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
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
     * Otherwise if this Doc is new, it will return
     * an empty collection; or if this Doc has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Doc.
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
        $this->document_date = null;
        $this->operation_date = null;
        $this->receipt_date = null;
        $this->bookking_date = null;
        $this->payment_deadline_date = null;
        $this->payment_date = null;
        $this->payment_method = null;
        $this->month_id = null;
        $this->doc_cat_id = null;
        $this->reg_idx = null;
        $this->reg_no = null;
        $this->doc_idx = null;
        $this->doc_no = null;
        $this->file_id = null;
        $this->user_id = null;
        $this->desc = null;
        $this->comment = null;
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
            if ($this->collBookks) {
                foreach ($this->collBookks as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIncomeDocs) {
                foreach ($this->collIncomeDocs as $o) {
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
            if ($this->aMonth instanceof Persistent) {
              $this->aMonth->clearAllReferences($deep);
            }
            if ($this->aDocCat instanceof Persistent) {
              $this->aDocCat->clearAllReferences($deep);
            }
            if ($this->aFile instanceof Persistent) {
              $this->aFile->clearAllReferences($deep);
            }
            if ($this->aUser instanceof Persistent) {
              $this->aUser->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collBookks instanceof PropelCollection) {
            $this->collBookks->clearIterator();
        }
        $this->collBookks = null;
        if ($this->collIncomeDocs instanceof PropelCollection) {
            $this->collIncomeDocs->clearIterator();
        }
        $this->collIncomeDocs = null;
        if ($this->collCostDocs instanceof PropelCollection) {
            $this->collCostDocs->clearIterator();
        }
        $this->collCostDocs = null;
        if ($this->collContracts instanceof PropelCollection) {
            $this->collContracts->clearIterator();
        }
        $this->collContracts = null;
        $this->aMonth = null;
        $this->aDocCat = null;
        $this->aFile = null;
        $this->aUser = null;
    }

    /**
     * return the string representation of this object
     *
     * @return string The value of the 'doc_no' column
     */
    public function __toString()
    {
        return (string) $this->getDocNo();
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
