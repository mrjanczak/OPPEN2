<?php

namespace AppBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use AppBundle\Model\BookkEntry;
use AppBundle\Model\Contract;
use AppBundle\Model\Cost;
use AppBundle\Model\Doc;
use AppBundle\Model\File;
use AppBundle\Model\FileCat;
use AppBundle\Model\FilePeer;
use AppBundle\Model\FileQuery;
use AppBundle\Model\Income;
use AppBundle\Model\Project;

/**
 * @method FileQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FileQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method FileQuery orderByAccNo($order = Criteria::ASC) Order by the acc_no column
 * @method FileQuery orderByFileCatId($order = Criteria::ASC) Order by the file_cat_id column
 * @method FileQuery orderBySubFileId($order = Criteria::ASC) Order by the sub_file_id column
 * @method FileQuery orderByFirstName($order = Criteria::ASC) Order by the first_name column
 * @method FileQuery orderBySecondName($order = Criteria::ASC) Order by the second_name column
 * @method FileQuery orderByLastName($order = Criteria::ASC) Order by the last_name column
 * @method FileQuery orderByMaidenName($order = Criteria::ASC) Order by the maiden_name column
 * @method FileQuery orderByFatherName($order = Criteria::ASC) Order by the father_name column
 * @method FileQuery orderByMotherName($order = Criteria::ASC) Order by the mother_name column
 * @method FileQuery orderByBirthDate($order = Criteria::ASC) Order by the birth_date column
 * @method FileQuery orderByBirthPlace($order = Criteria::ASC) Order by the birth_place column
 * @method FileQuery orderByPesel($order = Criteria::ASC) Order by the PESEL column
 * @method FileQuery orderByIdType($order = Criteria::ASC) Order by the ID_type column
 * @method FileQuery orderByIdNo($order = Criteria::ASC) Order by the ID_no column
 * @method FileQuery orderByIdCountry($order = Criteria::ASC) Order by the ID_country column
 * @method FileQuery orderByNip($order = Criteria::ASC) Order by the NIP column
 * @method FileQuery orderByProfession($order = Criteria::ASC) Order by the profession column
 * @method FileQuery orderByStreet($order = Criteria::ASC) Order by the street column
 * @method FileQuery orderByHouse($order = Criteria::ASC) Order by the house column
 * @method FileQuery orderByFlat($order = Criteria::ASC) Order by the flat column
 * @method FileQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method FileQuery orderByCity($order = Criteria::ASC) Order by the city column
 * @method FileQuery orderByDistrict2($order = Criteria::ASC) Order by the district2 column
 * @method FileQuery orderByDistrict($order = Criteria::ASC) Order by the district column
 * @method FileQuery orderByProvince($order = Criteria::ASC) Order by the province column
 * @method FileQuery orderByCountry($order = Criteria::ASC) Order by the country column
 * @method FileQuery orderByPostOffice($order = Criteria::ASC) Order by the post_office column
 * @method FileQuery orderByBankTaxAccount($order = Criteria::ASC) Order by the bank_tax_account column
 * @method FileQuery orderByBankAccount($order = Criteria::ASC) Order by the bank_account column
 * @method FileQuery orderByBankIban($order = Criteria::ASC) Order by the bank_IBAN column
 * @method FileQuery orderByBankSwift($order = Criteria::ASC) Order by the bank_SWIFT column
 * @method FileQuery orderByBankName($order = Criteria::ASC) Order by the bank_name column
 * @method FileQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method FileQuery orderByEmail($order = Criteria::ASC) Order by the email column
 *
 * @method FileQuery groupById() Group by the id column
 * @method FileQuery groupByName() Group by the name column
 * @method FileQuery groupByAccNo() Group by the acc_no column
 * @method FileQuery groupByFileCatId() Group by the file_cat_id column
 * @method FileQuery groupBySubFileId() Group by the sub_file_id column
 * @method FileQuery groupByFirstName() Group by the first_name column
 * @method FileQuery groupBySecondName() Group by the second_name column
 * @method FileQuery groupByLastName() Group by the last_name column
 * @method FileQuery groupByMaidenName() Group by the maiden_name column
 * @method FileQuery groupByFatherName() Group by the father_name column
 * @method FileQuery groupByMotherName() Group by the mother_name column
 * @method FileQuery groupByBirthDate() Group by the birth_date column
 * @method FileQuery groupByBirthPlace() Group by the birth_place column
 * @method FileQuery groupByPesel() Group by the PESEL column
 * @method FileQuery groupByIdType() Group by the ID_type column
 * @method FileQuery groupByIdNo() Group by the ID_no column
 * @method FileQuery groupByIdCountry() Group by the ID_country column
 * @method FileQuery groupByNip() Group by the NIP column
 * @method FileQuery groupByProfession() Group by the profession column
 * @method FileQuery groupByStreet() Group by the street column
 * @method FileQuery groupByHouse() Group by the house column
 * @method FileQuery groupByFlat() Group by the flat column
 * @method FileQuery groupByCode() Group by the code column
 * @method FileQuery groupByCity() Group by the city column
 * @method FileQuery groupByDistrict2() Group by the district2 column
 * @method FileQuery groupByDistrict() Group by the district column
 * @method FileQuery groupByProvince() Group by the province column
 * @method FileQuery groupByCountry() Group by the country column
 * @method FileQuery groupByPostOffice() Group by the post_office column
 * @method FileQuery groupByBankTaxAccount() Group by the bank_tax_account column
 * @method FileQuery groupByBankAccount() Group by the bank_account column
 * @method FileQuery groupByBankIban() Group by the bank_IBAN column
 * @method FileQuery groupByBankSwift() Group by the bank_SWIFT column
 * @method FileQuery groupByBankName() Group by the bank_name column
 * @method FileQuery groupByPhone() Group by the phone column
 * @method FileQuery groupByEmail() Group by the email column
 *
 * @method FileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FileQuery leftJoinFileCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCat relation
 * @method FileQuery rightJoinFileCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCat relation
 * @method FileQuery innerJoinFileCat($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCat relation
 *
 * @method FileQuery leftJoinSubFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the SubFile relation
 * @method FileQuery rightJoinSubFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SubFile relation
 * @method FileQuery innerJoinSubFile($relationAlias = null) Adds a INNER JOIN clause to the query using the SubFile relation
 *
 * @method FileQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method FileQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method FileQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method FileQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method FileQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method FileQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method FileQuery leftJoinBookkEntryRelatedByFileLev1Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookkEntryRelatedByFileLev1Id relation
 * @method FileQuery rightJoinBookkEntryRelatedByFileLev1Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookkEntryRelatedByFileLev1Id relation
 * @method FileQuery innerJoinBookkEntryRelatedByFileLev1Id($relationAlias = null) Adds a INNER JOIN clause to the query using the BookkEntryRelatedByFileLev1Id relation
 *
 * @method FileQuery leftJoinBookkEntryRelatedByFileLev2Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookkEntryRelatedByFileLev2Id relation
 * @method FileQuery rightJoinBookkEntryRelatedByFileLev2Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookkEntryRelatedByFileLev2Id relation
 * @method FileQuery innerJoinBookkEntryRelatedByFileLev2Id($relationAlias = null) Adds a INNER JOIN clause to the query using the BookkEntryRelatedByFileLev2Id relation
 *
 * @method FileQuery leftJoinBookkEntryRelatedByFileLev3Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookkEntryRelatedByFileLev3Id relation
 * @method FileQuery rightJoinBookkEntryRelatedByFileLev3Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookkEntryRelatedByFileLev3Id relation
 * @method FileQuery innerJoinBookkEntryRelatedByFileLev3Id($relationAlias = null) Adds a INNER JOIN clause to the query using the BookkEntryRelatedByFileLev3Id relation
 *
 * @method FileQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method FileQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method FileQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method FileQuery leftJoinIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the Income relation
 * @method FileQuery rightJoinIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Income relation
 * @method FileQuery innerJoinIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the Income relation
 *
 * @method FileQuery leftJoinCost($relationAlias = null) Adds a LEFT JOIN clause to the query using the Cost relation
 * @method FileQuery rightJoinCost($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Cost relation
 * @method FileQuery innerJoinCost($relationAlias = null) Adds a INNER JOIN clause to the query using the Cost relation
 *
 * @method FileQuery leftJoinContract($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contract relation
 * @method FileQuery rightJoinContract($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contract relation
 * @method FileQuery innerJoinContract($relationAlias = null) Adds a INNER JOIN clause to the query using the Contract relation
 *
 * @method File findOne(PropelPDO $con = null) Return the first File matching the query
 * @method File findOneOrCreate(PropelPDO $con = null) Return the first File matching the query, or a new File object populated from the query conditions when no match is found
 *
 * @method File findOneByName(string $name) Return the first File filtered by the name column
 * @method File findOneByAccNo(int $acc_no) Return the first File filtered by the acc_no column
 * @method File findOneByFileCatId(int $file_cat_id) Return the first File filtered by the file_cat_id column
 * @method File findOneBySubFileId(int $sub_file_id) Return the first File filtered by the sub_file_id column
 * @method File findOneByFirstName(string $first_name) Return the first File filtered by the first_name column
 * @method File findOneBySecondName(string $second_name) Return the first File filtered by the second_name column
 * @method File findOneByLastName(string $last_name) Return the first File filtered by the last_name column
 * @method File findOneByMaidenName(string $maiden_name) Return the first File filtered by the maiden_name column
 * @method File findOneByFatherName(string $father_name) Return the first File filtered by the father_name column
 * @method File findOneByMotherName(string $mother_name) Return the first File filtered by the mother_name column
 * @method File findOneByBirthDate(string $birth_date) Return the first File filtered by the birth_date column
 * @method File findOneByBirthPlace(string $birth_place) Return the first File filtered by the birth_place column
 * @method File findOneByPesel(string $PESEL) Return the first File filtered by the PESEL column
 * @method File findOneByIdType(string $ID_type) Return the first File filtered by the ID_type column
 * @method File findOneByIdNo(string $ID_no) Return the first File filtered by the ID_no column
 * @method File findOneByIdCountry(string $ID_country) Return the first File filtered by the ID_country column
 * @method File findOneByNip(string $NIP) Return the first File filtered by the NIP column
 * @method File findOneByProfession(string $profession) Return the first File filtered by the profession column
 * @method File findOneByStreet(string $street) Return the first File filtered by the street column
 * @method File findOneByHouse(string $house) Return the first File filtered by the house column
 * @method File findOneByFlat(string $flat) Return the first File filtered by the flat column
 * @method File findOneByCode(string $code) Return the first File filtered by the code column
 * @method File findOneByCity(string $city) Return the first File filtered by the city column
 * @method File findOneByDistrict2(string $district2) Return the first File filtered by the district2 column
 * @method File findOneByDistrict(string $district) Return the first File filtered by the district column
 * @method File findOneByProvince(string $province) Return the first File filtered by the province column
 * @method File findOneByCountry(string $country) Return the first File filtered by the country column
 * @method File findOneByPostOffice(string $post_office) Return the first File filtered by the post_office column
 * @method File findOneByBankTaxAccount(string $bank_tax_account) Return the first File filtered by the bank_account column
 * @method File findOneByBankAccount(string $bank_account) Return the first File filtered by the bank_account column
 * @method File findOneByBankIban(string $bank_IBAN) Return the first File filtered by the bank_IBAN column
 * @method File findOneByBankSwift(string $bank_SWIFT) Return the first File filtered by the bank_SWIFT column
 * @method File findOneByBankName(string $bank_name) Return the first File filtered by the bank_name column
 * @method File findOneByPhone(string $phone) Return the first File filtered by the phone column
 * @method File findOneByEmail(string $email) Return the first File filtered by the email column
 *
 * @method array findById(int $id) Return File objects filtered by the id column
 * @method array findByName(string $name) Return File objects filtered by the name column
 * @method array findByAccNo(int $acc_no) Return File objects filtered by the acc_no column
 * @method array findByFileCatId(int $file_cat_id) Return File objects filtered by the file_cat_id column
 * @method array findBySubFileId(int $sub_file_id) Return File objects filtered by the sub_file_id column
 * @method array findByFirstName(string $first_name) Return File objects filtered by the first_name column
 * @method array findBySecondName(string $second_name) Return File objects filtered by the second_name column
 * @method array findByLastName(string $last_name) Return File objects filtered by the last_name column
 * @method array findByMaidenName(string $maiden_name) Return File objects filtered by the maiden_name column
 * @method array findByFatherName(string $father_name) Return File objects filtered by the father_name column
 * @method array findByMotherName(string $mother_name) Return File objects filtered by the mother_name column
 * @method array findByBirthDate(string $birth_date) Return File objects filtered by the birth_date column
 * @method array findByBirthPlace(string $birth_place) Return File objects filtered by the birth_place column
 * @method array findByPesel(string $PESEL) Return File objects filtered by the PESEL column
 * @method array findByIdType(string $ID_type) Return File objects filtered by the ID_type column
 * @method array findByIdNo(string $ID_no) Return File objects filtered by the ID_no column
 * @method array findByIdCountry(string $ID_country) Return File objects filtered by the ID_country column
 * @method array findByNip(string $NIP) Return File objects filtered by the NIP column
 * @method array findByProfession(string $profession) Return File objects filtered by the profession column
 * @method array findByStreet(string $street) Return File objects filtered by the street column
 * @method array findByHouse(string $house) Return File objects filtered by the house column
 * @method array findByFlat(string $flat) Return File objects filtered by the flat column
 * @method array findByCode(string $code) Return File objects filtered by the code column
 * @method array findByCity(string $city) Return File objects filtered by the city column
 * @method array findByDistrict2(string $district2) Return File objects filtered by the district2 column
 * @method array findByDistrict(string $district) Return File objects filtered by the district column
 * @method array findByProvince(string $province) Return File objects filtered by the province column
 * @method array findByCountry(string $country) Return File objects filtered by the country column
 * @method array findByPostOffice(string $post_office) Return File objects filtered by the post_office column
 * @method array findByBankTaxAccount(string $bank_tax_account) Return File objects filtered by the bank_tax_account column
 * @method array findByBankAccount(string $bank_account) Return File objects filtered by the bank_account column
 * @method array findByBankIban(string $bank_IBAN) Return File objects filtered by the bank_IBAN column
 * @method array findByBankSwift(string $bank_SWIFT) Return File objects filtered by the bank_SWIFT column
 * @method array findByBankName(string $bank_name) Return File objects filtered by the bank_name column
 * @method array findByPhone(string $phone) Return File objects filtered by the phone column
 * @method array findByEmail(string $email) Return File objects filtered by the email column
 */
abstract class BaseFileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFileQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'AppBundle\\Model\\File';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FileQuery) {
            return $criteria;
        }
        $query = new FileQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   File|File[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FilePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 File A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 File A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `acc_no`, `file_cat_id`, `sub_file_id`, `first_name`, `second_name`, `last_name`, `maiden_name`, `father_name`, `mother_name`, `birth_date`, `birth_place`, `PESEL`, `ID_type`, `ID_no`, `ID_country`, `NIP`, `profession`, `street`, `house`, `flat`, `code`, `city`, `district2`, `district`, `province`, `country`, `post_office`, `bank_tax_account`, `bank_account`, `bank_IBAN`, `bank_SWIFT`, `bank_name`, `phone`, `email` FROM `file` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new File();
            $obj->hydrate($row);
            FilePeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return File|File[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|File[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FilePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FilePeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FilePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FilePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the acc_no column
     *
     * Example usage:
     * <code>
     * $query->filterByAccNo(1234); // WHERE acc_no = 1234
     * $query->filterByAccNo(array(12, 34)); // WHERE acc_no IN (12, 34)
     * $query->filterByAccNo(array('min' => 12)); // WHERE acc_no >= 12
     * $query->filterByAccNo(array('max' => 12)); // WHERE acc_no <= 12
     * </code>
     *
     * @param     mixed $accNo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByAccNo($accNo = null, $comparison = null)
    {
        if (is_array($accNo)) {
            $useMinMax = false;
            if (isset($accNo['min'])) {
                $this->addUsingAlias(FilePeer::ACC_NO, $accNo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accNo['max'])) {
                $this->addUsingAlias(FilePeer::ACC_NO, $accNo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::ACC_NO, $accNo, $comparison);
    }

    /**
     * Filter the query on the file_cat_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileCatId(1234); // WHERE file_cat_id = 1234
     * $query->filterByFileCatId(array(12, 34)); // WHERE file_cat_id IN (12, 34)
     * $query->filterByFileCatId(array('min' => 12)); // WHERE file_cat_id >= 12
     * $query->filterByFileCatId(array('max' => 12)); // WHERE file_cat_id <= 12
     * </code>
     *
     * @see       filterByFileCat()
     *
     * @param     mixed $fileCatId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByFileCatId($fileCatId = null, $comparison = null)
    {
        if (is_array($fileCatId)) {
            $useMinMax = false;
            if (isset($fileCatId['min'])) {
                $this->addUsingAlias(FilePeer::FILE_CAT_ID, $fileCatId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileCatId['max'])) {
                $this->addUsingAlias(FilePeer::FILE_CAT_ID, $fileCatId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::FILE_CAT_ID, $fileCatId, $comparison);
    }

    /**
     * Filter the query on the sub_file_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubFileId(1234); // WHERE sub_file_id = 1234
     * $query->filterBySubFileId(array(12, 34)); // WHERE sub_file_id IN (12, 34)
     * $query->filterBySubFileId(array('min' => 12)); // WHERE sub_file_id >= 12
     * $query->filterBySubFileId(array('max' => 12)); // WHERE sub_file_id <= 12
     * </code>
     *
     * @see       filterBySubFile()
     *
     * @param     mixed $subFileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterBySubFileId($subFileId = null, $comparison = null)
    {
        if (is_array($subFileId)) {
            $useMinMax = false;
            if (isset($subFileId['min'])) {
                $this->addUsingAlias(FilePeer::SUB_FILE_ID, $subFileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subFileId['max'])) {
                $this->addUsingAlias(FilePeer::SUB_FILE_ID, $subFileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::SUB_FILE_ID, $subFileId, $comparison);
    }

    /**
     * Filter the query on the first_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstName('fooValue');   // WHERE first_name = 'fooValue'
     * $query->filterByFirstName('%fooValue%'); // WHERE first_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByFirstName($firstName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstName)) {
                $firstName = str_replace('*', '%', $firstName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::FIRST_NAME, $firstName, $comparison);
    }

    /**
     * Filter the query on the second_name column
     *
     * Example usage:
     * <code>
     * $query->filterBySecondName('fooValue');   // WHERE second_name = 'fooValue'
     * $query->filterBySecondName('%fooValue%'); // WHERE second_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $secondName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterBySecondName($secondName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($secondName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $secondName)) {
                $secondName = str_replace('*', '%', $secondName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::SECOND_NAME, $secondName, $comparison);
    }

    /**
     * Filter the query on the last_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLastName('fooValue');   // WHERE last_name = 'fooValue'
     * $query->filterByLastName('%fooValue%'); // WHERE last_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $lastName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByLastName($lastName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($lastName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $lastName)) {
                $lastName = str_replace('*', '%', $lastName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::LAST_NAME, $lastName, $comparison);
    }

    /**
     * Filter the query on the maiden_name column
     *
     * Example usage:
     * <code>
     * $query->filterByMaidenName('fooValue');   // WHERE maiden_name = 'fooValue'
     * $query->filterByMaidenName('%fooValue%'); // WHERE maiden_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $maidenName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByMaidenName($maidenName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($maidenName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $maidenName)) {
                $maidenName = str_replace('*', '%', $maidenName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::MAIDEN_NAME, $maidenName, $comparison);
    }

    /**
     * Filter the query on the father_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFatherName('fooValue');   // WHERE father_name = 'fooValue'
     * $query->filterByFatherName('%fooValue%'); // WHERE father_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fatherName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByFatherName($fatherName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fatherName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fatherName)) {
                $fatherName = str_replace('*', '%', $fatherName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::FATHER_NAME, $fatherName, $comparison);
    }

    /**
     * Filter the query on the mother_name column
     *
     * Example usage:
     * <code>
     * $query->filterByMotherName('fooValue');   // WHERE mother_name = 'fooValue'
     * $query->filterByMotherName('%fooValue%'); // WHERE mother_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $motherName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByMotherName($motherName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($motherName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $motherName)) {
                $motherName = str_replace('*', '%', $motherName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::MOTHER_NAME, $motherName, $comparison);
    }

    /**
     * Filter the query on the birth_date column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthDate('2011-03-14'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate('now'); // WHERE birth_date = '2011-03-14'
     * $query->filterByBirthDate(array('max' => 'yesterday')); // WHERE birth_date < '2011-03-13'
     * </code>
     *
     * @param     mixed $birthDate The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBirthDate($birthDate = null, $comparison = null)
    {
        if (is_array($birthDate)) {
            $useMinMax = false;
            if (isset($birthDate['min'])) {
                $this->addUsingAlias(FilePeer::BIRTH_DATE, $birthDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($birthDate['max'])) {
                $this->addUsingAlias(FilePeer::BIRTH_DATE, $birthDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FilePeer::BIRTH_DATE, $birthDate, $comparison);
    }

    /**
     * Filter the query on the birth_place column
     *
     * Example usage:
     * <code>
     * $query->filterByBirthPlace('fooValue');   // WHERE birth_place = 'fooValue'
     * $query->filterByBirthPlace('%fooValue%'); // WHERE birth_place LIKE '%fooValue%'
     * </code>
     *
     * @param     string $birthPlace The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBirthPlace($birthPlace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($birthPlace)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $birthPlace)) {
                $birthPlace = str_replace('*', '%', $birthPlace);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BIRTH_PLACE, $birthPlace, $comparison);
    }

    /**
     * Filter the query on the PESEL column
     *
     * Example usage:
     * <code>
     * $query->filterByPesel('fooValue');   // WHERE PESEL = 'fooValue'
     * $query->filterByPesel('%fooValue%'); // WHERE PESEL LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pesel The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPesel($pesel = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pesel)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pesel)) {
                $pesel = str_replace('*', '%', $pesel);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::PESEL, $pesel, $comparison);
    }

    /**
     * Filter the query on the ID_type column
     *
     * Example usage:
     * <code>
     * $query->filterByIdType('fooValue');   // WHERE ID_type = 'fooValue'
     * $query->filterByIdType('%fooValue%'); // WHERE ID_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $idType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByIdType($idType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($idType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $idType)) {
                $idType = str_replace('*', '%', $idType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::ID_TYPE, $idType, $comparison);
    }

    /**
     * Filter the query on the ID_no column
     *
     * Example usage:
     * <code>
     * $query->filterByIdNo('fooValue');   // WHERE ID_no = 'fooValue'
     * $query->filterByIdNo('%fooValue%'); // WHERE ID_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $idNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByIdNo($idNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($idNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $idNo)) {
                $idNo = str_replace('*', '%', $idNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::ID_NO, $idNo, $comparison);
    }

    /**
     * Filter the query on the ID_country column
     *
     * Example usage:
     * <code>
     * $query->filterByIdCountry('fooValue');   // WHERE ID_country = 'fooValue'
     * $query->filterByIdCountry('%fooValue%'); // WHERE ID_country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $idCountry The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByIdCountry($idCountry = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($idCountry)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $idCountry)) {
                $idCountry = str_replace('*', '%', $idCountry);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::ID_COUNTRY, $idCountry, $comparison);
    }

    /**
     * Filter the query on the NIP column
     *
     * Example usage:
     * <code>
     * $query->filterByNip('fooValue');   // WHERE NIP = 'fooValue'
     * $query->filterByNip('%fooValue%'); // WHERE NIP LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nip The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByNip($nip = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nip)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nip)) {
                $nip = str_replace('*', '%', $nip);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::NIP, $nip, $comparison);
    }

    /**
     * Filter the query on the profession column
     *
     * Example usage:
     * <code>
     * $query->filterByProfession('fooValue');   // WHERE profession = 'fooValue'
     * $query->filterByProfession('%fooValue%'); // WHERE profession LIKE '%fooValue%'
     * </code>
     *
     * @param     string $profession The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByProfession($profession = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($profession)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $profession)) {
                $profession = str_replace('*', '%', $profession);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::PROFESSION, $profession, $comparison);
    }

    /**
     * Filter the query on the street column
     *
     * Example usage:
     * <code>
     * $query->filterByStreet('fooValue');   // WHERE street = 'fooValue'
     * $query->filterByStreet('%fooValue%'); // WHERE street LIKE '%fooValue%'
     * </code>
     *
     * @param     string $street The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByStreet($street = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($street)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $street)) {
                $street = str_replace('*', '%', $street);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::STREET, $street, $comparison);
    }

    /**
     * Filter the query on the house column
     *
     * Example usage:
     * <code>
     * $query->filterByHouse('fooValue');   // WHERE house = 'fooValue'
     * $query->filterByHouse('%fooValue%'); // WHERE house LIKE '%fooValue%'
     * </code>
     *
     * @param     string $house The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByHouse($house = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($house)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $house)) {
                $house = str_replace('*', '%', $house);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::HOUSE, $house, $comparison);
    }

    /**
     * Filter the query on the flat column
     *
     * Example usage:
     * <code>
     * $query->filterByFlat('fooValue');   // WHERE flat = 'fooValue'
     * $query->filterByFlat('%fooValue%'); // WHERE flat LIKE '%fooValue%'
     * </code>
     *
     * @param     string $flat The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByFlat($flat = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($flat)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $flat)) {
                $flat = str_replace('*', '%', $flat);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::FLAT, $flat, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode('fooValue');   // WHERE code = 'fooValue'
     * $query->filterByCode('%fooValue%'); // WHERE code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $code The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($code)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $code)) {
                $code = str_replace('*', '%', $code);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::CODE, $code, $comparison);
    }

    /**
     * Filter the query on the city column
     *
     * Example usage:
     * <code>
     * $query->filterByCity('fooValue');   // WHERE city = 'fooValue'
     * $query->filterByCity('%fooValue%'); // WHERE city LIKE '%fooValue%'
     * </code>
     *
     * @param     string $city The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByCity($city = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($city)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $city)) {
                $city = str_replace('*', '%', $city);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::CITY, $city, $comparison);
    }

    /**
     * Filter the query on the district2 column
     *
     * Example usage:
     * <code>
     * $query->filterByDistrict2('fooValue');   // WHERE district2 = 'fooValue'
     * $query->filterByDistrict2('%fooValue%'); // WHERE district2 LIKE '%fooValue%'
     * </code>
     *
     * @param     string $district2 The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByDistrict2($district2 = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($district2)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $district2)) {
                $district2 = str_replace('*', '%', $district2);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::DISTRICT2, $district2, $comparison);
    }

    /**
     * Filter the query on the district column
     *
     * Example usage:
     * <code>
     * $query->filterByDistrict('fooValue');   // WHERE district = 'fooValue'
     * $query->filterByDistrict('%fooValue%'); // WHERE district LIKE '%fooValue%'
     * </code>
     *
     * @param     string $district The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByDistrict($district = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($district)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $district)) {
                $district = str_replace('*', '%', $district);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::DISTRICT, $district, $comparison);
    }

    /**
     * Filter the query on the province column
     *
     * Example usage:
     * <code>
     * $query->filterByProvince('fooValue');   // WHERE province = 'fooValue'
     * $query->filterByProvince('%fooValue%'); // WHERE province LIKE '%fooValue%'
     * </code>
     *
     * @param     string $province The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByProvince($province = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($province)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $province)) {
                $province = str_replace('*', '%', $province);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::PROVINCE, $province, $comparison);
    }

    /**
     * Filter the query on the country column
     *
     * Example usage:
     * <code>
     * $query->filterByCountry('fooValue');   // WHERE country = 'fooValue'
     * $query->filterByCountry('%fooValue%'); // WHERE country LIKE '%fooValue%'
     * </code>
     *
     * @param     string $country The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByCountry($country = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($country)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $country)) {
                $country = str_replace('*', '%', $country);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::COUNTRY, $country, $comparison);
    }

    /**
     * Filter the query on the post_office column
     *
     * Example usage:
     * <code>
     * $query->filterByPostOffice('fooValue');   // WHERE post_office = 'fooValue'
     * $query->filterByPostOffice('%fooValue%'); // WHERE post_office LIKE '%fooValue%'
     * </code>
     *
     * @param     string $postOffice The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPostOffice($postOffice = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($postOffice)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $postOffice)) {
                $postOffice = str_replace('*', '%', $postOffice);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::POST_OFFICE, $postOffice, $comparison);
    }

        /**
     * Filter the query on the bank_tax_account column
     *
     * Example usage:
     * <code>
     * $query->filterByBankTaxAccount('fooValue');   // WHERE bank_tax_account = 'fooValue'
     * $query->filterByBankTaxAccount('%fooValue%'); // WHERE bank_tax_account LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankTaxAccount The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBankTaxAccount($bankAccount = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankTaxAccount)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankTaxAccount)) {
                $bankTaxAccount = str_replace('*', '%', $bankTaxAccount);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BANK_TAX_ACCOUNT, $bankTaxAccount, $comparison);
    }
    
    /**
     * Filter the query on the bank_account column
     *
     * Example usage:
     * <code>
     * $query->filterByBankAccount('fooValue');   // WHERE bank_account = 'fooValue'
     * $query->filterByBankAccount('%fooValue%'); // WHERE bank_account LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankAccount The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBankAccount($bankAccount = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankAccount)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankAccount)) {
                $bankAccount = str_replace('*', '%', $bankAccount);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BANK_ACCOUNT, $bankAccount, $comparison);
    }

    /**
     * Filter the query on the bank_IBAN column
     *
     * Example usage:
     * <code>
     * $query->filterByBankIban('fooValue');   // WHERE bank_IBAN = 'fooValue'
     * $query->filterByBankIban('%fooValue%'); // WHERE bank_IBAN LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankIban The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBankIban($bankIban = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankIban)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankIban)) {
                $bankIban = str_replace('*', '%', $bankIban);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BANK_IBAN, $bankIban, $comparison);
    }

    /**
     * Filter the query on the bank_SWIFT column
     *
     * Example usage:
     * <code>
     * $query->filterByBankSwift('fooValue');   // WHERE bank_SWIFT = 'fooValue'
     * $query->filterByBankSwift('%fooValue%'); // WHERE bank_SWIFT LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankSwift The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBankSwift($bankSwift = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankSwift)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankSwift)) {
                $bankSwift = str_replace('*', '%', $bankSwift);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BANK_SWIFT, $bankSwift, $comparison);
    }

    /**
     * Filter the query on the bank_name column
     *
     * Example usage:
     * <code>
     * $query->filterByBankName('fooValue');   // WHERE bank_name = 'fooValue'
     * $query->filterByBankName('%fooValue%'); // WHERE bank_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bankName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByBankName($bankName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bankName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bankName)) {
                $bankName = str_replace('*', '%', $bankName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::BANK_NAME, $bankName, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FilePeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCat($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(FilePeer::FILE_CAT_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FilePeer::FILE_CAT_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileCat() only accepts arguments of type FileCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileCat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinFileCat($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileCat');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'FileCat');
        }

        return $this;
    }

    /**
     * Use the FileCat relation FileCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useFileCatQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileCat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileCat', '\AppBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySubFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(FilePeer::SUB_FILE_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FilePeer::SUB_FILE_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubFile() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SubFile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinSubFile($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SubFile');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'SubFile');
        }

        return $this;
    }

    /**
     * Use the SubFile relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useSubFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SubFile', '\AppBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(FilePeer::ID, $file->getSubFileId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            return $this
                ->useFileQuery()
                ->filterByPrimaryKeys($file->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByFile() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the File relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinFile($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('File');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'File');
        }

        return $this;
    }

    /**
     * Use the File relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\AppBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(FilePeer::ID, $doc->getFileId(), $comparison);
        } elseif ($doc instanceof PropelObjectCollection) {
            return $this
                ->useDocQuery()
                ->filterByPrimaryKeys($doc->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDoc() only accepts arguments of type Doc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Doc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinDoc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Doc');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Doc');
        }

        return $this;
    }

    /**
     * Use the Doc relation Doc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\DocQuery A secondary query class using the current class as primary query
     */
    public function useDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Doc', '\AppBundle\Model\DocQuery');
    }

    /**
     * Filter the query by a related BookkEntry object
     *
     * @param   BookkEntry|PropelObjectCollection $bookkEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookkEntryRelatedByFileLev1Id($bookkEntry, $comparison = null)
    {
        if ($bookkEntry instanceof BookkEntry) {
            return $this
                ->addUsingAlias(FilePeer::ID, $bookkEntry->getFileLev1Id(), $comparison);
        } elseif ($bookkEntry instanceof PropelObjectCollection) {
            return $this
                ->useBookkEntryRelatedByFileLev1IdQuery()
                ->filterByPrimaryKeys($bookkEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookkEntryRelatedByFileLev1Id() only accepts arguments of type BookkEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookkEntryRelatedByFileLev1Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinBookkEntryRelatedByFileLev1Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookkEntryRelatedByFileLev1Id');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'BookkEntryRelatedByFileLev1Id');
        }

        return $this;
    }

    /**
     * Use the BookkEntryRelatedByFileLev1Id relation BookkEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\BookkEntryQuery A secondary query class using the current class as primary query
     */
    public function useBookkEntryRelatedByFileLev1IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookkEntryRelatedByFileLev1Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookkEntryRelatedByFileLev1Id', '\AppBundle\Model\BookkEntryQuery');
    }

    /**
     * Filter the query by a related BookkEntry object
     *
     * @param   BookkEntry|PropelObjectCollection $bookkEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookkEntryRelatedByFileLev2Id($bookkEntry, $comparison = null)
    {
        if ($bookkEntry instanceof BookkEntry) {
            return $this
                ->addUsingAlias(FilePeer::ID, $bookkEntry->getFileLev2Id(), $comparison);
        } elseif ($bookkEntry instanceof PropelObjectCollection) {
            return $this
                ->useBookkEntryRelatedByFileLev2IdQuery()
                ->filterByPrimaryKeys($bookkEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookkEntryRelatedByFileLev2Id() only accepts arguments of type BookkEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookkEntryRelatedByFileLev2Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinBookkEntryRelatedByFileLev2Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookkEntryRelatedByFileLev2Id');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'BookkEntryRelatedByFileLev2Id');
        }

        return $this;
    }

    /**
     * Use the BookkEntryRelatedByFileLev2Id relation BookkEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\BookkEntryQuery A secondary query class using the current class as primary query
     */
    public function useBookkEntryRelatedByFileLev2IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookkEntryRelatedByFileLev2Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookkEntryRelatedByFileLev2Id', '\AppBundle\Model\BookkEntryQuery');
    }

    /**
     * Filter the query by a related BookkEntry object
     *
     * @param   BookkEntry|PropelObjectCollection $bookkEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookkEntryRelatedByFileLev3Id($bookkEntry, $comparison = null)
    {
        if ($bookkEntry instanceof BookkEntry) {
            return $this
                ->addUsingAlias(FilePeer::ID, $bookkEntry->getFileLev3Id(), $comparison);
        } elseif ($bookkEntry instanceof PropelObjectCollection) {
            return $this
                ->useBookkEntryRelatedByFileLev3IdQuery()
                ->filterByPrimaryKeys($bookkEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookkEntryRelatedByFileLev3Id() only accepts arguments of type BookkEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookkEntryRelatedByFileLev3Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinBookkEntryRelatedByFileLev3Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookkEntryRelatedByFileLev3Id');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'BookkEntryRelatedByFileLev3Id');
        }

        return $this;
    }

    /**
     * Use the BookkEntryRelatedByFileLev3Id relation BookkEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\BookkEntryQuery A secondary query class using the current class as primary query
     */
    public function useBookkEntryRelatedByFileLev3IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookkEntryRelatedByFileLev3Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookkEntryRelatedByFileLev3Id', '\AppBundle\Model\BookkEntryQuery');
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProject($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(FilePeer::ID, $project->getFileId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            return $this
                ->useProjectQuery()
                ->filterByPrimaryKeys($project->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProject() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Project relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinProject($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Project');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Project');
        }

        return $this;
    }

    /**
     * Use the Project relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Project', '\AppBundle\Model\ProjectQuery');
    }

    /**
     * Filter the query by a related Income object
     *
     * @param   Income|PropelObjectCollection $income  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncome($income, $comparison = null)
    {
        if ($income instanceof Income) {
            return $this
                ->addUsingAlias(FilePeer::ID, $income->getFileId(), $comparison);
        } elseif ($income instanceof PropelObjectCollection) {
            return $this
                ->useIncomeQuery()
                ->filterByPrimaryKeys($income->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIncome() only accepts arguments of type Income or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Income relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinIncome($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Income');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Income');
        }

        return $this;
    }

    /**
     * Use the Income relation Income object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\IncomeQuery A secondary query class using the current class as primary query
     */
    public function useIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Income', '\AppBundle\Model\IncomeQuery');
    }

    /**
     * Filter the query by a related Cost object
     *
     * @param   Cost|PropelObjectCollection $cost  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCost($cost, $comparison = null)
    {
        if ($cost instanceof Cost) {
            return $this
                ->addUsingAlias(FilePeer::ID, $cost->getFileId(), $comparison);
        } elseif ($cost instanceof PropelObjectCollection) {
            return $this
                ->useCostQuery()
                ->filterByPrimaryKeys($cost->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCost() only accepts arguments of type Cost or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Cost relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinCost($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Cost');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Cost');
        }

        return $this;
    }

    /**
     * Use the Cost relation Cost object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\CostQuery A secondary query class using the current class as primary query
     */
    public function useCostQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCost($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Cost', '\AppBundle\Model\CostQuery');
    }

    /**
     * Filter the query by a related Contract object
     *
     * @param   Contract|PropelObjectCollection $contract  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByContract($contract, $comparison = null)
    {
        if ($contract instanceof Contract) {
            return $this
                ->addUsingAlias(FilePeer::ID, $contract->getFileId(), $comparison);
        } elseif ($contract instanceof PropelObjectCollection) {
            return $this
                ->useContractQuery()
                ->filterByPrimaryKeys($contract->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByContract() only accepts arguments of type Contract or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Contract relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function joinContract($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Contract');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Contract');
        }

        return $this;
    }

    /**
     * Use the Contract relation Contract object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\ContractQuery A secondary query class using the current class as primary query
     */
    public function useContractQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContract($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contract', '\AppBundle\Model\ContractQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   File $file Object to remove from the list of results
     *
     * @return FileQuery The current query, for fluid interface
     */
    public function prune($file = null)
    {
        if ($file) {
            $this->addUsingAlias(FilePeer::ID, $file->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
