<?php

namespace AppBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use AppBundle\Model\File;
use AppBundle\Model\FileCatPeer;
use AppBundle\Model\FilePeer;
use AppBundle\Model\map\FileTableMap;

abstract class BaseFilePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'file';

    /** the related Propel class for this table */
    const OM_CLASS = 'AppBundle\\Model\\File';

    /** the related TableMap class for this table */
    const TM_CLASS = 'AppBundle\\Model\\map\\FileTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 35;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 35;

    /** the column name for the id field */
    const ID = 'file.id';

    /** the column name for the name field */
    const NAME = 'file.name';

    /** the column name for the acc_no field */
    const ACC_NO = 'file.acc_no';

    /** the column name for the file_cat_id field */
    const FILE_CAT_ID = 'file.file_cat_id';

    /** the column name for the sub_file_id field */
    const SUB_FILE_ID = 'file.sub_file_id';

    /** the column name for the first_name field */
    const FIRST_NAME = 'file.first_name';

    /** the column name for the second_name field */
    const SECOND_NAME = 'file.second_name';

    /** the column name for the last_name field */
    const LAST_NAME = 'file.last_name';

    /** the column name for the maiden_name field */
    const MAIDEN_NAME = 'file.maiden_name';

    /** the column name for the father_name field */
    const FATHER_NAME = 'file.father_name';

    /** the column name for the mother_name field */
    const MOTHER_NAME = 'file.mother_name';

    /** the column name for the birth_date field */
    const BIRTH_DATE = 'file.birth_date';

    /** the column name for the birth_place field */
    const BIRTH_PLACE = 'file.birth_place';

    /** the column name for the PESEL field */
    const PESEL = 'file.PESEL';

    /** the column name for the ID_type field */
    const ID_TYPE = 'file.ID_type';

    /** the column name for the ID_no field */
    const ID_NO = 'file.ID_no';

    /** the column name for the ID_country field */
    const ID_COUNTRY = 'file.ID_country';

    /** the column name for the NIP field */
    const NIP = 'file.NIP';

    /** the column name for the profession field */
    const PROFESSION = 'file.profession';

    /** the column name for the street field */
    const STREET = 'file.street';

    /** the column name for the house field */
    const HOUSE = 'file.house';

    /** the column name for the flat field */
    const FLAT = 'file.flat';

    /** the column name for the code field */
    const CODE = 'file.code';

    /** the column name for the city field */
    const CITY = 'file.city';

    /** the column name for the district2 field */
    const DISTRICT2 = 'file.district2';

    /** the column name for the district field */
    const DISTRICT = 'file.district';

    /** the column name for the province field */
    const PROVINCE = 'file.province';

    /** the column name for the country field */
    const COUNTRY = 'file.country';

    /** the column name for the post_office field */
    const POST_OFFICE = 'file.post_office';

    /** the column name for the bank_tax_account field */
    const BANK_TAX_ACCOUNT = 'file.bank_tax_account';    
    
    /** the column name for the bank_account field */
    const BANK_ACCOUNT = 'file.bank_account';

    /** the column name for the bank_IBAN field */
    const BANK_IBAN = 'file.bank_IBAN';

    /** the column name for the bank_SWIFT field */
    const BANK_SWIFT = 'file.bank_SWIFT';

    /** the column name for the bank_name field */
    const BANK_NAME = 'file.bank_name';

    /** the column name for the phone field */
    const PHONE = 'file.phone';

    /** the column name for the email field */
    const EMAIL = 'file.email';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of File objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array File[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. FilePeer::$fieldNames[FilePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Name', 'AccNo', 'FileCatId', 'SubFileId', 'FirstName', 'SecondName', 'LastName', 'MaidenName', 'FatherName', 'MotherName', 'BirthDate', 'BirthPlace', 'Pesel', 'IdType', 'IdNo', 'IdCountry', 'Nip', 'Profession', 'Street', 'House', 'Flat', 'Code', 'City', 'District2', 'District', 'Province', 'Country', 'PostOffice', 'BankTaxAccount', 'BankAccount', 'BankIban', 'BankSwift', 'BankName', 'Phone', 'Email', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'name', 'accNo', 'fileCatId', 'subFileId', 'firstName', 'secondName', 'lastName', 'maidenName', 'fatherName', 'motherName', 'birthDate', 'birthPlace', 'pesel', 'idType', 'idNo', 'idCountry', 'nip', 'profession', 'street', 'house', 'flat', 'code', 'city', 'district2', 'district', 'province', 'country', 'postOffice', 'bankTaxAccount', 'bankAccount', 'bankIban', 'bankSwift', 'bankName', 'phone', 'email', ),
        BasePeer::TYPE_COLNAME => array (FilePeer::ID, FilePeer::NAME, FilePeer::ACC_NO, FilePeer::FILE_CAT_ID, FilePeer::SUB_FILE_ID, FilePeer::FIRST_NAME, FilePeer::SECOND_NAME, FilePeer::LAST_NAME, FilePeer::MAIDEN_NAME, FilePeer::FATHER_NAME, FilePeer::MOTHER_NAME, FilePeer::BIRTH_DATE, FilePeer::BIRTH_PLACE, FilePeer::PESEL, FilePeer::ID_TYPE, FilePeer::ID_NO, FilePeer::ID_COUNTRY, FilePeer::NIP, FilePeer::PROFESSION, FilePeer::STREET, FilePeer::HOUSE, FilePeer::FLAT, FilePeer::CODE, FilePeer::CITY, FilePeer::DISTRICT2, FilePeer::DISTRICT, FilePeer::PROVINCE, FilePeer::COUNTRY, FilePeer::POST_OFFICE, FilePeer::BANK_TAX_ACCOUNT, FilePeer::BANK_ACCOUNT, FilePeer::BANK_IBAN, FilePeer::BANK_SWIFT, FilePeer::BANK_NAME, FilePeer::PHONE, FilePeer::EMAIL, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'NAME', 'ACC_NO', 'FILE_CAT_ID', 'SUB_FILE_ID', 'FIRST_NAME', 'SECOND_NAME', 'LAST_NAME', 'MAIDEN_NAME', 'FATHER_NAME', 'MOTHER_NAME', 'BIRTH_DATE', 'BIRTH_PLACE', 'PESEL', 'ID_TYPE', 'ID_NO', 'ID_COUNTRY', 'NIP', 'PROFESSION', 'STREET', 'HOUSE', 'FLAT', 'CODE', 'CITY', 'DISTRICT2', 'DISTRICT', 'PROVINCE', 'COUNTRY', 'POST_OFFICE', 'BANK_TAX_ACCOUNT', 'BANK_ACCOUNT', 'BANK_IBAN', 'BANK_SWIFT', 'BANK_NAME', 'PHONE', 'EMAIL', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'name', 'acc_no', 'file_cat_id', 'sub_file_id', 'first_name', 'second_name', 'last_name', 'maiden_name', 'father_name', 'mother_name', 'birth_date', 'birth_place', 'PESEL', 'ID_type', 'ID_no', 'ID_country', 'NIP', 'profession', 'street', 'house', 'flat', 'code', 'city', 'district2', 'district', 'province', 'country', 'post_office', 'bank_tax_account', 'bank_account', 'bank_IBAN', 'bank_SWIFT', 'bank_name', 'phone', 'email', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35,)
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. FilePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Name' => 1, 'AccNo' => 2, 'FileCatId' => 3, 'SubFileId' => 4, 'FirstName' => 5, 'SecondName' => 6, 'LastName' => 7, 'MaidenName' => 8, 'FatherName' => 9, 'MotherName' => 10, 'BirthDate' => 11, 'BirthPlace' => 12, 'Pesel' => 13, 'IdType' => 14, 'IdNo' => 15, 'IdCountry' => 16, 'Nip' => 17, 'Profession' => 18, 'Street' => 19, 'House' => 20, 'Flat' => 21, 'Code' => 22, 'City' => 23, 'District2' => 24, 'District' => 25, 'Province' => 26, 'Country' => 27, 'PostOffice' => 28, 'BankTaxAccount' => 29, 'BankAccount' => 30, 'BankIban' => 31, 'BankSwift' => 32, 'BankName' => 33, 'Phone' => 34, 'Email' => 35, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'name' => 1, 'accNo' => 2, 'fileCatId' => 3, 'subFileId' => 4, 'firstName' => 5, 'secondName' => 6, 'lastName' => 7, 'maidenName' => 8, 'fatherName' => 9, 'motherName' => 10, 'birthDate' => 11, 'birthPlace' => 12, 'pesel' => 13, 'idType' => 14, 'idNo' => 15, 'idCountry' => 16, 'nip' => 17, 'profession' => 18, 'street' => 19, 'house' => 20, 'flat' => 21, 'code' => 22, 'city' => 23, 'district2' => 24, 'district' => 25, 'province' => 26, 'country' => 27, 'postOffice' => 28, 'bankTaxAccount' => 29, 'bankAccount' => 30, 'bankIban' => 31, 'bankSwift' => 32, 'bankName' => 33, 'phone' => 34, 'email' => 35, ),
        BasePeer::TYPE_COLNAME => array (FilePeer::ID => 0, FilePeer::NAME => 1, FilePeer::ACC_NO => 2, FilePeer::FILE_CAT_ID => 3, FilePeer::SUB_FILE_ID => 4, FilePeer::FIRST_NAME => 5, FilePeer::SECOND_NAME => 6, FilePeer::LAST_NAME => 7, FilePeer::MAIDEN_NAME => 8, FilePeer::FATHER_NAME => 9, FilePeer::MOTHER_NAME => 10, FilePeer::BIRTH_DATE => 11, FilePeer::BIRTH_PLACE => 12, FilePeer::PESEL => 13, FilePeer::ID_TYPE => 14, FilePeer::ID_NO => 15, FilePeer::ID_COUNTRY => 16, FilePeer::NIP => 17, FilePeer::PROFESSION => 18, FilePeer::STREET => 19, FilePeer::HOUSE => 20, FilePeer::FLAT => 21, FilePeer::CODE => 22, FilePeer::CITY => 23, FilePeer::DISTRICT2 => 24, FilePeer::DISTRICT => 25, FilePeer::PROVINCE => 26, FilePeer::COUNTRY => 27, FilePeer::POST_OFFICE => 28, FilePeer::BANK_TAX_ACCOUNT => 29, FilePeer::BANK_ACCOUNT => 30, FilePeer::BANK_IBAN => 31, FilePeer::BANK_SWIFT => 32, FilePeer::BANK_NAME => 33, FilePeer::PHONE => 34, FilePeer::EMAIL => 35, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'NAME' => 1, 'ACC_NO' => 2, 'FILE_CAT_ID' => 3, 'SUB_FILE_ID' => 4, 'FIRST_NAME' => 5, 'SECOND_NAME' => 6, 'LAST_NAME' => 7, 'MAIDEN_NAME' => 8, 'FATHER_NAME' => 9, 'MOTHER_NAME' => 10, 'BIRTH_DATE' => 11, 'BIRTH_PLACE' => 12, 'PESEL' => 13, 'ID_TYPE' => 14, 'ID_NO' => 15, 'ID_COUNTRY' => 16, 'NIP' => 17, 'PROFESSION' => 18, 'STREET' => 19, 'HOUSE' => 20, 'FLAT' => 21, 'CODE' => 22, 'CITY' => 23, 'DISTRICT2' => 24, 'DISTRICT' => 25, 'PROVINCE' => 26, 'COUNTRY' => 27, 'POST_OFFICE' => 28, 'BANK_TAX_ACCOUNT' => 29, 'BANK_ACCOUNT' => 30, 'BANK_IBAN' => 31, 'BANK_SWIFT' => 32, 'BANK_NAME' => 33, 'PHONE' => 34, 'EMAIL' => 35, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'name' => 1, 'acc_no' => 2, 'file_cat_id' => 3, 'sub_file_id' => 4, 'first_name' => 5, 'second_name' => 6, 'last_name' => 7, 'maiden_name' => 8, 'father_name' => 9, 'mother_name' => 10, 'birth_date' => 11, 'birth_place' => 12, 'PESEL' => 13, 'ID_type' => 14, 'ID_no' => 15, 'ID_country' => 16, 'NIP' => 17, 'profession' => 18, 'street' => 19, 'house' => 20, 'flat' => 21, 'code' => 22, 'city' => 23, 'district2' => 24, 'district' => 25, 'province' => 26, 'country' => 27, 'post_office' => 28, 'bank_tax_account' => 29, 'bank_account' => 30, 'bank_IBAN' => 31, 'bank_SWIFT' => 32, 'bank_name' => 33, 'phone' => 34, 'email' => 35, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35,)
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = FilePeer::getFieldNames($toType);
        $key = isset(FilePeer::$fieldKeys[$fromType][$name]) ? FilePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(FilePeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, FilePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return FilePeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. FilePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(FilePeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(FilePeer::ID);
            $criteria->addSelectColumn(FilePeer::NAME);
            $criteria->addSelectColumn(FilePeer::ACC_NO);
            $criteria->addSelectColumn(FilePeer::FILE_CAT_ID);
            $criteria->addSelectColumn(FilePeer::SUB_FILE_ID);
            $criteria->addSelectColumn(FilePeer::FIRST_NAME);
            $criteria->addSelectColumn(FilePeer::SECOND_NAME);
            $criteria->addSelectColumn(FilePeer::LAST_NAME);
            $criteria->addSelectColumn(FilePeer::MAIDEN_NAME);
            $criteria->addSelectColumn(FilePeer::FATHER_NAME);
            $criteria->addSelectColumn(FilePeer::MOTHER_NAME);
            $criteria->addSelectColumn(FilePeer::BIRTH_DATE);
            $criteria->addSelectColumn(FilePeer::BIRTH_PLACE);
            $criteria->addSelectColumn(FilePeer::PESEL);
            $criteria->addSelectColumn(FilePeer::ID_TYPE);
            $criteria->addSelectColumn(FilePeer::ID_NO);
            $criteria->addSelectColumn(FilePeer::ID_COUNTRY);
            $criteria->addSelectColumn(FilePeer::NIP);
            $criteria->addSelectColumn(FilePeer::PROFESSION);
            $criteria->addSelectColumn(FilePeer::STREET);
            $criteria->addSelectColumn(FilePeer::HOUSE);
            $criteria->addSelectColumn(FilePeer::FLAT);
            $criteria->addSelectColumn(FilePeer::CODE);
            $criteria->addSelectColumn(FilePeer::CITY);
            $criteria->addSelectColumn(FilePeer::DISTRICT2);
            $criteria->addSelectColumn(FilePeer::DISTRICT);
            $criteria->addSelectColumn(FilePeer::PROVINCE);
            $criteria->addSelectColumn(FilePeer::COUNTRY);
            $criteria->addSelectColumn(FilePeer::POST_OFFICE);
            $criteria->addSelectColumn(FilePeer::BANK_TAX_ACCOUNT);
            $criteria->addSelectColumn(FilePeer::BANK_ACCOUNT);
            $criteria->addSelectColumn(FilePeer::BANK_IBAN);
            $criteria->addSelectColumn(FilePeer::BANK_SWIFT);
            $criteria->addSelectColumn(FilePeer::BANK_NAME);
            $criteria->addSelectColumn(FilePeer::PHONE);
            $criteria->addSelectColumn(FilePeer::EMAIL);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.acc_no');
            $criteria->addSelectColumn($alias . '.file_cat_id');
            $criteria->addSelectColumn($alias . '.sub_file_id');
            $criteria->addSelectColumn($alias . '.first_name');
            $criteria->addSelectColumn($alias . '.second_name');
            $criteria->addSelectColumn($alias . '.last_name');
            $criteria->addSelectColumn($alias . '.maiden_name');
            $criteria->addSelectColumn($alias . '.father_name');
            $criteria->addSelectColumn($alias . '.mother_name');
            $criteria->addSelectColumn($alias . '.birth_date');
            $criteria->addSelectColumn($alias . '.birth_place');
            $criteria->addSelectColumn($alias . '.PESEL');
            $criteria->addSelectColumn($alias . '.ID_type');
            $criteria->addSelectColumn($alias . '.ID_no');
            $criteria->addSelectColumn($alias . '.ID_country');
            $criteria->addSelectColumn($alias . '.NIP');
            $criteria->addSelectColumn($alias . '.profession');
            $criteria->addSelectColumn($alias . '.street');
            $criteria->addSelectColumn($alias . '.house');
            $criteria->addSelectColumn($alias . '.flat');
            $criteria->addSelectColumn($alias . '.code');
            $criteria->addSelectColumn($alias . '.city');
            $criteria->addSelectColumn($alias . '.district2');
            $criteria->addSelectColumn($alias . '.district');
            $criteria->addSelectColumn($alias . '.province');
            $criteria->addSelectColumn($alias . '.country');
            $criteria->addSelectColumn($alias . '.post_office');
            $criteria->addSelectColumn($alias . '.bank_tax_account');
            $criteria->addSelectColumn($alias . '.bank_account');
            $criteria->addSelectColumn($alias . '.bank_IBAN');
            $criteria->addSelectColumn($alias . '.bank_SWIFT');
            $criteria->addSelectColumn($alias . '.bank_name');
            $criteria->addSelectColumn($alias . '.phone');
            $criteria->addSelectColumn($alias . '.email');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(FilePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return File
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = FilePeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return FilePeer::populateObjects(FilePeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            FilePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param File $obj A File object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            FilePeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A File object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof File) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or File object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(FilePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return File Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(FilePeer::$instances[$key])) {
                return FilePeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (FilePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        FilePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to file
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = FilePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = FilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = FilePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                FilePeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (File object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = FilePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = FilePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + FilePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = FilePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            FilePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related FileCat table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinFileCat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of File objects pre-filled with their FileCat objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of File objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinFileCat(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FilePeer::DATABASE_NAME);
        }

        FilePeer::addSelectColumns($criteria);
        $startcol = FilePeer::NUM_HYDRATE_COLUMNS;
        FileCatPeer::addSelectColumns($criteria);

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = FilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = FileCatPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FileCatPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    FileCatPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (File) to $obj2 (FileCat)
                $obj2->addFile($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of File objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of File objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FilePeer::DATABASE_NAME);
        }

        FilePeer::addSelectColumns($criteria);
        $startcol2 = FilePeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = FilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined FileCat rows

            $key2 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = FileCatPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = FileCatPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    FileCatPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (File) to the collection in $obj2 (FileCat)
                $obj2->addFile($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related FileCat table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptFileCat(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Returns the number of rows matching criteria, joining the related SubFile table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSubFile(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(FilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            FilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of File objects pre-filled with all related objects except FileCat.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of File objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptFileCat(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FilePeer::DATABASE_NAME);
        }

        FilePeer::addSelectColumns($criteria);
        $startcol2 = FilePeer::NUM_HYDRATE_COLUMNS;


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = FilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of File objects pre-filled with all related objects except SubFile.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of File objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSubFile(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(FilePeer::DATABASE_NAME);
        }

        FilePeer::addSelectColumns($criteria);
        $startcol2 = FilePeer::NUM_HYDRATE_COLUMNS;

        FileCatPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + FileCatPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(FilePeer::FILE_CAT_ID, FileCatPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = FilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = FilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = FilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                FilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined FileCat rows

                $key2 = FileCatPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = FileCatPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = FileCatPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    FileCatPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (File) to the collection in $obj2 (FileCat)
                $obj2->addFile($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(FilePeer::DATABASE_NAME)->getTable(FilePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseFilePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseFilePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \AppBundle\Model\map\FileTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return FilePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a File or Criteria object.
     *
     * @param      mixed $values Criteria or File object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from File object
        }

        if ($criteria->containsKey(FilePeer::ID) && $criteria->keyContainsValue(FilePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.FilePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a File or Criteria object.
     *
     * @param      mixed $values Criteria or File object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(FilePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(FilePeer::ID);
            $value = $criteria->remove(FilePeer::ID);
            if ($value) {
                $selectCriteria->add(FilePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(FilePeer::TABLE_NAME);
            }

        } else { // $values is File object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the file table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(FilePeer::TABLE_NAME, $con, FilePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            FilePeer::clearInstancePool();
            FilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a File or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or File object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            FilePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof File) { // it's a model object
            // invalidate the cache for this single object
            FilePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(FilePeer::DATABASE_NAME);
            $criteria->add(FilePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                FilePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(FilePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            FilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given File object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param File $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(FilePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(FilePeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(FilePeer::DATABASE_NAME, FilePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return File
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = FilePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(FilePeer::DATABASE_NAME);
        $criteria->add(FilePeer::ID, $pk);

        $v = FilePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return File[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(FilePeer::DATABASE_NAME);
            $criteria->add(FilePeer::ID, $pks, Criteria::IN);
            $objs = FilePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BaseFilePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseFilePeer::buildTableMap();

