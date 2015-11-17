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
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\ContractQuery;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\CostQuery;
use Oppen\ProjectBundle\Model\Doc;
use Oppen\ProjectBundle\Model\DocQuery;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\FilePeer;
use Oppen\ProjectBundle\Model\FileQuery;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\IncomeQuery;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\ProjectQuery;

abstract class BaseFile extends BaseObject implements Persistent
{
    /**
     * Peer class name
     */
    const PEER = 'Oppen\\ProjectBundle\\Model\\FilePeer';

    /**
     * The Peer class.
     * Instance provides a convenient way of calling static methods on a class
     * that calling code may not be able to identify.
     * @var        FilePeer
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
     * The value for the acc_no field.
     * @var        int
     */
    protected $acc_no;

    /**
     * The value for the file_cat_id field.
     * @var        int
     */
    protected $file_cat_id;

    /**
     * The value for the sub_file_id field.
     * @var        int
     */
    protected $sub_file_id;

    /**
     * The value for the first_name field.
     * @var        string
     */
    protected $first_name;

    /**
     * The value for the second_name field.
     * @var        string
     */
    protected $second_name;

    /**
     * The value for the last_name field.
     * @var        string
     */
    protected $last_name;

    /**
     * The value for the maiden_name field.
     * @var        string
     */
    protected $maiden_name;

    /**
     * The value for the father_name field.
     * @var        string
     */
    protected $father_name;

    /**
     * The value for the mother_name field.
     * @var        string
     */
    protected $mother_name;

    /**
     * The value for the birth_date field.
     * @var        string
     */
    protected $birth_date;

    /**
     * The value for the birth_place field.
     * @var        string
     */
    protected $birth_place;

    /**
     * The value for the pesel field.
     * @var        string
     */
    protected $pesel;

    /**
     * The value for the passport field.
     * @var        string
     */
    protected $passport;

    /**
     * The value for the nip field.
     * @var        string
     */
    protected $nip;

    /**
     * The value for the profession field.
     * @var        string
     */
    protected $profession;

    /**
     * The value for the street field.
     * @var        string
     */
    protected $street;

    /**
     * The value for the house field.
     * @var        string
     */
    protected $house;

    /**
     * The value for the flat field.
     * @var        string
     */
    protected $flat;

    /**
     * The value for the code field.
     * @var        string
     */
    protected $code;

    /**
     * The value for the city field.
     * @var        string
     */
    protected $city;

    /**
     * The value for the district2 field.
     * @var        string
     */
    protected $district2;

    /**
     * The value for the district field.
     * @var        string
     */
    protected $district;

    /**
     * The value for the province field.
     * @var        string
     */
    protected $province;

    /**
     * The value for the country field.
     * @var        string
     */
    protected $country;

    /**
     * The value for the post_office field.
     * @var        string
     */
    protected $post_office;

    /**
     * The value for the bank_account field.
     * @var        string
     */
    protected $bank_account;

    /**
     * The value for the bank_name field.
     * @var        string
     */
    protected $bank_name;

    /**
     * The value for the phone field.
     * @var        string
     */
    protected $phone;

    /**
     * The value for the email field.
     * @var        string
     */
    protected $email;

    /**
     * @var        FileCat
     */
    protected $aFileCat;

    /**
     * @var        File
     */
    protected $aSubFile;

    /**
     * @var        PropelObjectCollection|File[] Collection to store aggregation of File objects.
     */
    protected $collFiles;
    protected $collFilesPartial;

    /**
     * @var        PropelObjectCollection|Doc[] Collection to store aggregation of Doc objects.
     */
    protected $collDocs;
    protected $collDocsPartial;

    /**
     * @var        PropelObjectCollection|BookkEntry[] Collection to store aggregation of BookkEntry objects.
     */
    protected $collBookkEntriesRelatedByFileLev1Id;
    protected $collBookkEntriesRelatedByFileLev1IdPartial;

    /**
     * @var        PropelObjectCollection|BookkEntry[] Collection to store aggregation of BookkEntry objects.
     */
    protected $collBookkEntriesRelatedByFileLev2Id;
    protected $collBookkEntriesRelatedByFileLev2IdPartial;

    /**
     * @var        PropelObjectCollection|BookkEntry[] Collection to store aggregation of BookkEntry objects.
     */
    protected $collBookkEntriesRelatedByFileLev3Id;
    protected $collBookkEntriesRelatedByFileLev3IdPartial;

    /**
     * @var        PropelObjectCollection|Project[] Collection to store aggregation of Project objects.
     */
    protected $collProjects;
    protected $collProjectsPartial;

    /**
     * @var        PropelObjectCollection|Income[] Collection to store aggregation of Income objects.
     */
    protected $collIncomes;
    protected $collIncomesPartial;

    /**
     * @var        PropelObjectCollection|Cost[] Collection to store aggregation of Cost objects.
     */
    protected $collCosts;
    protected $collCostsPartial;

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
    protected $filesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $docsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookkEntriesRelatedByFileLev1IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookkEntriesRelatedByFileLev2IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $bookkEntriesRelatedByFileLev3IdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $projectsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $incomesScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var		PropelObjectCollection
     */
    protected $costsScheduledForDeletion = null;

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
     * Get the [acc_no] column value.
     *
     * @return int
     */
    public function getAccNo()
    {

        return $this->acc_no;
    }

    /**
     * Get the [file_cat_id] column value.
     *
     * @return int
     */
    public function getFileCatId()
    {

        return $this->file_cat_id;
    }

    /**
     * Get the [sub_file_id] column value.
     *
     * @return int
     */
    public function getSubFileId()
    {

        return $this->sub_file_id;
    }

    /**
     * Get the [first_name] column value.
     *
     * @return string
     */
    public function getFirstName()
    {

        return $this->first_name;
    }

    /**
     * Get the [second_name] column value.
     *
     * @return string
     */
    public function getSecondName()
    {

        return $this->second_name;
    }

    /**
     * Get the [last_name] column value.
     *
     * @return string
     */
    public function getLastName()
    {

        return $this->last_name;
    }

    /**
     * Get the [maiden_name] column value.
     *
     * @return string
     */
    public function getMaidenName()
    {

        return $this->maiden_name;
    }

    /**
     * Get the [father_name] column value.
     *
     * @return string
     */
    public function getFatherName()
    {

        return $this->father_name;
    }

    /**
     * Get the [mother_name] column value.
     *
     * @return string
     */
    public function getMotherName()
    {

        return $this->mother_name;
    }

    /**
     * Get the [optionally formatted] temporal [birth_date] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getBirthDate($format = null)
    {
        if ($this->birth_date === null) {
            return null;
        }

        if ($this->birth_date === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }

        try {
            $dt = new DateTime($this->birth_date);
        } catch (Exception $x) {
            throw new PropelException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birth_date, true), $x);
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
     * Get the [birth_place] column value.
     *
     * @return string
     */
    public function getBirthPlace()
    {

        return $this->birth_place;
    }

    /**
     * Get the [pesel] column value.
     *
     * @return string
     */
    public function getPesel()
    {

        return $this->pesel;
    }

    /**
     * Get the [passport] column value.
     *
     * @return string
     */
    public function getPassport()
    {

        return $this->passport;
    }

    /**
     * Get the [nip] column value.
     *
     * @return string
     */
    public function getNip()
    {

        return $this->nip;
    }

    /**
     * Get the [profession] column value.
     *
     * @return string
     */
    public function getProfession()
    {

        return $this->profession;
    }

    /**
     * Get the [street] column value.
     *
     * @return string
     */
    public function getStreet()
    {

        return $this->street;
    }

    /**
     * Get the [house] column value.
     *
     * @return string
     */
    public function getHouse()
    {

        return $this->house;
    }

    /**
     * Get the [flat] column value.
     *
     * @return string
     */
    public function getFlat()
    {

        return $this->flat;
    }

    /**
     * Get the [code] column value.
     *
     * @return string
     */
    public function getCode()
    {

        return $this->code;
    }

    /**
     * Get the [city] column value.
     *
     * @return string
     */
    public function getCity()
    {

        return $this->city;
    }

    /**
     * Get the [district2] column value.
     *
     * @return string
     */
    public function getDistrict2()
    {

        return $this->district2;
    }

    /**
     * Get the [district] column value.
     *
     * @return string
     */
    public function getDistrict()
    {

        return $this->district;
    }

    /**
     * Get the [province] column value.
     *
     * @return string
     */
    public function getProvince()
    {

        return $this->province;
    }

    /**
     * Get the [country] column value.
     *
     * @return string
     */
    public function getCountry()
    {

        return $this->country;
    }

    /**
     * Get the [post_office] column value.
     *
     * @return string
     */
    public function getPostOffice()
    {

        return $this->post_office;
    }

    /**
     * Get the [bank_account] column value.
     *
     * @return string
     */
    public function getBankAccount()
    {

        return $this->bank_account;
    }

    /**
     * Get the [bank_name] column value.
     *
     * @return string
     */
    public function getBankName()
    {

        return $this->bank_name;
    }

    /**
     * Get the [phone] column value.
     *
     * @return string
     */
    public function getPhone()
    {

        return $this->phone;
    }

    /**
     * Get the [email] column value.
     *
     * @return string
     */
    public function getEmail()
    {

        return $this->email;
    }

    /**
     * Set the value of [id] column.
     *
     * @param  int $v new value
     * @return File The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[] = FilePeer::ID;
        }


        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[] = FilePeer::NAME;
        }


        return $this;
    } // setName()

    /**
     * Set the value of [acc_no] column.
     *
     * @param  int $v new value
     * @return File The current object (for fluent API support)
     */
    public function setAccNo($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->acc_no !== $v) {
            $this->acc_no = $v;
            $this->modifiedColumns[] = FilePeer::ACC_NO;
        }


        return $this;
    } // setAccNo()

    /**
     * Set the value of [file_cat_id] column.
     *
     * @param  int $v new value
     * @return File The current object (for fluent API support)
     */
    public function setFileCatId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->file_cat_id !== $v) {
            $this->file_cat_id = $v;
            $this->modifiedColumns[] = FilePeer::FILE_CAT_ID;
        }

        if ($this->aFileCat !== null && $this->aFileCat->getId() !== $v) {
            $this->aFileCat = null;
        }


        return $this;
    } // setFileCatId()

    /**
     * Set the value of [sub_file_id] column.
     *
     * @param  int $v new value
     * @return File The current object (for fluent API support)
     */
    public function setSubFileId($v)
    {
        if ($v !== null && is_numeric($v)) {
            $v = (int) $v;
        }

        if ($this->sub_file_id !== $v) {
            $this->sub_file_id = $v;
            $this->modifiedColumns[] = FilePeer::SUB_FILE_ID;
        }

        if ($this->aSubFile !== null && $this->aSubFile->getId() !== $v) {
            $this->aSubFile = null;
        }


        return $this;
    } // setSubFileId()

    /**
     * Set the value of [first_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setFirstName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->first_name !== $v) {
            $this->first_name = $v;
            $this->modifiedColumns[] = FilePeer::FIRST_NAME;
        }


        return $this;
    } // setFirstName()

    /**
     * Set the value of [second_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setSecondName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->second_name !== $v) {
            $this->second_name = $v;
            $this->modifiedColumns[] = FilePeer::SECOND_NAME;
        }


        return $this;
    } // setSecondName()

    /**
     * Set the value of [last_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setLastName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->last_name !== $v) {
            $this->last_name = $v;
            $this->modifiedColumns[] = FilePeer::LAST_NAME;
        }


        return $this;
    } // setLastName()

    /**
     * Set the value of [maiden_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setMaidenName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->maiden_name !== $v) {
            $this->maiden_name = $v;
            $this->modifiedColumns[] = FilePeer::MAIDEN_NAME;
        }


        return $this;
    } // setMaidenName()

    /**
     * Set the value of [father_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setFatherName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->father_name !== $v) {
            $this->father_name = $v;
            $this->modifiedColumns[] = FilePeer::FATHER_NAME;
        }


        return $this;
    } // setFatherName()

    /**
     * Set the value of [mother_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setMotherName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->mother_name !== $v) {
            $this->mother_name = $v;
            $this->modifiedColumns[] = FilePeer::MOTHER_NAME;
        }


        return $this;
    } // setMotherName()

    /**
     * Sets the value of [birth_date] column to a normalized version of the date/time value specified.
     *
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     * @return File The current object (for fluent API support)
     */
    public function setBirthDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->birth_date !== null || $dt !== null) {
            $currentDateAsString = ($this->birth_date !== null && $tmpDt = new DateTime($this->birth_date)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $dt ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                $this->birth_date = $newDateAsString;
                $this->modifiedColumns[] = FilePeer::BIRTH_DATE;
            }
        } // if either are not null


        return $this;
    } // setBirthDate()

    /**
     * Set the value of [birth_place] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setBirthPlace($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->birth_place !== $v) {
            $this->birth_place = $v;
            $this->modifiedColumns[] = FilePeer::BIRTH_PLACE;
        }


        return $this;
    } // setBirthPlace()

    /**
     * Set the value of [pesel] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setPesel($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->pesel !== $v) {
            $this->pesel = $v;
            $this->modifiedColumns[] = FilePeer::PESEL;
        }


        return $this;
    } // setPesel()

    /**
     * Set the value of [passport] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setPassport($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->passport !== $v) {
            $this->passport = $v;
            $this->modifiedColumns[] = FilePeer::PASSPORT;
        }


        return $this;
    } // setPassport()

    /**
     * Set the value of [nip] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setNip($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->nip !== $v) {
            $this->nip = $v;
            $this->modifiedColumns[] = FilePeer::NIP;
        }


        return $this;
    } // setNip()

    /**
     * Set the value of [profession] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setProfession($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->profession !== $v) {
            $this->profession = $v;
            $this->modifiedColumns[] = FilePeer::PROFESSION;
        }


        return $this;
    } // setProfession()

    /**
     * Set the value of [street] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setStreet($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->street !== $v) {
            $this->street = $v;
            $this->modifiedColumns[] = FilePeer::STREET;
        }


        return $this;
    } // setStreet()

    /**
     * Set the value of [house] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setHouse($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->house !== $v) {
            $this->house = $v;
            $this->modifiedColumns[] = FilePeer::HOUSE;
        }


        return $this;
    } // setHouse()

    /**
     * Set the value of [flat] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setFlat($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->flat !== $v) {
            $this->flat = $v;
            $this->modifiedColumns[] = FilePeer::FLAT;
        }


        return $this;
    } // setFlat()

    /**
     * Set the value of [code] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setCode($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->code !== $v) {
            $this->code = $v;
            $this->modifiedColumns[] = FilePeer::CODE;
        }


        return $this;
    } // setCode()

    /**
     * Set the value of [city] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setCity($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->city !== $v) {
            $this->city = $v;
            $this->modifiedColumns[] = FilePeer::CITY;
        }


        return $this;
    } // setCity()

    /**
     * Set the value of [district2] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setDistrict2($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->district2 !== $v) {
            $this->district2 = $v;
            $this->modifiedColumns[] = FilePeer::DISTRICT2;
        }


        return $this;
    } // setDistrict2()

    /**
     * Set the value of [district] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setDistrict($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->district !== $v) {
            $this->district = $v;
            $this->modifiedColumns[] = FilePeer::DISTRICT;
        }


        return $this;
    } // setDistrict()

    /**
     * Set the value of [province] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setProvince($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->province !== $v) {
            $this->province = $v;
            $this->modifiedColumns[] = FilePeer::PROVINCE;
        }


        return $this;
    } // setProvince()

    /**
     * Set the value of [country] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setCountry($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->country !== $v) {
            $this->country = $v;
            $this->modifiedColumns[] = FilePeer::COUNTRY;
        }


        return $this;
    } // setCountry()

    /**
     * Set the value of [post_office] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setPostOffice($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->post_office !== $v) {
            $this->post_office = $v;
            $this->modifiedColumns[] = FilePeer::POST_OFFICE;
        }


        return $this;
    } // setPostOffice()

    /**
     * Set the value of [bank_account] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setBankAccount($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_account !== $v) {
            $this->bank_account = $v;
            $this->modifiedColumns[] = FilePeer::BANK_ACCOUNT;
        }


        return $this;
    } // setBankAccount()

    /**
     * Set the value of [bank_name] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setBankName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bank_name !== $v) {
            $this->bank_name = $v;
            $this->modifiedColumns[] = FilePeer::BANK_NAME;
        }


        return $this;
    } // setBankName()

    /**
     * Set the value of [phone] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setPhone($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->phone !== $v) {
            $this->phone = $v;
            $this->modifiedColumns[] = FilePeer::PHONE;
        }


        return $this;
    } // setPhone()

    /**
     * Set the value of [email] column.
     *
     * @param  string $v new value
     * @return File The current object (for fluent API support)
     */
    public function setEmail($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->email !== $v) {
            $this->email = $v;
            $this->modifiedColumns[] = FilePeer::EMAIL;
        }


        return $this;
    } // setEmail()

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
            $this->acc_no = ($row[$startcol + 2] !== null) ? (int) $row[$startcol + 2] : null;
            $this->file_cat_id = ($row[$startcol + 3] !== null) ? (int) $row[$startcol + 3] : null;
            $this->sub_file_id = ($row[$startcol + 4] !== null) ? (int) $row[$startcol + 4] : null;
            $this->first_name = ($row[$startcol + 5] !== null) ? (string) $row[$startcol + 5] : null;
            $this->second_name = ($row[$startcol + 6] !== null) ? (string) $row[$startcol + 6] : null;
            $this->last_name = ($row[$startcol + 7] !== null) ? (string) $row[$startcol + 7] : null;
            $this->maiden_name = ($row[$startcol + 8] !== null) ? (string) $row[$startcol + 8] : null;
            $this->father_name = ($row[$startcol + 9] !== null) ? (string) $row[$startcol + 9] : null;
            $this->mother_name = ($row[$startcol + 10] !== null) ? (string) $row[$startcol + 10] : null;
            $this->birth_date = ($row[$startcol + 11] !== null) ? (string) $row[$startcol + 11] : null;
            $this->birth_place = ($row[$startcol + 12] !== null) ? (string) $row[$startcol + 12] : null;
            $this->pesel = ($row[$startcol + 13] !== null) ? (string) $row[$startcol + 13] : null;
            $this->passport = ($row[$startcol + 14] !== null) ? (string) $row[$startcol + 14] : null;
            $this->nip = ($row[$startcol + 15] !== null) ? (string) $row[$startcol + 15] : null;
            $this->profession = ($row[$startcol + 16] !== null) ? (string) $row[$startcol + 16] : null;
            $this->street = ($row[$startcol + 17] !== null) ? (string) $row[$startcol + 17] : null;
            $this->house = ($row[$startcol + 18] !== null) ? (string) $row[$startcol + 18] : null;
            $this->flat = ($row[$startcol + 19] !== null) ? (string) $row[$startcol + 19] : null;
            $this->code = ($row[$startcol + 20] !== null) ? (string) $row[$startcol + 20] : null;
            $this->city = ($row[$startcol + 21] !== null) ? (string) $row[$startcol + 21] : null;
            $this->district2 = ($row[$startcol + 22] !== null) ? (string) $row[$startcol + 22] : null;
            $this->district = ($row[$startcol + 23] !== null) ? (string) $row[$startcol + 23] : null;
            $this->province = ($row[$startcol + 24] !== null) ? (string) $row[$startcol + 24] : null;
            $this->country = ($row[$startcol + 25] !== null) ? (string) $row[$startcol + 25] : null;
            $this->post_office = ($row[$startcol + 26] !== null) ? (string) $row[$startcol + 26] : null;
            $this->bank_account = ($row[$startcol + 27] !== null) ? (string) $row[$startcol + 27] : null;
            $this->bank_name = ($row[$startcol + 28] !== null) ? (string) $row[$startcol + 28] : null;
            $this->phone = ($row[$startcol + 29] !== null) ? (string) $row[$startcol + 29] : null;
            $this->email = ($row[$startcol + 30] !== null) ? (string) $row[$startcol + 30] : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }
            $this->postHydrate($row, $startcol, $rehydrate);

            return $startcol + 31; // 31 = FilePeer::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException("Error populating File object", $e);
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

        if ($this->aFileCat !== null && $this->file_cat_id !== $this->aFileCat->getId()) {
            $this->aFileCat = null;
        }
        if ($this->aSubFile !== null && $this->sub_file_id !== $this->aSubFile->getId()) {
            $this->aSubFile = null;
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
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $stmt = FilePeer::doSelectStmt($this->buildPkeyCriteria(), $con);
        $row = $stmt->fetch(PDO::FETCH_NUM);
        $stmt->closeCursor();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aFileCat = null;
            $this->aSubFile = null;
            $this->collFiles = null;

            $this->collDocs = null;

            $this->collBookkEntriesRelatedByFileLev1Id = null;

            $this->collBookkEntriesRelatedByFileLev2Id = null;

            $this->collBookkEntriesRelatedByFileLev3Id = null;

            $this->collProjects = null;

            $this->collIncomes = null;

            $this->collCosts = null;

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
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $con->beginTransaction();
        try {
            $deleteQuery = FileQuery::create()
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
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
                FilePeer::addInstanceToPool($this);
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

            if ($this->aFileCat !== null) {
                if ($this->aFileCat->isModified() || $this->aFileCat->isNew()) {
                    $affectedRows += $this->aFileCat->save($con);
                }
                $this->setFileCat($this->aFileCat);
            }

            if ($this->aSubFile !== null) {
                if ($this->aSubFile->isModified() || $this->aSubFile->isNew()) {
                    $affectedRows += $this->aSubFile->save($con);
                }
                $this->setSubFile($this->aSubFile);
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

            if ($this->filesScheduledForDeletion !== null) {
                if (!$this->filesScheduledForDeletion->isEmpty()) {
                    foreach ($this->filesScheduledForDeletion as $file) {
                        // need to save related object because we set the relation to null
                        $file->save($con);
                    }
                    $this->filesScheduledForDeletion = null;
                }
            }

            if ($this->collFiles !== null) {
                foreach ($this->collFiles as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->docsScheduledForDeletion !== null) {
                if (!$this->docsScheduledForDeletion->isEmpty()) {
                    foreach ($this->docsScheduledForDeletion as $doc) {
                        // need to save related object because we set the relation to null
                        $doc->save($con);
                    }
                    $this->docsScheduledForDeletion = null;
                }
            }

            if ($this->collDocs !== null) {
                foreach ($this->collDocs as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion !== null) {
                if (!$this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion as $bookkEntryRelatedByFileLev1Id) {
                        // need to save related object because we set the relation to null
                        $bookkEntryRelatedByFileLev1Id->save($con);
                    }
                    $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion = null;
                }
            }

            if ($this->collBookkEntriesRelatedByFileLev1Id !== null) {
                foreach ($this->collBookkEntriesRelatedByFileLev1Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion !== null) {
                if (!$this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion as $bookkEntryRelatedByFileLev2Id) {
                        // need to save related object because we set the relation to null
                        $bookkEntryRelatedByFileLev2Id->save($con);
                    }
                    $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion = null;
                }
            }

            if ($this->collBookkEntriesRelatedByFileLev2Id !== null) {
                foreach ($this->collBookkEntriesRelatedByFileLev2Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion !== null) {
                if (!$this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion->isEmpty()) {
                    foreach ($this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion as $bookkEntryRelatedByFileLev3Id) {
                        // need to save related object because we set the relation to null
                        $bookkEntryRelatedByFileLev3Id->save($con);
                    }
                    $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion = null;
                }
            }

            if ($this->collBookkEntriesRelatedByFileLev3Id !== null) {
                foreach ($this->collBookkEntriesRelatedByFileLev3Id as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->projectsScheduledForDeletion !== null) {
                if (!$this->projectsScheduledForDeletion->isEmpty()) {
                    foreach ($this->projectsScheduledForDeletion as $project) {
                        // need to save related object because we set the relation to null
                        $project->save($con);
                    }
                    $this->projectsScheduledForDeletion = null;
                }
            }

            if ($this->collProjects !== null) {
                foreach ($this->collProjects as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->incomesScheduledForDeletion !== null) {
                if (!$this->incomesScheduledForDeletion->isEmpty()) {
                    foreach ($this->incomesScheduledForDeletion as $income) {
                        // need to save related object because we set the relation to null
                        $income->save($con);
                    }
                    $this->incomesScheduledForDeletion = null;
                }
            }

            if ($this->collIncomes !== null) {
                foreach ($this->collIncomes as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->costsScheduledForDeletion !== null) {
                if (!$this->costsScheduledForDeletion->isEmpty()) {
                    foreach ($this->costsScheduledForDeletion as $cost) {
                        // need to save related object because we set the relation to null
                        $cost->save($con);
                    }
                    $this->costsScheduledForDeletion = null;
                }
            }

            if ($this->collCosts !== null) {
                foreach ($this->collCosts as $referrerFK) {
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

        $this->modifiedColumns[] = FilePeer::ID;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . FilePeer::ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(FilePeer::ID)) {
            $modifiedColumns[':p' . $index++]  = '`id`';
        }
        if ($this->isColumnModified(FilePeer::NAME)) {
            $modifiedColumns[':p' . $index++]  = '`name`';
        }
        if ($this->isColumnModified(FilePeer::ACC_NO)) {
            $modifiedColumns[':p' . $index++]  = '`acc_no`';
        }
        if ($this->isColumnModified(FilePeer::FILE_CAT_ID)) {
            $modifiedColumns[':p' . $index++]  = '`file_cat_id`';
        }
        if ($this->isColumnModified(FilePeer::SUB_FILE_ID)) {
            $modifiedColumns[':p' . $index++]  = '`sub_file_id`';
        }
        if ($this->isColumnModified(FilePeer::FIRST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`first_name`';
        }
        if ($this->isColumnModified(FilePeer::SECOND_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`second_name`';
        }
        if ($this->isColumnModified(FilePeer::LAST_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`last_name`';
        }
        if ($this->isColumnModified(FilePeer::MAIDEN_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`maiden_name`';
        }
        if ($this->isColumnModified(FilePeer::FATHER_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`father_name`';
        }
        if ($this->isColumnModified(FilePeer::MOTHER_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`mother_name`';
        }
        if ($this->isColumnModified(FilePeer::BIRTH_DATE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_date`';
        }
        if ($this->isColumnModified(FilePeer::BIRTH_PLACE)) {
            $modifiedColumns[':p' . $index++]  = '`birth_place`';
        }
        if ($this->isColumnModified(FilePeer::PESEL)) {
            $modifiedColumns[':p' . $index++]  = '`PESEL`';
        }
        if ($this->isColumnModified(FilePeer::PASSPORT)) {
            $modifiedColumns[':p' . $index++]  = '`Passport`';
        }
        if ($this->isColumnModified(FilePeer::NIP)) {
            $modifiedColumns[':p' . $index++]  = '`NIP`';
        }
        if ($this->isColumnModified(FilePeer::PROFESSION)) {
            $modifiedColumns[':p' . $index++]  = '`profession`';
        }
        if ($this->isColumnModified(FilePeer::STREET)) {
            $modifiedColumns[':p' . $index++]  = '`street`';
        }
        if ($this->isColumnModified(FilePeer::HOUSE)) {
            $modifiedColumns[':p' . $index++]  = '`house`';
        }
        if ($this->isColumnModified(FilePeer::FLAT)) {
            $modifiedColumns[':p' . $index++]  = '`flat`';
        }
        if ($this->isColumnModified(FilePeer::CODE)) {
            $modifiedColumns[':p' . $index++]  = '`code`';
        }
        if ($this->isColumnModified(FilePeer::CITY)) {
            $modifiedColumns[':p' . $index++]  = '`city`';
        }
        if ($this->isColumnModified(FilePeer::DISTRICT2)) {
            $modifiedColumns[':p' . $index++]  = '`district2`';
        }
        if ($this->isColumnModified(FilePeer::DISTRICT)) {
            $modifiedColumns[':p' . $index++]  = '`district`';
        }
        if ($this->isColumnModified(FilePeer::PROVINCE)) {
            $modifiedColumns[':p' . $index++]  = '`province`';
        }
        if ($this->isColumnModified(FilePeer::COUNTRY)) {
            $modifiedColumns[':p' . $index++]  = '`country`';
        }
        if ($this->isColumnModified(FilePeer::POST_OFFICE)) {
            $modifiedColumns[':p' . $index++]  = '`post_office`';
        }
        if ($this->isColumnModified(FilePeer::BANK_ACCOUNT)) {
            $modifiedColumns[':p' . $index++]  = '`bank_account`';
        }
        if ($this->isColumnModified(FilePeer::BANK_NAME)) {
            $modifiedColumns[':p' . $index++]  = '`bank_name`';
        }
        if ($this->isColumnModified(FilePeer::PHONE)) {
            $modifiedColumns[':p' . $index++]  = '`phone`';
        }
        if ($this->isColumnModified(FilePeer::EMAIL)) {
            $modifiedColumns[':p' . $index++]  = '`email`';
        }

        $sql = sprintf(
            'INSERT INTO `file` (%s) VALUES (%s)',
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
                    case '`acc_no`':
                        $stmt->bindValue($identifier, $this->acc_no, PDO::PARAM_INT);
                        break;
                    case '`file_cat_id`':
                        $stmt->bindValue($identifier, $this->file_cat_id, PDO::PARAM_INT);
                        break;
                    case '`sub_file_id`':
                        $stmt->bindValue($identifier, $this->sub_file_id, PDO::PARAM_INT);
                        break;
                    case '`first_name`':
                        $stmt->bindValue($identifier, $this->first_name, PDO::PARAM_STR);
                        break;
                    case '`second_name`':
                        $stmt->bindValue($identifier, $this->second_name, PDO::PARAM_STR);
                        break;
                    case '`last_name`':
                        $stmt->bindValue($identifier, $this->last_name, PDO::PARAM_STR);
                        break;
                    case '`maiden_name`':
                        $stmt->bindValue($identifier, $this->maiden_name, PDO::PARAM_STR);
                        break;
                    case '`father_name`':
                        $stmt->bindValue($identifier, $this->father_name, PDO::PARAM_STR);
                        break;
                    case '`mother_name`':
                        $stmt->bindValue($identifier, $this->mother_name, PDO::PARAM_STR);
                        break;
                    case '`birth_date`':
                        $stmt->bindValue($identifier, $this->birth_date, PDO::PARAM_STR);
                        break;
                    case '`birth_place`':
                        $stmt->bindValue($identifier, $this->birth_place, PDO::PARAM_STR);
                        break;
                    case '`PESEL`':
                        $stmt->bindValue($identifier, $this->pesel, PDO::PARAM_STR);
                        break;
                    case '`Passport`':
                        $stmt->bindValue($identifier, $this->passport, PDO::PARAM_STR);
                        break;
                    case '`NIP`':
                        $stmt->bindValue($identifier, $this->nip, PDO::PARAM_STR);
                        break;
                    case '`profession`':
                        $stmt->bindValue($identifier, $this->profession, PDO::PARAM_STR);
                        break;
                    case '`street`':
                        $stmt->bindValue($identifier, $this->street, PDO::PARAM_STR);
                        break;
                    case '`house`':
                        $stmt->bindValue($identifier, $this->house, PDO::PARAM_STR);
                        break;
                    case '`flat`':
                        $stmt->bindValue($identifier, $this->flat, PDO::PARAM_STR);
                        break;
                    case '`code`':
                        $stmt->bindValue($identifier, $this->code, PDO::PARAM_STR);
                        break;
                    case '`city`':
                        $stmt->bindValue($identifier, $this->city, PDO::PARAM_STR);
                        break;
                    case '`district2`':
                        $stmt->bindValue($identifier, $this->district2, PDO::PARAM_STR);
                        break;
                    case '`district`':
                        $stmt->bindValue($identifier, $this->district, PDO::PARAM_STR);
                        break;
                    case '`province`':
                        $stmt->bindValue($identifier, $this->province, PDO::PARAM_STR);
                        break;
                    case '`country`':
                        $stmt->bindValue($identifier, $this->country, PDO::PARAM_STR);
                        break;
                    case '`post_office`':
                        $stmt->bindValue($identifier, $this->post_office, PDO::PARAM_STR);
                        break;
                    case '`bank_account`':
                        $stmt->bindValue($identifier, $this->bank_account, PDO::PARAM_STR);
                        break;
                    case '`bank_name`':
                        $stmt->bindValue($identifier, $this->bank_name, PDO::PARAM_STR);
                        break;
                    case '`phone`':
                        $stmt->bindValue($identifier, $this->phone, PDO::PARAM_STR);
                        break;
                    case '`email`':
                        $stmt->bindValue($identifier, $this->email, PDO::PARAM_STR);
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

            if ($this->aFileCat !== null) {
                if (!$this->aFileCat->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aFileCat->getValidationFailures());
                }
            }

            if ($this->aSubFile !== null) {
                if (!$this->aSubFile->validate($columns)) {
                    $failureMap = array_merge($failureMap, $this->aSubFile->getValidationFailures());
                }
            }


            if (($retval = FilePeer::doValidate($this, $columns)) !== true) {
                $failureMap = array_merge($failureMap, $retval);
            }


                if ($this->collFiles !== null) {
                    foreach ($this->collFiles as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collDocs !== null) {
                    foreach ($this->collDocs as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBookkEntriesRelatedByFileLev1Id !== null) {
                    foreach ($this->collBookkEntriesRelatedByFileLev1Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBookkEntriesRelatedByFileLev2Id !== null) {
                    foreach ($this->collBookkEntriesRelatedByFileLev2Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collBookkEntriesRelatedByFileLev3Id !== null) {
                    foreach ($this->collBookkEntriesRelatedByFileLev3Id as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collProjects !== null) {
                    foreach ($this->collProjects as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collIncomes !== null) {
                    foreach ($this->collIncomes as $referrerFK) {
                        if (!$referrerFK->validate($columns)) {
                            $failureMap = array_merge($failureMap, $referrerFK->getValidationFailures());
                        }
                    }
                }

                if ($this->collCosts !== null) {
                    foreach ($this->collCosts as $referrerFK) {
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
        $pos = FilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);
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
                return $this->getAccNo();
                break;
            case 3:
                return $this->getFileCatId();
                break;
            case 4:
                return $this->getSubFileId();
                break;
            case 5:
                return $this->getFirstName();
                break;
            case 6:
                return $this->getSecondName();
                break;
            case 7:
                return $this->getLastName();
                break;
            case 8:
                return $this->getMaidenName();
                break;
            case 9:
                return $this->getFatherName();
                break;
            case 10:
                return $this->getMotherName();
                break;
            case 11:
                return $this->getBirthDate();
                break;
            case 12:
                return $this->getBirthPlace();
                break;
            case 13:
                return $this->getPesel();
                break;
            case 14:
                return $this->getPassport();
                break;
            case 15:
                return $this->getNip();
                break;
            case 16:
                return $this->getProfession();
                break;
            case 17:
                return $this->getStreet();
                break;
            case 18:
                return $this->getHouse();
                break;
            case 19:
                return $this->getFlat();
                break;
            case 20:
                return $this->getCode();
                break;
            case 21:
                return $this->getCity();
                break;
            case 22:
                return $this->getDistrict2();
                break;
            case 23:
                return $this->getDistrict();
                break;
            case 24:
                return $this->getProvince();
                break;
            case 25:
                return $this->getCountry();
                break;
            case 26:
                return $this->getPostOffice();
                break;
            case 27:
                return $this->getBankAccount();
                break;
            case 28:
                return $this->getBankName();
                break;
            case 29:
                return $this->getPhone();
                break;
            case 30:
                return $this->getEmail();
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
        if (isset($alreadyDumpedObjects['File'][$this->getPrimaryKey()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['File'][$this->getPrimaryKey()] = true;
        $keys = FilePeer::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getAccNo(),
            $keys[3] => $this->getFileCatId(),
            $keys[4] => $this->getSubFileId(),
            $keys[5] => $this->getFirstName(),
            $keys[6] => $this->getSecondName(),
            $keys[7] => $this->getLastName(),
            $keys[8] => $this->getMaidenName(),
            $keys[9] => $this->getFatherName(),
            $keys[10] => $this->getMotherName(),
            $keys[11] => $this->getBirthDate(),
            $keys[12] => $this->getBirthPlace(),
            $keys[13] => $this->getPesel(),
            $keys[14] => $this->getPassport(),
            $keys[15] => $this->getNip(),
            $keys[16] => $this->getProfession(),
            $keys[17] => $this->getStreet(),
            $keys[18] => $this->getHouse(),
            $keys[19] => $this->getFlat(),
            $keys[20] => $this->getCode(),
            $keys[21] => $this->getCity(),
            $keys[22] => $this->getDistrict2(),
            $keys[23] => $this->getDistrict(),
            $keys[24] => $this->getProvince(),
            $keys[25] => $this->getCountry(),
            $keys[26] => $this->getPostOffice(),
            $keys[27] => $this->getBankAccount(),
            $keys[28] => $this->getBankName(),
            $keys[29] => $this->getPhone(),
            $keys[30] => $this->getEmail(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aFileCat) {
                $result['FileCat'] = $this->aFileCat->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->aSubFile) {
                $result['SubFile'] = $this->aSubFile->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collFiles) {
                $result['Files'] = $this->collFiles->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collDocs) {
                $result['Docs'] = $this->collDocs->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBookkEntriesRelatedByFileLev1Id) {
                $result['BookkEntriesRelatedByFileLev1Id'] = $this->collBookkEntriesRelatedByFileLev1Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBookkEntriesRelatedByFileLev2Id) {
                $result['BookkEntriesRelatedByFileLev2Id'] = $this->collBookkEntriesRelatedByFileLev2Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collBookkEntriesRelatedByFileLev3Id) {
                $result['BookkEntriesRelatedByFileLev3Id'] = $this->collBookkEntriesRelatedByFileLev3Id->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collProjects) {
                $result['Projects'] = $this->collProjects->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collIncomes) {
                $result['Incomes'] = $this->collIncomes->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCosts) {
                $result['Costs'] = $this->collCosts->toArray(null, true, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
        $pos = FilePeer::translateFieldName($name, $type, BasePeer::TYPE_NUM);

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
                $this->setAccNo($value);
                break;
            case 3:
                $this->setFileCatId($value);
                break;
            case 4:
                $this->setSubFileId($value);
                break;
            case 5:
                $this->setFirstName($value);
                break;
            case 6:
                $this->setSecondName($value);
                break;
            case 7:
                $this->setLastName($value);
                break;
            case 8:
                $this->setMaidenName($value);
                break;
            case 9:
                $this->setFatherName($value);
                break;
            case 10:
                $this->setMotherName($value);
                break;
            case 11:
                $this->setBirthDate($value);
                break;
            case 12:
                $this->setBirthPlace($value);
                break;
            case 13:
                $this->setPesel($value);
                break;
            case 14:
                $this->setPassport($value);
                break;
            case 15:
                $this->setNip($value);
                break;
            case 16:
                $this->setProfession($value);
                break;
            case 17:
                $this->setStreet($value);
                break;
            case 18:
                $this->setHouse($value);
                break;
            case 19:
                $this->setFlat($value);
                break;
            case 20:
                $this->setCode($value);
                break;
            case 21:
                $this->setCity($value);
                break;
            case 22:
                $this->setDistrict2($value);
                break;
            case 23:
                $this->setDistrict($value);
                break;
            case 24:
                $this->setProvince($value);
                break;
            case 25:
                $this->setCountry($value);
                break;
            case 26:
                $this->setPostOffice($value);
                break;
            case 27:
                $this->setBankAccount($value);
                break;
            case 28:
                $this->setBankName($value);
                break;
            case 29:
                $this->setPhone($value);
                break;
            case 30:
                $this->setEmail($value);
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
        $keys = FilePeer::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) $this->setId($arr[$keys[0]]);
        if (array_key_exists($keys[1], $arr)) $this->setName($arr[$keys[1]]);
        if (array_key_exists($keys[2], $arr)) $this->setAccNo($arr[$keys[2]]);
        if (array_key_exists($keys[3], $arr)) $this->setFileCatId($arr[$keys[3]]);
        if (array_key_exists($keys[4], $arr)) $this->setSubFileId($arr[$keys[4]]);
        if (array_key_exists($keys[5], $arr)) $this->setFirstName($arr[$keys[5]]);
        if (array_key_exists($keys[6], $arr)) $this->setSecondName($arr[$keys[6]]);
        if (array_key_exists($keys[7], $arr)) $this->setLastName($arr[$keys[7]]);
        if (array_key_exists($keys[8], $arr)) $this->setMaidenName($arr[$keys[8]]);
        if (array_key_exists($keys[9], $arr)) $this->setFatherName($arr[$keys[9]]);
        if (array_key_exists($keys[10], $arr)) $this->setMotherName($arr[$keys[10]]);
        if (array_key_exists($keys[11], $arr)) $this->setBirthDate($arr[$keys[11]]);
        if (array_key_exists($keys[12], $arr)) $this->setBirthPlace($arr[$keys[12]]);
        if (array_key_exists($keys[13], $arr)) $this->setPesel($arr[$keys[13]]);
        if (array_key_exists($keys[14], $arr)) $this->setPassport($arr[$keys[14]]);
        if (array_key_exists($keys[15], $arr)) $this->setNip($arr[$keys[15]]);
        if (array_key_exists($keys[16], $arr)) $this->setProfession($arr[$keys[16]]);
        if (array_key_exists($keys[17], $arr)) $this->setStreet($arr[$keys[17]]);
        if (array_key_exists($keys[18], $arr)) $this->setHouse($arr[$keys[18]]);
        if (array_key_exists($keys[19], $arr)) $this->setFlat($arr[$keys[19]]);
        if (array_key_exists($keys[20], $arr)) $this->setCode($arr[$keys[20]]);
        if (array_key_exists($keys[21], $arr)) $this->setCity($arr[$keys[21]]);
        if (array_key_exists($keys[22], $arr)) $this->setDistrict2($arr[$keys[22]]);
        if (array_key_exists($keys[23], $arr)) $this->setDistrict($arr[$keys[23]]);
        if (array_key_exists($keys[24], $arr)) $this->setProvince($arr[$keys[24]]);
        if (array_key_exists($keys[25], $arr)) $this->setCountry($arr[$keys[25]]);
        if (array_key_exists($keys[26], $arr)) $this->setPostOffice($arr[$keys[26]]);
        if (array_key_exists($keys[27], $arr)) $this->setBankAccount($arr[$keys[27]]);
        if (array_key_exists($keys[28], $arr)) $this->setBankName($arr[$keys[28]]);
        if (array_key_exists($keys[29], $arr)) $this->setPhone($arr[$keys[29]]);
        if (array_key_exists($keys[30], $arr)) $this->setEmail($arr[$keys[30]]);
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(FilePeer::DATABASE_NAME);

        if ($this->isColumnModified(FilePeer::ID)) $criteria->add(FilePeer::ID, $this->id);
        if ($this->isColumnModified(FilePeer::NAME)) $criteria->add(FilePeer::NAME, $this->name);
        if ($this->isColumnModified(FilePeer::ACC_NO)) $criteria->add(FilePeer::ACC_NO, $this->acc_no);
        if ($this->isColumnModified(FilePeer::FILE_CAT_ID)) $criteria->add(FilePeer::FILE_CAT_ID, $this->file_cat_id);
        if ($this->isColumnModified(FilePeer::SUB_FILE_ID)) $criteria->add(FilePeer::SUB_FILE_ID, $this->sub_file_id);
        if ($this->isColumnModified(FilePeer::FIRST_NAME)) $criteria->add(FilePeer::FIRST_NAME, $this->first_name);
        if ($this->isColumnModified(FilePeer::SECOND_NAME)) $criteria->add(FilePeer::SECOND_NAME, $this->second_name);
        if ($this->isColumnModified(FilePeer::LAST_NAME)) $criteria->add(FilePeer::LAST_NAME, $this->last_name);
        if ($this->isColumnModified(FilePeer::MAIDEN_NAME)) $criteria->add(FilePeer::MAIDEN_NAME, $this->maiden_name);
        if ($this->isColumnModified(FilePeer::FATHER_NAME)) $criteria->add(FilePeer::FATHER_NAME, $this->father_name);
        if ($this->isColumnModified(FilePeer::MOTHER_NAME)) $criteria->add(FilePeer::MOTHER_NAME, $this->mother_name);
        if ($this->isColumnModified(FilePeer::BIRTH_DATE)) $criteria->add(FilePeer::BIRTH_DATE, $this->birth_date);
        if ($this->isColumnModified(FilePeer::BIRTH_PLACE)) $criteria->add(FilePeer::BIRTH_PLACE, $this->birth_place);
        if ($this->isColumnModified(FilePeer::PESEL)) $criteria->add(FilePeer::PESEL, $this->pesel);
        if ($this->isColumnModified(FilePeer::PASSPORT)) $criteria->add(FilePeer::PASSPORT, $this->passport);
        if ($this->isColumnModified(FilePeer::NIP)) $criteria->add(FilePeer::NIP, $this->nip);
        if ($this->isColumnModified(FilePeer::PROFESSION)) $criteria->add(FilePeer::PROFESSION, $this->profession);
        if ($this->isColumnModified(FilePeer::STREET)) $criteria->add(FilePeer::STREET, $this->street);
        if ($this->isColumnModified(FilePeer::HOUSE)) $criteria->add(FilePeer::HOUSE, $this->house);
        if ($this->isColumnModified(FilePeer::FLAT)) $criteria->add(FilePeer::FLAT, $this->flat);
        if ($this->isColumnModified(FilePeer::CODE)) $criteria->add(FilePeer::CODE, $this->code);
        if ($this->isColumnModified(FilePeer::CITY)) $criteria->add(FilePeer::CITY, $this->city);
        if ($this->isColumnModified(FilePeer::DISTRICT2)) $criteria->add(FilePeer::DISTRICT2, $this->district2);
        if ($this->isColumnModified(FilePeer::DISTRICT)) $criteria->add(FilePeer::DISTRICT, $this->district);
        if ($this->isColumnModified(FilePeer::PROVINCE)) $criteria->add(FilePeer::PROVINCE, $this->province);
        if ($this->isColumnModified(FilePeer::COUNTRY)) $criteria->add(FilePeer::COUNTRY, $this->country);
        if ($this->isColumnModified(FilePeer::POST_OFFICE)) $criteria->add(FilePeer::POST_OFFICE, $this->post_office);
        if ($this->isColumnModified(FilePeer::BANK_ACCOUNT)) $criteria->add(FilePeer::BANK_ACCOUNT, $this->bank_account);
        if ($this->isColumnModified(FilePeer::BANK_NAME)) $criteria->add(FilePeer::BANK_NAME, $this->bank_name);
        if ($this->isColumnModified(FilePeer::PHONE)) $criteria->add(FilePeer::PHONE, $this->phone);
        if ($this->isColumnModified(FilePeer::EMAIL)) $criteria->add(FilePeer::EMAIL, $this->email);

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
        $criteria = new Criteria(FilePeer::DATABASE_NAME);
        $criteria->add(FilePeer::ID, $this->id);

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
     * @param object $copyObj An object of File (or compatible) type.
     * @param boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setAccNo($this->getAccNo());
        $copyObj->setFileCatId($this->getFileCatId());
        $copyObj->setSubFileId($this->getSubFileId());
        $copyObj->setFirstName($this->getFirstName());
        $copyObj->setSecondName($this->getSecondName());
        $copyObj->setLastName($this->getLastName());
        $copyObj->setMaidenName($this->getMaidenName());
        $copyObj->setFatherName($this->getFatherName());
        $copyObj->setMotherName($this->getMotherName());
        $copyObj->setBirthDate($this->getBirthDate());
        $copyObj->setBirthPlace($this->getBirthPlace());
        $copyObj->setPesel($this->getPesel());
        $copyObj->setPassport($this->getPassport());
        $copyObj->setNip($this->getNip());
        $copyObj->setProfession($this->getProfession());
        $copyObj->setStreet($this->getStreet());
        $copyObj->setHouse($this->getHouse());
        $copyObj->setFlat($this->getFlat());
        $copyObj->setCode($this->getCode());
        $copyObj->setCity($this->getCity());
        $copyObj->setDistrict2($this->getDistrict2());
        $copyObj->setDistrict($this->getDistrict());
        $copyObj->setProvince($this->getProvince());
        $copyObj->setCountry($this->getCountry());
        $copyObj->setPostOffice($this->getPostOffice());
        $copyObj->setBankAccount($this->getBankAccount());
        $copyObj->setBankName($this->getBankName());
        $copyObj->setPhone($this->getPhone());
        $copyObj->setEmail($this->getEmail());

        if ($deepCopy && !$this->startCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);
            // store object hash to prevent cycle
            $this->startCopy = true;

            foreach ($this->getFiles() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addFile($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getDocs() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addDoc($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookkEntriesRelatedByFileLev1Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookkEntryRelatedByFileLev1Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookkEntriesRelatedByFileLev2Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookkEntryRelatedByFileLev2Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getBookkEntriesRelatedByFileLev3Id() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addBookkEntryRelatedByFileLev3Id($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getProjects() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addProject($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getIncomes() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addIncome($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCosts() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCost($relObj->copy($deepCopy));
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
     * @return File Clone of current object.
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
     * @return FilePeer
     */
    public function getPeer()
    {
        if (self::$peer === null) {
            self::$peer = new FilePeer();
        }

        return self::$peer;
    }

    /**
     * Declares an association between this object and a FileCat object.
     *
     * @param                  FileCat $v
     * @return File The current object (for fluent API support)
     * @throws PropelException
     */
    public function setFileCat(FileCat $v = null)
    {
        if ($v === null) {
            $this->setFileCatId(NULL);
        } else {
            $this->setFileCatId($v->getId());
        }

        $this->aFileCat = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the FileCat object, it will not be re-added.
        if ($v !== null) {
            $v->addFile($this);
        }


        return $this;
    }


    /**
     * Get the associated FileCat object
     *
     * @param PropelPDO $con Optional Connection object.
     * @param $doQuery Executes a query to get the object if required
     * @return FileCat The associated FileCat object.
     * @throws PropelException
     */
    public function getFileCat(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aFileCat === null && ($this->file_cat_id !== null) && $doQuery) {
            $this->aFileCat = FileCatQuery::create()->findPk($this->file_cat_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aFileCat->addFiles($this);
             */
        }

        return $this->aFileCat;
    }

    /**
     * Declares an association between this object and a File object.
     *
     * @param                  File $v
     * @return File The current object (for fluent API support)
     * @throws PropelException
     */
    public function setSubFile(File $v = null)
    {
        if ($v === null) {
            $this->setSubFileId(NULL);
        } else {
            $this->setSubFileId($v->getId());
        }

        $this->aSubFile = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the File object, it will not be re-added.
        if ($v !== null) {
            $v->addFile($this);
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
    public function getSubFile(PropelPDO $con = null, $doQuery = true)
    {
        if ($this->aSubFile === null && ($this->sub_file_id !== null) && $doQuery) {
            $this->aSubFile = FileQuery::create()->findPk($this->sub_file_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aSubFile->addFiles($this);
             */
        }

        return $this->aSubFile;
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
        if ('File' == $relationName) {
            $this->initFiles();
        }
        if ('Doc' == $relationName) {
            $this->initDocs();
        }
        if ('BookkEntryRelatedByFileLev1Id' == $relationName) {
            $this->initBookkEntriesRelatedByFileLev1Id();
        }
        if ('BookkEntryRelatedByFileLev2Id' == $relationName) {
            $this->initBookkEntriesRelatedByFileLev2Id();
        }
        if ('BookkEntryRelatedByFileLev3Id' == $relationName) {
            $this->initBookkEntriesRelatedByFileLev3Id();
        }
        if ('Project' == $relationName) {
            $this->initProjects();
        }
        if ('Income' == $relationName) {
            $this->initIncomes();
        }
        if ('Cost' == $relationName) {
            $this->initCosts();
        }
        if ('Contract' == $relationName) {
            $this->initContracts();
        }
    }

    /**
     * Clears out the collFiles collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addFiles()
     */
    public function clearFiles()
    {
        $this->collFiles = null; // important to set this to null since that means it is uninitialized
        $this->collFilesPartial = null;

        return $this;
    }

    /**
     * reset is the collFiles collection loaded partially
     *
     * @return void
     */
    public function resetPartialFiles($v = true)
    {
        $this->collFilesPartial = $v;
    }

    /**
     * Initializes the collFiles collection.
     *
     * By default this just sets the collFiles collection to an empty array (like clearcollFiles());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initFiles($overrideExisting = true)
    {
        if (null !== $this->collFiles && !$overrideExisting) {
            return;
        }
        $this->collFiles = new PropelObjectCollection();
        $this->collFiles->setModel('File');
    }

    /**
     * Gets an array of File objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|File[] List of File objects
     * @throws PropelException
     */
    public function getFiles($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                // return empty collection
                $this->initFiles();
            } else {
                $collFiles = FileQuery::create(null, $criteria)
                    ->filterBySubFile($this)
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

    /**
     * Sets a collection of File objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $files A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setFiles(PropelCollection $files, PropelPDO $con = null)
    {
        $filesToDelete = $this->getFiles(new Criteria(), $con)->diff($files);


        $this->filesScheduledForDeletion = $filesToDelete;

        foreach ($filesToDelete as $fileRemoved) {
            $fileRemoved->setSubFile(null);
        }

        $this->collFiles = null;
        foreach ($files as $file) {
            $this->addFile($file);
        }

        $this->collFiles = $files;
        $this->collFilesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related File objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related File objects.
     * @throws PropelException
     */
    public function countFiles(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collFilesPartial && !$this->isNew();
        if (null === $this->collFiles || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collFiles) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getFiles());
            }
            $query = FileQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBySubFile($this)
                ->count($con);
        }

        return count($this->collFiles);
    }

    /**
     * Method called to associate a File object to this object
     * through the File foreign key attribute.
     *
     * @param    File $l File
     * @return File The current object (for fluent API support)
     */
    public function addFile(File $l)
    {
        if ($this->collFiles === null) {
            $this->initFiles();
            $this->collFilesPartial = true;
        }

        if (!in_array($l, $this->collFiles->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddFile($l);

            if ($this->filesScheduledForDeletion and $this->filesScheduledForDeletion->contains($l)) {
                $this->filesScheduledForDeletion->remove($this->filesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	File $file The file object to add.
     */
    protected function doAddFile($file)
    {
        $this->collFiles[]= $file;
        $file->setSubFile($this);
    }

    /**
     * @param	File $file The file object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeFile($file)
    {
        if ($this->getFiles()->contains($file)) {
            $this->collFiles->remove($this->collFiles->search($file));
            if (null === $this->filesScheduledForDeletion) {
                $this->filesScheduledForDeletion = clone $this->collFiles;
                $this->filesScheduledForDeletion->clear();
            }
            $this->filesScheduledForDeletion[]= $file;
            $file->setSubFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Files from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|File[] List of File objects
     */
    public function getFilesJoinFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = FileQuery::create(null, $criteria);
        $query->joinWith('FileCat', $join_behavior);

        return $this->getFiles($query, $con);
    }

    /**
     * Clears out the collDocs collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addDocs()
     */
    public function clearDocs()
    {
        $this->collDocs = null; // important to set this to null since that means it is uninitialized
        $this->collDocsPartial = null;

        return $this;
    }

    /**
     * reset is the collDocs collection loaded partially
     *
     * @return void
     */
    public function resetPartialDocs($v = true)
    {
        $this->collDocsPartial = $v;
    }

    /**
     * Initializes the collDocs collection.
     *
     * By default this just sets the collDocs collection to an empty array (like clearcollDocs());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initDocs($overrideExisting = true)
    {
        if (null !== $this->collDocs && !$overrideExisting) {
            return;
        }
        $this->collDocs = new PropelObjectCollection();
        $this->collDocs->setModel('Doc');
    }

    /**
     * Gets an array of Doc objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Doc[] List of Doc objects
     * @throws PropelException
     */
    public function getDocs($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collDocsPartial && !$this->isNew();
        if (null === $this->collDocs || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collDocs) {
                // return empty collection
                $this->initDocs();
            } else {
                $collDocs = DocQuery::create(null, $criteria)
                    ->filterByFile($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collDocsPartial && count($collDocs)) {
                      $this->initDocs(false);

                      foreach ($collDocs as $obj) {
                        if (false == $this->collDocs->contains($obj)) {
                          $this->collDocs->append($obj);
                        }
                      }

                      $this->collDocsPartial = true;
                    }

                    $collDocs->getInternalIterator()->rewind();

                    return $collDocs;
                }

                if ($partial && $this->collDocs) {
                    foreach ($this->collDocs as $obj) {
                        if ($obj->isNew()) {
                            $collDocs[] = $obj;
                        }
                    }
                }

                $this->collDocs = $collDocs;
                $this->collDocsPartial = false;
            }
        }

        return $this->collDocs;
    }

    /**
     * Sets a collection of Doc objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $docs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setDocs(PropelCollection $docs, PropelPDO $con = null)
    {
        $docsToDelete = $this->getDocs(new Criteria(), $con)->diff($docs);


        $this->docsScheduledForDeletion = $docsToDelete;

        foreach ($docsToDelete as $docRemoved) {
            $docRemoved->setFile(null);
        }

        $this->collDocs = null;
        foreach ($docs as $doc) {
            $this->addDoc($doc);
        }

        $this->collDocs = $docs;
        $this->collDocsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Doc objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Doc objects.
     * @throws PropelException
     */
    public function countDocs(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collDocsPartial && !$this->isNew();
        if (null === $this->collDocs || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collDocs) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getDocs());
            }
            $query = DocQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFile($this)
                ->count($con);
        }

        return count($this->collDocs);
    }

    /**
     * Method called to associate a Doc object to this object
     * through the Doc foreign key attribute.
     *
     * @param    Doc $l Doc
     * @return File The current object (for fluent API support)
     */
    public function addDoc(Doc $l)
    {
        if ($this->collDocs === null) {
            $this->initDocs();
            $this->collDocsPartial = true;
        }

        if (!in_array($l, $this->collDocs->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddDoc($l);

            if ($this->docsScheduledForDeletion and $this->docsScheduledForDeletion->contains($l)) {
                $this->docsScheduledForDeletion->remove($this->docsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Doc $doc The doc object to add.
     */
    protected function doAddDoc($doc)
    {
        $this->collDocs[]= $doc;
        $doc->setFile($this);
    }

    /**
     * @param	Doc $doc The doc object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeDoc($doc)
    {
        if ($this->getDocs()->contains($doc)) {
            $this->collDocs->remove($this->collDocs->search($doc));
            if (null === $this->docsScheduledForDeletion) {
                $this->docsScheduledForDeletion = clone $this->collDocs;
                $this->docsScheduledForDeletion->clear();
            }
            $this->docsScheduledForDeletion[]= $doc;
            $doc->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinMonth($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('Month', $join_behavior);

        return $this->getDocs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinDocCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('DocCat', $join_behavior);

        return $this->getDocs($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Docs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Doc[] List of Doc objects
     */
    public function getDocsJoinUser($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = DocQuery::create(null, $criteria);
        $query->joinWith('User', $join_behavior);

        return $this->getDocs($query, $con);
    }

    /**
     * Clears out the collBookkEntriesRelatedByFileLev1Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addBookkEntriesRelatedByFileLev1Id()
     */
    public function clearBookkEntriesRelatedByFileLev1Id()
    {
        $this->collBookkEntriesRelatedByFileLev1Id = null; // important to set this to null since that means it is uninitialized
        $this->collBookkEntriesRelatedByFileLev1IdPartial = null;

        return $this;
    }

    /**
     * reset is the collBookkEntriesRelatedByFileLev1Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookkEntriesRelatedByFileLev1Id($v = true)
    {
        $this->collBookkEntriesRelatedByFileLev1IdPartial = $v;
    }

    /**
     * Initializes the collBookkEntriesRelatedByFileLev1Id collection.
     *
     * By default this just sets the collBookkEntriesRelatedByFileLev1Id collection to an empty array (like clearcollBookkEntriesRelatedByFileLev1Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookkEntriesRelatedByFileLev1Id($overrideExisting = true)
    {
        if (null !== $this->collBookkEntriesRelatedByFileLev1Id && !$overrideExisting) {
            return;
        }
        $this->collBookkEntriesRelatedByFileLev1Id = new PropelObjectCollection();
        $this->collBookkEntriesRelatedByFileLev1Id->setModel('BookkEntry');
    }

    /**
     * Gets an array of BookkEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     * @throws PropelException
     */
    public function getBookkEntriesRelatedByFileLev1Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev1IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev1Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev1Id) {
                // return empty collection
                $this->initBookkEntriesRelatedByFileLev1Id();
            } else {
                $collBookkEntriesRelatedByFileLev1Id = BookkEntryQuery::create(null, $criteria)
                    ->filterByFileLev1($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookkEntriesRelatedByFileLev1IdPartial && count($collBookkEntriesRelatedByFileLev1Id)) {
                      $this->initBookkEntriesRelatedByFileLev1Id(false);

                      foreach ($collBookkEntriesRelatedByFileLev1Id as $obj) {
                        if (false == $this->collBookkEntriesRelatedByFileLev1Id->contains($obj)) {
                          $this->collBookkEntriesRelatedByFileLev1Id->append($obj);
                        }
                      }

                      $this->collBookkEntriesRelatedByFileLev1IdPartial = true;
                    }

                    $collBookkEntriesRelatedByFileLev1Id->getInternalIterator()->rewind();

                    return $collBookkEntriesRelatedByFileLev1Id;
                }

                if ($partial && $this->collBookkEntriesRelatedByFileLev1Id) {
                    foreach ($this->collBookkEntriesRelatedByFileLev1Id as $obj) {
                        if ($obj->isNew()) {
                            $collBookkEntriesRelatedByFileLev1Id[] = $obj;
                        }
                    }
                }

                $this->collBookkEntriesRelatedByFileLev1Id = $collBookkEntriesRelatedByFileLev1Id;
                $this->collBookkEntriesRelatedByFileLev1IdPartial = false;
            }
        }

        return $this->collBookkEntriesRelatedByFileLev1Id;
    }

    /**
     * Sets a collection of BookkEntryRelatedByFileLev1Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookkEntriesRelatedByFileLev1Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setBookkEntriesRelatedByFileLev1Id(PropelCollection $bookkEntriesRelatedByFileLev1Id, PropelPDO $con = null)
    {
        $bookkEntriesRelatedByFileLev1IdToDelete = $this->getBookkEntriesRelatedByFileLev1Id(new Criteria(), $con)->diff($bookkEntriesRelatedByFileLev1Id);


        $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion = $bookkEntriesRelatedByFileLev1IdToDelete;

        foreach ($bookkEntriesRelatedByFileLev1IdToDelete as $bookkEntryRelatedByFileLev1IdRemoved) {
            $bookkEntryRelatedByFileLev1IdRemoved->setFileLev1(null);
        }

        $this->collBookkEntriesRelatedByFileLev1Id = null;
        foreach ($bookkEntriesRelatedByFileLev1Id as $bookkEntryRelatedByFileLev1Id) {
            $this->addBookkEntryRelatedByFileLev1Id($bookkEntryRelatedByFileLev1Id);
        }

        $this->collBookkEntriesRelatedByFileLev1Id = $bookkEntriesRelatedByFileLev1Id;
        $this->collBookkEntriesRelatedByFileLev1IdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BookkEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BookkEntry objects.
     * @throws PropelException
     */
    public function countBookkEntriesRelatedByFileLev1Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev1IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev1Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev1Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookkEntriesRelatedByFileLev1Id());
            }
            $query = BookkEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileLev1($this)
                ->count($con);
        }

        return count($this->collBookkEntriesRelatedByFileLev1Id);
    }

    /**
     * Method called to associate a BookkEntry object to this object
     * through the BookkEntry foreign key attribute.
     *
     * @param    BookkEntry $l BookkEntry
     * @return File The current object (for fluent API support)
     */
    public function addBookkEntryRelatedByFileLev1Id(BookkEntry $l)
    {
        if ($this->collBookkEntriesRelatedByFileLev1Id === null) {
            $this->initBookkEntriesRelatedByFileLev1Id();
            $this->collBookkEntriesRelatedByFileLev1IdPartial = true;
        }

        if (!in_array($l, $this->collBookkEntriesRelatedByFileLev1Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookkEntryRelatedByFileLev1Id($l);

            if ($this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion and $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion->contains($l)) {
                $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion->remove($this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BookkEntryRelatedByFileLev1Id $bookkEntryRelatedByFileLev1Id The bookkEntryRelatedByFileLev1Id object to add.
     */
    protected function doAddBookkEntryRelatedByFileLev1Id($bookkEntryRelatedByFileLev1Id)
    {
        $this->collBookkEntriesRelatedByFileLev1Id[]= $bookkEntryRelatedByFileLev1Id;
        $bookkEntryRelatedByFileLev1Id->setFileLev1($this);
    }

    /**
     * @param	BookkEntryRelatedByFileLev1Id $bookkEntryRelatedByFileLev1Id The bookkEntryRelatedByFileLev1Id object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeBookkEntryRelatedByFileLev1Id($bookkEntryRelatedByFileLev1Id)
    {
        if ($this->getBookkEntriesRelatedByFileLev1Id()->contains($bookkEntryRelatedByFileLev1Id)) {
            $this->collBookkEntriesRelatedByFileLev1Id->remove($this->collBookkEntriesRelatedByFileLev1Id->search($bookkEntryRelatedByFileLev1Id));
            if (null === $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion) {
                $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion = clone $this->collBookkEntriesRelatedByFileLev1Id;
                $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion->clear();
            }
            $this->bookkEntriesRelatedByFileLev1IdScheduledForDeletion[]= $bookkEntryRelatedByFileLev1Id;
            $bookkEntryRelatedByFileLev1Id->setFileLev1(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev1Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev1IdJoinBookk($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Bookk', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev1Id($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev1Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev1IdJoinAccount($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Account', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev1Id($query, $con);
    }

    /**
     * Clears out the collBookkEntriesRelatedByFileLev2Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addBookkEntriesRelatedByFileLev2Id()
     */
    public function clearBookkEntriesRelatedByFileLev2Id()
    {
        $this->collBookkEntriesRelatedByFileLev2Id = null; // important to set this to null since that means it is uninitialized
        $this->collBookkEntriesRelatedByFileLev2IdPartial = null;

        return $this;
    }

    /**
     * reset is the collBookkEntriesRelatedByFileLev2Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookkEntriesRelatedByFileLev2Id($v = true)
    {
        $this->collBookkEntriesRelatedByFileLev2IdPartial = $v;
    }

    /**
     * Initializes the collBookkEntriesRelatedByFileLev2Id collection.
     *
     * By default this just sets the collBookkEntriesRelatedByFileLev2Id collection to an empty array (like clearcollBookkEntriesRelatedByFileLev2Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookkEntriesRelatedByFileLev2Id($overrideExisting = true)
    {
        if (null !== $this->collBookkEntriesRelatedByFileLev2Id && !$overrideExisting) {
            return;
        }
        $this->collBookkEntriesRelatedByFileLev2Id = new PropelObjectCollection();
        $this->collBookkEntriesRelatedByFileLev2Id->setModel('BookkEntry');
    }

    /**
     * Gets an array of BookkEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     * @throws PropelException
     */
    public function getBookkEntriesRelatedByFileLev2Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev2IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev2Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev2Id) {
                // return empty collection
                $this->initBookkEntriesRelatedByFileLev2Id();
            } else {
                $collBookkEntriesRelatedByFileLev2Id = BookkEntryQuery::create(null, $criteria)
                    ->filterByFileLev2($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookkEntriesRelatedByFileLev2IdPartial && count($collBookkEntriesRelatedByFileLev2Id)) {
                      $this->initBookkEntriesRelatedByFileLev2Id(false);

                      foreach ($collBookkEntriesRelatedByFileLev2Id as $obj) {
                        if (false == $this->collBookkEntriesRelatedByFileLev2Id->contains($obj)) {
                          $this->collBookkEntriesRelatedByFileLev2Id->append($obj);
                        }
                      }

                      $this->collBookkEntriesRelatedByFileLev2IdPartial = true;
                    }

                    $collBookkEntriesRelatedByFileLev2Id->getInternalIterator()->rewind();

                    return $collBookkEntriesRelatedByFileLev2Id;
                }

                if ($partial && $this->collBookkEntriesRelatedByFileLev2Id) {
                    foreach ($this->collBookkEntriesRelatedByFileLev2Id as $obj) {
                        if ($obj->isNew()) {
                            $collBookkEntriesRelatedByFileLev2Id[] = $obj;
                        }
                    }
                }

                $this->collBookkEntriesRelatedByFileLev2Id = $collBookkEntriesRelatedByFileLev2Id;
                $this->collBookkEntriesRelatedByFileLev2IdPartial = false;
            }
        }

        return $this->collBookkEntriesRelatedByFileLev2Id;
    }

    /**
     * Sets a collection of BookkEntryRelatedByFileLev2Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookkEntriesRelatedByFileLev2Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setBookkEntriesRelatedByFileLev2Id(PropelCollection $bookkEntriesRelatedByFileLev2Id, PropelPDO $con = null)
    {
        $bookkEntriesRelatedByFileLev2IdToDelete = $this->getBookkEntriesRelatedByFileLev2Id(new Criteria(), $con)->diff($bookkEntriesRelatedByFileLev2Id);


        $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion = $bookkEntriesRelatedByFileLev2IdToDelete;

        foreach ($bookkEntriesRelatedByFileLev2IdToDelete as $bookkEntryRelatedByFileLev2IdRemoved) {
            $bookkEntryRelatedByFileLev2IdRemoved->setFileLev2(null);
        }

        $this->collBookkEntriesRelatedByFileLev2Id = null;
        foreach ($bookkEntriesRelatedByFileLev2Id as $bookkEntryRelatedByFileLev2Id) {
            $this->addBookkEntryRelatedByFileLev2Id($bookkEntryRelatedByFileLev2Id);
        }

        $this->collBookkEntriesRelatedByFileLev2Id = $bookkEntriesRelatedByFileLev2Id;
        $this->collBookkEntriesRelatedByFileLev2IdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BookkEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BookkEntry objects.
     * @throws PropelException
     */
    public function countBookkEntriesRelatedByFileLev2Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev2IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev2Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev2Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookkEntriesRelatedByFileLev2Id());
            }
            $query = BookkEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileLev2($this)
                ->count($con);
        }

        return count($this->collBookkEntriesRelatedByFileLev2Id);
    }

    /**
     * Method called to associate a BookkEntry object to this object
     * through the BookkEntry foreign key attribute.
     *
     * @param    BookkEntry $l BookkEntry
     * @return File The current object (for fluent API support)
     */
    public function addBookkEntryRelatedByFileLev2Id(BookkEntry $l)
    {
        if ($this->collBookkEntriesRelatedByFileLev2Id === null) {
            $this->initBookkEntriesRelatedByFileLev2Id();
            $this->collBookkEntriesRelatedByFileLev2IdPartial = true;
        }

        if (!in_array($l, $this->collBookkEntriesRelatedByFileLev2Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookkEntryRelatedByFileLev2Id($l);

            if ($this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion and $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion->contains($l)) {
                $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion->remove($this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BookkEntryRelatedByFileLev2Id $bookkEntryRelatedByFileLev2Id The bookkEntryRelatedByFileLev2Id object to add.
     */
    protected function doAddBookkEntryRelatedByFileLev2Id($bookkEntryRelatedByFileLev2Id)
    {
        $this->collBookkEntriesRelatedByFileLev2Id[]= $bookkEntryRelatedByFileLev2Id;
        $bookkEntryRelatedByFileLev2Id->setFileLev2($this);
    }

    /**
     * @param	BookkEntryRelatedByFileLev2Id $bookkEntryRelatedByFileLev2Id The bookkEntryRelatedByFileLev2Id object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeBookkEntryRelatedByFileLev2Id($bookkEntryRelatedByFileLev2Id)
    {
        if ($this->getBookkEntriesRelatedByFileLev2Id()->contains($bookkEntryRelatedByFileLev2Id)) {
            $this->collBookkEntriesRelatedByFileLev2Id->remove($this->collBookkEntriesRelatedByFileLev2Id->search($bookkEntryRelatedByFileLev2Id));
            if (null === $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion) {
                $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion = clone $this->collBookkEntriesRelatedByFileLev2Id;
                $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion->clear();
            }
            $this->bookkEntriesRelatedByFileLev2IdScheduledForDeletion[]= $bookkEntryRelatedByFileLev2Id;
            $bookkEntryRelatedByFileLev2Id->setFileLev2(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev2Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev2IdJoinBookk($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Bookk', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev2Id($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev2Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev2IdJoinAccount($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Account', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev2Id($query, $con);
    }

    /**
     * Clears out the collBookkEntriesRelatedByFileLev3Id collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addBookkEntriesRelatedByFileLev3Id()
     */
    public function clearBookkEntriesRelatedByFileLev3Id()
    {
        $this->collBookkEntriesRelatedByFileLev3Id = null; // important to set this to null since that means it is uninitialized
        $this->collBookkEntriesRelatedByFileLev3IdPartial = null;

        return $this;
    }

    /**
     * reset is the collBookkEntriesRelatedByFileLev3Id collection loaded partially
     *
     * @return void
     */
    public function resetPartialBookkEntriesRelatedByFileLev3Id($v = true)
    {
        $this->collBookkEntriesRelatedByFileLev3IdPartial = $v;
    }

    /**
     * Initializes the collBookkEntriesRelatedByFileLev3Id collection.
     *
     * By default this just sets the collBookkEntriesRelatedByFileLev3Id collection to an empty array (like clearcollBookkEntriesRelatedByFileLev3Id());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initBookkEntriesRelatedByFileLev3Id($overrideExisting = true)
    {
        if (null !== $this->collBookkEntriesRelatedByFileLev3Id && !$overrideExisting) {
            return;
        }
        $this->collBookkEntriesRelatedByFileLev3Id = new PropelObjectCollection();
        $this->collBookkEntriesRelatedByFileLev3Id->setModel('BookkEntry');
    }

    /**
     * Gets an array of BookkEntry objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     * @throws PropelException
     */
    public function getBookkEntriesRelatedByFileLev3Id($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev3IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev3Id || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev3Id) {
                // return empty collection
                $this->initBookkEntriesRelatedByFileLev3Id();
            } else {
                $collBookkEntriesRelatedByFileLev3Id = BookkEntryQuery::create(null, $criteria)
                    ->filterByFileLev3($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collBookkEntriesRelatedByFileLev3IdPartial && count($collBookkEntriesRelatedByFileLev3Id)) {
                      $this->initBookkEntriesRelatedByFileLev3Id(false);

                      foreach ($collBookkEntriesRelatedByFileLev3Id as $obj) {
                        if (false == $this->collBookkEntriesRelatedByFileLev3Id->contains($obj)) {
                          $this->collBookkEntriesRelatedByFileLev3Id->append($obj);
                        }
                      }

                      $this->collBookkEntriesRelatedByFileLev3IdPartial = true;
                    }

                    $collBookkEntriesRelatedByFileLev3Id->getInternalIterator()->rewind();

                    return $collBookkEntriesRelatedByFileLev3Id;
                }

                if ($partial && $this->collBookkEntriesRelatedByFileLev3Id) {
                    foreach ($this->collBookkEntriesRelatedByFileLev3Id as $obj) {
                        if ($obj->isNew()) {
                            $collBookkEntriesRelatedByFileLev3Id[] = $obj;
                        }
                    }
                }

                $this->collBookkEntriesRelatedByFileLev3Id = $collBookkEntriesRelatedByFileLev3Id;
                $this->collBookkEntriesRelatedByFileLev3IdPartial = false;
            }
        }

        return $this->collBookkEntriesRelatedByFileLev3Id;
    }

    /**
     * Sets a collection of BookkEntryRelatedByFileLev3Id objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $bookkEntriesRelatedByFileLev3Id A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setBookkEntriesRelatedByFileLev3Id(PropelCollection $bookkEntriesRelatedByFileLev3Id, PropelPDO $con = null)
    {
        $bookkEntriesRelatedByFileLev3IdToDelete = $this->getBookkEntriesRelatedByFileLev3Id(new Criteria(), $con)->diff($bookkEntriesRelatedByFileLev3Id);


        $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion = $bookkEntriesRelatedByFileLev3IdToDelete;

        foreach ($bookkEntriesRelatedByFileLev3IdToDelete as $bookkEntryRelatedByFileLev3IdRemoved) {
            $bookkEntryRelatedByFileLev3IdRemoved->setFileLev3(null);
        }

        $this->collBookkEntriesRelatedByFileLev3Id = null;
        foreach ($bookkEntriesRelatedByFileLev3Id as $bookkEntryRelatedByFileLev3Id) {
            $this->addBookkEntryRelatedByFileLev3Id($bookkEntryRelatedByFileLev3Id);
        }

        $this->collBookkEntriesRelatedByFileLev3Id = $bookkEntriesRelatedByFileLev3Id;
        $this->collBookkEntriesRelatedByFileLev3IdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related BookkEntry objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related BookkEntry objects.
     * @throws PropelException
     */
    public function countBookkEntriesRelatedByFileLev3Id(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collBookkEntriesRelatedByFileLev3IdPartial && !$this->isNew();
        if (null === $this->collBookkEntriesRelatedByFileLev3Id || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collBookkEntriesRelatedByFileLev3Id) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getBookkEntriesRelatedByFileLev3Id());
            }
            $query = BookkEntryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFileLev3($this)
                ->count($con);
        }

        return count($this->collBookkEntriesRelatedByFileLev3Id);
    }

    /**
     * Method called to associate a BookkEntry object to this object
     * through the BookkEntry foreign key attribute.
     *
     * @param    BookkEntry $l BookkEntry
     * @return File The current object (for fluent API support)
     */
    public function addBookkEntryRelatedByFileLev3Id(BookkEntry $l)
    {
        if ($this->collBookkEntriesRelatedByFileLev3Id === null) {
            $this->initBookkEntriesRelatedByFileLev3Id();
            $this->collBookkEntriesRelatedByFileLev3IdPartial = true;
        }

        if (!in_array($l, $this->collBookkEntriesRelatedByFileLev3Id->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddBookkEntryRelatedByFileLev3Id($l);

            if ($this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion and $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion->contains($l)) {
                $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion->remove($this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	BookkEntryRelatedByFileLev3Id $bookkEntryRelatedByFileLev3Id The bookkEntryRelatedByFileLev3Id object to add.
     */
    protected function doAddBookkEntryRelatedByFileLev3Id($bookkEntryRelatedByFileLev3Id)
    {
        $this->collBookkEntriesRelatedByFileLev3Id[]= $bookkEntryRelatedByFileLev3Id;
        $bookkEntryRelatedByFileLev3Id->setFileLev3($this);
    }

    /**
     * @param	BookkEntryRelatedByFileLev3Id $bookkEntryRelatedByFileLev3Id The bookkEntryRelatedByFileLev3Id object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeBookkEntryRelatedByFileLev3Id($bookkEntryRelatedByFileLev3Id)
    {
        if ($this->getBookkEntriesRelatedByFileLev3Id()->contains($bookkEntryRelatedByFileLev3Id)) {
            $this->collBookkEntriesRelatedByFileLev3Id->remove($this->collBookkEntriesRelatedByFileLev3Id->search($bookkEntryRelatedByFileLev3Id));
            if (null === $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion) {
                $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion = clone $this->collBookkEntriesRelatedByFileLev3Id;
                $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion->clear();
            }
            $this->bookkEntriesRelatedByFileLev3IdScheduledForDeletion[]= $bookkEntryRelatedByFileLev3Id;
            $bookkEntryRelatedByFileLev3Id->setFileLev3(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev3Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev3IdJoinBookk($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Bookk', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev3Id($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related BookkEntriesRelatedByFileLev3Id from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|BookkEntry[] List of BookkEntry objects
     */
    public function getBookkEntriesRelatedByFileLev3IdJoinAccount($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = BookkEntryQuery::create(null, $criteria);
        $query->joinWith('Account', $join_behavior);

        return $this->getBookkEntriesRelatedByFileLev3Id($query, $con);
    }

    /**
     * Clears out the collProjects collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addProjects()
     */
    public function clearProjects()
    {
        $this->collProjects = null; // important to set this to null since that means it is uninitialized
        $this->collProjectsPartial = null;

        return $this;
    }

    /**
     * reset is the collProjects collection loaded partially
     *
     * @return void
     */
    public function resetPartialProjects($v = true)
    {
        $this->collProjectsPartial = $v;
    }

    /**
     * Initializes the collProjects collection.
     *
     * By default this just sets the collProjects collection to an empty array (like clearcollProjects());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initProjects($overrideExisting = true)
    {
        if (null !== $this->collProjects && !$overrideExisting) {
            return;
        }
        $this->collProjects = new PropelObjectCollection();
        $this->collProjects->setModel('Project');
    }

    /**
     * Gets an array of Project objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Project[] List of Project objects
     * @throws PropelException
     */
    public function getProjects($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collProjectsPartial && !$this->isNew();
        if (null === $this->collProjects || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collProjects) {
                // return empty collection
                $this->initProjects();
            } else {
                $collProjects = ProjectQuery::create(null, $criteria)
                    ->filterByFile($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collProjectsPartial && count($collProjects)) {
                      $this->initProjects(false);

                      foreach ($collProjects as $obj) {
                        if (false == $this->collProjects->contains($obj)) {
                          $this->collProjects->append($obj);
                        }
                      }

                      $this->collProjectsPartial = true;
                    }

                    $collProjects->getInternalIterator()->rewind();

                    return $collProjects;
                }

                if ($partial && $this->collProjects) {
                    foreach ($this->collProjects as $obj) {
                        if ($obj->isNew()) {
                            $collProjects[] = $obj;
                        }
                    }
                }

                $this->collProjects = $collProjects;
                $this->collProjectsPartial = false;
            }
        }

        return $this->collProjects;
    }

    /**
     * Sets a collection of Project objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $projects A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setProjects(PropelCollection $projects, PropelPDO $con = null)
    {
        $projectsToDelete = $this->getProjects(new Criteria(), $con)->diff($projects);


        $this->projectsScheduledForDeletion = $projectsToDelete;

        foreach ($projectsToDelete as $projectRemoved) {
            $projectRemoved->setFile(null);
        }

        $this->collProjects = null;
        foreach ($projects as $project) {
            $this->addProject($project);
        }

        $this->collProjects = $projects;
        $this->collProjectsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Project objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Project objects.
     * @throws PropelException
     */
    public function countProjects(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collProjectsPartial && !$this->isNew();
        if (null === $this->collProjects || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collProjects) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getProjects());
            }
            $query = ProjectQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFile($this)
                ->count($con);
        }

        return count($this->collProjects);
    }

    /**
     * Method called to associate a Project object to this object
     * through the Project foreign key attribute.
     *
     * @param    Project $l Project
     * @return File The current object (for fluent API support)
     */
    public function addProject(Project $l)
    {
        if ($this->collProjects === null) {
            $this->initProjects();
            $this->collProjectsPartial = true;
        }

        if (!in_array($l, $this->collProjects->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddProject($l);

            if ($this->projectsScheduledForDeletion and $this->projectsScheduledForDeletion->contains($l)) {
                $this->projectsScheduledForDeletion->remove($this->projectsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Project $project The project object to add.
     */
    protected function doAddProject($project)
    {
        $this->collProjects[]= $project;
        $project->setFile($this);
    }

    /**
     * @param	Project $project The project object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeProject($project)
    {
        if ($this->getProjects()->contains($project)) {
            $this->collProjects->remove($this->collProjects->search($project));
            if (null === $this->projectsScheduledForDeletion) {
                $this->projectsScheduledForDeletion = clone $this->collProjects;
                $this->projectsScheduledForDeletion->clear();
            }
            $this->projectsScheduledForDeletion[]= $project;
            $project->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinYear($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('Year', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinCostFileCat($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostFileCat', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinIncomeAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('IncomeAcc', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinCostAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('CostAcc', $join_behavior);

        return $this->getProjects($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Projects from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Project[] List of Project objects
     */
    public function getProjectsJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = ProjectQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getProjects($query, $con);
    }

    /**
     * Clears out the collIncomes collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addIncomes()
     */
    public function clearIncomes()
    {
        $this->collIncomes = null; // important to set this to null since that means it is uninitialized
        $this->collIncomesPartial = null;

        return $this;
    }

    /**
     * reset is the collIncomes collection loaded partially
     *
     * @return void
     */
    public function resetPartialIncomes($v = true)
    {
        $this->collIncomesPartial = $v;
    }

    /**
     * Initializes the collIncomes collection.
     *
     * By default this just sets the collIncomes collection to an empty array (like clearcollIncomes());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initIncomes($overrideExisting = true)
    {
        if (null !== $this->collIncomes && !$overrideExisting) {
            return;
        }
        $this->collIncomes = new PropelObjectCollection();
        $this->collIncomes->setModel('Income');
    }

    /**
     * Gets an array of Income objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Income[] List of Income objects
     * @throws PropelException
     */
    public function getIncomes($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collIncomesPartial && !$this->isNew();
        if (null === $this->collIncomes || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collIncomes) {
                // return empty collection
                $this->initIncomes();
            } else {
                $collIncomes = IncomeQuery::create(null, $criteria)
                    ->filterByFile($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collIncomesPartial && count($collIncomes)) {
                      $this->initIncomes(false);

                      foreach ($collIncomes as $obj) {
                        if (false == $this->collIncomes->contains($obj)) {
                          $this->collIncomes->append($obj);
                        }
                      }

                      $this->collIncomesPartial = true;
                    }

                    $collIncomes->getInternalIterator()->rewind();

                    return $collIncomes;
                }

                if ($partial && $this->collIncomes) {
                    foreach ($this->collIncomes as $obj) {
                        if ($obj->isNew()) {
                            $collIncomes[] = $obj;
                        }
                    }
                }

                $this->collIncomes = $collIncomes;
                $this->collIncomesPartial = false;
            }
        }

        return $this->collIncomes;
    }

    /**
     * Sets a collection of Income objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $incomes A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setIncomes(PropelCollection $incomes, PropelPDO $con = null)
    {
        $incomesToDelete = $this->getIncomes(new Criteria(), $con)->diff($incomes);


        $this->incomesScheduledForDeletion = $incomesToDelete;

        foreach ($incomesToDelete as $incomeRemoved) {
            $incomeRemoved->setFile(null);
        }

        $this->collIncomes = null;
        foreach ($incomes as $income) {
            $this->addIncome($income);
        }

        $this->collIncomes = $incomes;
        $this->collIncomesPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Income objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Income objects.
     * @throws PropelException
     */
    public function countIncomes(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collIncomesPartial && !$this->isNew();
        if (null === $this->collIncomes || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collIncomes) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getIncomes());
            }
            $query = IncomeQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFile($this)
                ->count($con);
        }

        return count($this->collIncomes);
    }

    /**
     * Method called to associate a Income object to this object
     * through the Income foreign key attribute.
     *
     * @param    Income $l Income
     * @return File The current object (for fluent API support)
     */
    public function addIncome(Income $l)
    {
        if ($this->collIncomes === null) {
            $this->initIncomes();
            $this->collIncomesPartial = true;
        }

        if (!in_array($l, $this->collIncomes->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddIncome($l);

            if ($this->incomesScheduledForDeletion and $this->incomesScheduledForDeletion->contains($l)) {
                $this->incomesScheduledForDeletion->remove($this->incomesScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Income $income The income object to add.
     */
    protected function doAddIncome($income)
    {
        $this->collIncomes[]= $income;
        $income->setFile($this);
    }

    /**
     * @param	Income $income The income object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeIncome($income)
    {
        if ($this->getIncomes()->contains($income)) {
            $this->collIncomes->remove($this->collIncomes->search($income));
            if (null === $this->incomesScheduledForDeletion) {
                $this->incomesScheduledForDeletion = clone $this->collIncomes;
                $this->incomesScheduledForDeletion->clear();
            }
            $this->incomesScheduledForDeletion[]= $income;
            $income->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getIncomes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getIncomes($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Incomes from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Income[] List of Income objects
     */
    public function getIncomesJoinIncomeAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = IncomeQuery::create(null, $criteria);
        $query->joinWith('IncomeAcc', $join_behavior);

        return $this->getIncomes($query, $con);
    }

    /**
     * Clears out the collCosts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
     * @see        addCosts()
     */
    public function clearCosts()
    {
        $this->collCosts = null; // important to set this to null since that means it is uninitialized
        $this->collCostsPartial = null;

        return $this;
    }

    /**
     * reset is the collCosts collection loaded partially
     *
     * @return void
     */
    public function resetPartialCosts($v = true)
    {
        $this->collCostsPartial = $v;
    }

    /**
     * Initializes the collCosts collection.
     *
     * By default this just sets the collCosts collection to an empty array (like clearcollCosts());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCosts($overrideExisting = true)
    {
        if (null !== $this->collCosts && !$overrideExisting) {
            return;
        }
        $this->collCosts = new PropelObjectCollection();
        $this->collCosts->setModel('Cost');
    }

    /**
     * Gets an array of Cost objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this File is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @return PropelObjectCollection|Cost[] List of Cost objects
     * @throws PropelException
     */
    public function getCosts($criteria = null, PropelPDO $con = null)
    {
        $partial = $this->collCostsPartial && !$this->isNew();
        if (null === $this->collCosts || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCosts) {
                // return empty collection
                $this->initCosts();
            } else {
                $collCosts = CostQuery::create(null, $criteria)
                    ->filterByFile($this)
                    ->find($con);
                if (null !== $criteria) {
                    if (false !== $this->collCostsPartial && count($collCosts)) {
                      $this->initCosts(false);

                      foreach ($collCosts as $obj) {
                        if (false == $this->collCosts->contains($obj)) {
                          $this->collCosts->append($obj);
                        }
                      }

                      $this->collCostsPartial = true;
                    }

                    $collCosts->getInternalIterator()->rewind();

                    return $collCosts;
                }

                if ($partial && $this->collCosts) {
                    foreach ($this->collCosts as $obj) {
                        if ($obj->isNew()) {
                            $collCosts[] = $obj;
                        }
                    }
                }

                $this->collCosts = $collCosts;
                $this->collCostsPartial = false;
            }
        }

        return $this->collCosts;
    }

    /**
     * Sets a collection of Cost objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param PropelCollection $costs A Propel collection.
     * @param PropelPDO $con Optional connection object
     * @return File The current object (for fluent API support)
     */
    public function setCosts(PropelCollection $costs, PropelPDO $con = null)
    {
        $costsToDelete = $this->getCosts(new Criteria(), $con)->diff($costs);


        $this->costsScheduledForDeletion = $costsToDelete;

        foreach ($costsToDelete as $costRemoved) {
            $costRemoved->setFile(null);
        }

        $this->collCosts = null;
        foreach ($costs as $cost) {
            $this->addCost($cost);
        }

        $this->collCosts = $costs;
        $this->collCostsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Cost objects.
     *
     * @param Criteria $criteria
     * @param boolean $distinct
     * @param PropelPDO $con
     * @return int             Count of related Cost objects.
     * @throws PropelException
     */
    public function countCosts(Criteria $criteria = null, $distinct = false, PropelPDO $con = null)
    {
        $partial = $this->collCostsPartial && !$this->isNew();
        if (null === $this->collCosts || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCosts) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCosts());
            }
            $query = CostQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByFile($this)
                ->count($con);
        }

        return count($this->collCosts);
    }

    /**
     * Method called to associate a Cost object to this object
     * through the Cost foreign key attribute.
     *
     * @param    Cost $l Cost
     * @return File The current object (for fluent API support)
     */
    public function addCost(Cost $l)
    {
        if ($this->collCosts === null) {
            $this->initCosts();
            $this->collCostsPartial = true;
        }

        if (!in_array($l, $this->collCosts->getArrayCopy(), true)) { // only add it if the **same** object is not already associated
            $this->doAddCost($l);

            if ($this->costsScheduledForDeletion and $this->costsScheduledForDeletion->contains($l)) {
                $this->costsScheduledForDeletion->remove($this->costsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param	Cost $cost The cost object to add.
     */
    protected function doAddCost($cost)
    {
        $this->collCosts[]= $cost;
        $cost->setFile($this);
    }

    /**
     * @param	Cost $cost The cost object to remove.
     * @return File The current object (for fluent API support)
     */
    public function removeCost($cost)
    {
        if ($this->getCosts()->contains($cost)) {
            $this->collCosts->remove($this->collCosts->search($cost));
            if (null === $this->costsScheduledForDeletion) {
                $this->costsScheduledForDeletion = clone $this->collCosts;
                $this->costsScheduledForDeletion->clear();
            }
            $this->costsScheduledForDeletion[]= $cost;
            $cost->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinProject($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('Project', $join_behavior);

        return $this->getCosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinBankAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('BankAcc', $join_behavior);

        return $this->getCosts($query, $con);
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Costs from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
     *
     * @param Criteria $criteria optional Criteria object to narrow the query
     * @param PropelPDO $con optional connection object
     * @param string $join_behavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return PropelObjectCollection|Cost[] List of Cost objects
     */
    public function getCostsJoinCostAcc($criteria = null, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $query = CostQuery::create(null, $criteria);
        $query->joinWith('CostAcc', $join_behavior);

        return $this->getCosts($query, $con);
    }

    /**
     * Clears out the collContracts collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return File The current object (for fluent API support)
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
     * If this File is new, it will return
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
                    ->filterByFile($this)
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
     * @return File The current object (for fluent API support)
     */
    public function setContracts(PropelCollection $contracts, PropelPDO $con = null)
    {
        $contractsToDelete = $this->getContracts(new Criteria(), $con)->diff($contracts);


        $this->contractsScheduledForDeletion = $contractsToDelete;

        foreach ($contractsToDelete as $contractRemoved) {
            $contractRemoved->setFile(null);
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
                ->filterByFile($this)
                ->count($con);
        }

        return count($this->collContracts);
    }

    /**
     * Method called to associate a Contract object to this object
     * through the Contract foreign key attribute.
     *
     * @param    Contract $l Contract
     * @return File The current object (for fluent API support)
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
        $contract->setFile($this);
    }

    /**
     * @param	Contract $contract The contract object to remove.
     * @return File The current object (for fluent API support)
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
            $contract->setFile(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
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
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
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
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
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
     * Otherwise if this File is new, it will return
     * an empty collection; or if this File has previously
     * been saved, it will retrieve related Contracts from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in File.
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
        $this->acc_no = null;
        $this->file_cat_id = null;
        $this->sub_file_id = null;
        $this->first_name = null;
        $this->second_name = null;
        $this->last_name = null;
        $this->maiden_name = null;
        $this->father_name = null;
        $this->mother_name = null;
        $this->birth_date = null;
        $this->birth_place = null;
        $this->pesel = null;
        $this->passport = null;
        $this->nip = null;
        $this->profession = null;
        $this->street = null;
        $this->house = null;
        $this->flat = null;
        $this->code = null;
        $this->city = null;
        $this->district2 = null;
        $this->district = null;
        $this->province = null;
        $this->country = null;
        $this->post_office = null;
        $this->bank_account = null;
        $this->bank_name = null;
        $this->phone = null;
        $this->email = null;
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
            if ($this->collFiles) {
                foreach ($this->collFiles as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collDocs) {
                foreach ($this->collDocs as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookkEntriesRelatedByFileLev1Id) {
                foreach ($this->collBookkEntriesRelatedByFileLev1Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookkEntriesRelatedByFileLev2Id) {
                foreach ($this->collBookkEntriesRelatedByFileLev2Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collBookkEntriesRelatedByFileLev3Id) {
                foreach ($this->collBookkEntriesRelatedByFileLev3Id as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collProjects) {
                foreach ($this->collProjects as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collIncomes) {
                foreach ($this->collIncomes as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCosts) {
                foreach ($this->collCosts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collContracts) {
                foreach ($this->collContracts as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->aFileCat instanceof Persistent) {
              $this->aFileCat->clearAllReferences($deep);
            }
            if ($this->aSubFile instanceof Persistent) {
              $this->aSubFile->clearAllReferences($deep);
            }

            $this->alreadyInClearAllReferencesDeep = false;
        } // if ($deep)

        if ($this->collFiles instanceof PropelCollection) {
            $this->collFiles->clearIterator();
        }
        $this->collFiles = null;
        if ($this->collDocs instanceof PropelCollection) {
            $this->collDocs->clearIterator();
        }
        $this->collDocs = null;
        if ($this->collBookkEntriesRelatedByFileLev1Id instanceof PropelCollection) {
            $this->collBookkEntriesRelatedByFileLev1Id->clearIterator();
        }
        $this->collBookkEntriesRelatedByFileLev1Id = null;
        if ($this->collBookkEntriesRelatedByFileLev2Id instanceof PropelCollection) {
            $this->collBookkEntriesRelatedByFileLev2Id->clearIterator();
        }
        $this->collBookkEntriesRelatedByFileLev2Id = null;
        if ($this->collBookkEntriesRelatedByFileLev3Id instanceof PropelCollection) {
            $this->collBookkEntriesRelatedByFileLev3Id->clearIterator();
        }
        $this->collBookkEntriesRelatedByFileLev3Id = null;
        if ($this->collProjects instanceof PropelCollection) {
            $this->collProjects->clearIterator();
        }
        $this->collProjects = null;
        if ($this->collIncomes instanceof PropelCollection) {
            $this->collIncomes->clearIterator();
        }
        $this->collIncomes = null;
        if ($this->collCosts instanceof PropelCollection) {
            $this->collCosts->clearIterator();
        }
        $this->collCosts = null;
        if ($this->collContracts instanceof PropelCollection) {
            $this->collContracts->clearIterator();
        }
        $this->collContracts = null;
        $this->aFileCat = null;
        $this->aSubFile = null;
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

}
