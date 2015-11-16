<?php

namespace Oppen\ProjectBundle\Model\om;

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
use Oppen\ProjectBundle\Model\Account;
use Oppen\ProjectBundle\Model\Bookk;
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\BookkEntryPeer;
use Oppen\ProjectBundle\Model\BookkEntryQuery;
use Oppen\ProjectBundle\Model\File;

/**
 * @method BookkEntryQuery orderById($order = Criteria::ASC) Order by the id column
 * @method BookkEntryQuery orderByAccNo($order = Criteria::ASC) Order by the acc_no column
 * @method BookkEntryQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method BookkEntryQuery orderBySide($order = Criteria::ASC) Order by the side column
 * @method BookkEntryQuery orderByBookkId($order = Criteria::ASC) Order by the bookk_id column
 * @method BookkEntryQuery orderByAccountId($order = Criteria::ASC) Order by the account_id column
 * @method BookkEntryQuery orderByFileLev1Id($order = Criteria::ASC) Order by the file_lev1_id column
 * @method BookkEntryQuery orderByFileLev2Id($order = Criteria::ASC) Order by the file_lev2_id column
 * @method BookkEntryQuery orderByFileLev3Id($order = Criteria::ASC) Order by the file_lev3_id column
 *
 * @method BookkEntryQuery groupById() Group by the id column
 * @method BookkEntryQuery groupByAccNo() Group by the acc_no column
 * @method BookkEntryQuery groupByValue() Group by the value column
 * @method BookkEntryQuery groupBySide() Group by the side column
 * @method BookkEntryQuery groupByBookkId() Group by the bookk_id column
 * @method BookkEntryQuery groupByAccountId() Group by the account_id column
 * @method BookkEntryQuery groupByFileLev1Id() Group by the file_lev1_id column
 * @method BookkEntryQuery groupByFileLev2Id() Group by the file_lev2_id column
 * @method BookkEntryQuery groupByFileLev3Id() Group by the file_lev3_id column
 *
 * @method BookkEntryQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method BookkEntryQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method BookkEntryQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method BookkEntryQuery leftJoinBookk($relationAlias = null) Adds a LEFT JOIN clause to the query using the Bookk relation
 * @method BookkEntryQuery rightJoinBookk($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Bookk relation
 * @method BookkEntryQuery innerJoinBookk($relationAlias = null) Adds a INNER JOIN clause to the query using the Bookk relation
 *
 * @method BookkEntryQuery leftJoinAccount($relationAlias = null) Adds a LEFT JOIN clause to the query using the Account relation
 * @method BookkEntryQuery rightJoinAccount($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Account relation
 * @method BookkEntryQuery innerJoinAccount($relationAlias = null) Adds a INNER JOIN clause to the query using the Account relation
 *
 * @method BookkEntryQuery leftJoinFileLev1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileLev1 relation
 * @method BookkEntryQuery rightJoinFileLev1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileLev1 relation
 * @method BookkEntryQuery innerJoinFileLev1($relationAlias = null) Adds a INNER JOIN clause to the query using the FileLev1 relation
 *
 * @method BookkEntryQuery leftJoinFileLev2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileLev2 relation
 * @method BookkEntryQuery rightJoinFileLev2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileLev2 relation
 * @method BookkEntryQuery innerJoinFileLev2($relationAlias = null) Adds a INNER JOIN clause to the query using the FileLev2 relation
 *
 * @method BookkEntryQuery leftJoinFileLev3($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileLev3 relation
 * @method BookkEntryQuery rightJoinFileLev3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileLev3 relation
 * @method BookkEntryQuery innerJoinFileLev3($relationAlias = null) Adds a INNER JOIN clause to the query using the FileLev3 relation
 *
 * @method BookkEntry findOne(PropelPDO $con = null) Return the first BookkEntry matching the query
 * @method BookkEntry findOneOrCreate(PropelPDO $con = null) Return the first BookkEntry matching the query, or a new BookkEntry object populated from the query conditions when no match is found
 *
 * @method BookkEntry findOneByAccNo(string $acc_no) Return the first BookkEntry filtered by the acc_no column
 * @method BookkEntry findOneByValue(double $value) Return the first BookkEntry filtered by the value column
 * @method BookkEntry findOneBySide(int $side) Return the first BookkEntry filtered by the side column
 * @method BookkEntry findOneByBookkId(int $bookk_id) Return the first BookkEntry filtered by the bookk_id column
 * @method BookkEntry findOneByAccountId(int $account_id) Return the first BookkEntry filtered by the account_id column
 * @method BookkEntry findOneByFileLev1Id(int $file_lev1_id) Return the first BookkEntry filtered by the file_lev1_id column
 * @method BookkEntry findOneByFileLev2Id(int $file_lev2_id) Return the first BookkEntry filtered by the file_lev2_id column
 * @method BookkEntry findOneByFileLev3Id(int $file_lev3_id) Return the first BookkEntry filtered by the file_lev3_id column
 *
 * @method array findById(int $id) Return BookkEntry objects filtered by the id column
 * @method array findByAccNo(string $acc_no) Return BookkEntry objects filtered by the acc_no column
 * @method array findByValue(double $value) Return BookkEntry objects filtered by the value column
 * @method array findBySide(int $side) Return BookkEntry objects filtered by the side column
 * @method array findByBookkId(int $bookk_id) Return BookkEntry objects filtered by the bookk_id column
 * @method array findByAccountId(int $account_id) Return BookkEntry objects filtered by the account_id column
 * @method array findByFileLev1Id(int $file_lev1_id) Return BookkEntry objects filtered by the file_lev1_id column
 * @method array findByFileLev2Id(int $file_lev2_id) Return BookkEntry objects filtered by the file_lev2_id column
 * @method array findByFileLev3Id(int $file_lev3_id) Return BookkEntry objects filtered by the file_lev3_id column
 */
abstract class BaseBookkEntryQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseBookkEntryQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\BookkEntry';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new BookkEntryQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   BookkEntryQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return BookkEntryQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof BookkEntryQuery) {
            return $criteria;
        }
        $query = new BookkEntryQuery(null, null, $modelAlias);

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
     * @return   BookkEntry|BookkEntry[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BookkEntryPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(BookkEntryPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 BookkEntry A model object, or null if the key is not found
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
     * @return                 BookkEntry A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `acc_no`, `value`, `side`, `bookk_id`, `account_id`, `file_lev1_id`, `file_lev2_id`, `file_lev3_id` FROM `bookk_entry` WHERE `id` = :p0';
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
            $obj = new BookkEntry();
            $obj->hydrate($row);
            BookkEntryPeer::addInstanceToPool($obj, (string) $key);
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
     * @return BookkEntry|BookkEntry[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|BookkEntry[]|mixed the list of results, formatted by the current formatter
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
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BookkEntryPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BookkEntryPeer::ID, $keys, Criteria::IN);
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
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BookkEntryPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BookkEntryPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the acc_no column
     *
     * Example usage:
     * <code>
     * $query->filterByAccNo('fooValue');   // WHERE acc_no = 'fooValue'
     * $query->filterByAccNo('%fooValue%'); // WHERE acc_no LIKE '%fooValue%'
     * </code>
     *
     * @param     string $accNo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByAccNo($accNo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($accNo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $accNo)) {
                $accNo = str_replace('*', '%', $accNo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::ACC_NO, $accNo, $comparison);
    }

    /**
     * Filter the query on the value column
     *
     * Example usage:
     * <code>
     * $query->filterByValue(1234); // WHERE value = 1234
     * $query->filterByValue(array(12, 34)); // WHERE value IN (12, 34)
     * $query->filterByValue(array('min' => 12)); // WHERE value >= 12
     * $query->filterByValue(array('max' => 12)); // WHERE value <= 12
     * </code>
     *
     * @param     mixed $value The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(BookkEntryPeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(BookkEntryPeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the side column
     *
     * Example usage:
     * <code>
     * $query->filterBySide(1234); // WHERE side = 1234
     * $query->filterBySide(array(12, 34)); // WHERE side IN (12, 34)
     * $query->filterBySide(array('min' => 12)); // WHERE side >= 12
     * $query->filterBySide(array('max' => 12)); // WHERE side <= 12
     * </code>
     *
     * @param     mixed $side The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterBySide($side = null, $comparison = null)
    {
        if (is_array($side)) {
            $useMinMax = false;
            if (isset($side['min'])) {
                $this->addUsingAlias(BookkEntryPeer::SIDE, $side['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($side['max'])) {
                $this->addUsingAlias(BookkEntryPeer::SIDE, $side['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::SIDE, $side, $comparison);
    }

    /**
     * Filter the query on the bookk_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBookkId(1234); // WHERE bookk_id = 1234
     * $query->filterByBookkId(array(12, 34)); // WHERE bookk_id IN (12, 34)
     * $query->filterByBookkId(array('min' => 12)); // WHERE bookk_id >= 12
     * $query->filterByBookkId(array('max' => 12)); // WHERE bookk_id <= 12
     * </code>
     *
     * @see       filterByBookk()
     *
     * @param     mixed $bookkId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByBookkId($bookkId = null, $comparison = null)
    {
        if (is_array($bookkId)) {
            $useMinMax = false;
            if (isset($bookkId['min'])) {
                $this->addUsingAlias(BookkEntryPeer::BOOKK_ID, $bookkId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bookkId['max'])) {
                $this->addUsingAlias(BookkEntryPeer::BOOKK_ID, $bookkId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::BOOKK_ID, $bookkId, $comparison);
    }

    /**
     * Filter the query on the account_id column
     *
     * Example usage:
     * <code>
     * $query->filterByAccountId(1234); // WHERE account_id = 1234
     * $query->filterByAccountId(array(12, 34)); // WHERE account_id IN (12, 34)
     * $query->filterByAccountId(array('min' => 12)); // WHERE account_id >= 12
     * $query->filterByAccountId(array('max' => 12)); // WHERE account_id <= 12
     * </code>
     *
     * @see       filterByAccount()
     *
     * @param     mixed $accountId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByAccountId($accountId = null, $comparison = null)
    {
        if (is_array($accountId)) {
            $useMinMax = false;
            if (isset($accountId['min'])) {
                $this->addUsingAlias(BookkEntryPeer::ACCOUNT_ID, $accountId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($accountId['max'])) {
                $this->addUsingAlias(BookkEntryPeer::ACCOUNT_ID, $accountId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::ACCOUNT_ID, $accountId, $comparison);
    }

    /**
     * Filter the query on the file_lev1_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileLev1Id(1234); // WHERE file_lev1_id = 1234
     * $query->filterByFileLev1Id(array(12, 34)); // WHERE file_lev1_id IN (12, 34)
     * $query->filterByFileLev1Id(array('min' => 12)); // WHERE file_lev1_id >= 12
     * $query->filterByFileLev1Id(array('max' => 12)); // WHERE file_lev1_id <= 12
     * </code>
     *
     * @see       filterByFileLev1()
     *
     * @param     mixed $fileLev1Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByFileLev1Id($fileLev1Id = null, $comparison = null)
    {
        if (is_array($fileLev1Id)) {
            $useMinMax = false;
            if (isset($fileLev1Id['min'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV1_ID, $fileLev1Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileLev1Id['max'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV1_ID, $fileLev1Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::FILE_LEV1_ID, $fileLev1Id, $comparison);
    }

    /**
     * Filter the query on the file_lev2_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileLev2Id(1234); // WHERE file_lev2_id = 1234
     * $query->filterByFileLev2Id(array(12, 34)); // WHERE file_lev2_id IN (12, 34)
     * $query->filterByFileLev2Id(array('min' => 12)); // WHERE file_lev2_id >= 12
     * $query->filterByFileLev2Id(array('max' => 12)); // WHERE file_lev2_id <= 12
     * </code>
     *
     * @see       filterByFileLev2()
     *
     * @param     mixed $fileLev2Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByFileLev2Id($fileLev2Id = null, $comparison = null)
    {
        if (is_array($fileLev2Id)) {
            $useMinMax = false;
            if (isset($fileLev2Id['min'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV2_ID, $fileLev2Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileLev2Id['max'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV2_ID, $fileLev2Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::FILE_LEV2_ID, $fileLev2Id, $comparison);
    }

    /**
     * Filter the query on the file_lev3_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileLev3Id(1234); // WHERE file_lev3_id = 1234
     * $query->filterByFileLev3Id(array(12, 34)); // WHERE file_lev3_id IN (12, 34)
     * $query->filterByFileLev3Id(array('min' => 12)); // WHERE file_lev3_id >= 12
     * $query->filterByFileLev3Id(array('max' => 12)); // WHERE file_lev3_id <= 12
     * </code>
     *
     * @see       filterByFileLev3()
     *
     * @param     mixed $fileLev3Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function filterByFileLev3Id($fileLev3Id = null, $comparison = null)
    {
        if (is_array($fileLev3Id)) {
            $useMinMax = false;
            if (isset($fileLev3Id['min'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV3_ID, $fileLev3Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileLev3Id['max'])) {
                $this->addUsingAlias(BookkEntryPeer::FILE_LEV3_ID, $fileLev3Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BookkEntryPeer::FILE_LEV3_ID, $fileLev3Id, $comparison);
    }

    /**
     * Filter the query by a related Bookk object
     *
     * @param   Bookk|PropelObjectCollection $bookk The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookk($bookk, $comparison = null)
    {
        if ($bookk instanceof Bookk) {
            return $this
                ->addUsingAlias(BookkEntryPeer::BOOKK_ID, $bookk->getId(), $comparison);
        } elseif ($bookk instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkEntryPeer::BOOKK_ID, $bookk->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBookk() only accepts arguments of type Bookk or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Bookk relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function joinBookk($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Bookk');

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
            $this->addJoinObject($join, 'Bookk');
        }

        return $this;
    }

    /**
     * Use the Bookk relation Bookk object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\BookkQuery A secondary query class using the current class as primary query
     */
    public function useBookkQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookk($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Bookk', '\Oppen\ProjectBundle\Model\BookkQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccount($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(BookkEntryPeer::ACCOUNT_ID, $account->getId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkEntryPeer::ACCOUNT_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByAccount() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Account relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function joinAccount($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Account');

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
            $this->addJoinObject($join, 'Account');
        }

        return $this;
    }

    /**
     * Use the Account relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccount($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Account', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileLev1($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV1_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV1_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileLev1() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileLev1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function joinFileLev1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileLev1');

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
            $this->addJoinObject($join, 'FileLev1');
        }

        return $this;
    }

    /**
     * Use the FileLev1 relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileLev1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileLev1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileLev1', '\Oppen\ProjectBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileLev2($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV2_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV2_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileLev2() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileLev2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function joinFileLev2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileLev2');

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
            $this->addJoinObject($join, 'FileLev2');
        }

        return $this;
    }

    /**
     * Use the FileLev2 relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileLev2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileLev2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileLev2', '\Oppen\ProjectBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 BookkEntryQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileLev3($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV3_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(BookkEntryPeer::FILE_LEV3_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileLev3() only accepts arguments of type File or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileLev3 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function joinFileLev3($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileLev3');

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
            $this->addJoinObject($join, 'FileLev3');
        }

        return $this;
    }

    /**
     * Use the FileLev3 relation File object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileLev3Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileLev3($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileLev3', '\Oppen\ProjectBundle\Model\FileQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   BookkEntry $bookkEntry Object to remove from the list of results
     *
     * @return BookkEntryQuery The current query, for fluid interface
     */
    public function prune($bookkEntry = null)
    {
        if ($bookkEntry) {
            $this->addUsingAlias(BookkEntryPeer::ID, $bookkEntry->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
