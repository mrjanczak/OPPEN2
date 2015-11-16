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
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\FileCatPeer;
use Oppen\ProjectBundle\Model\FileCatQuery;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\Year;

/**
 * @method FileCatQuery orderById($order = Criteria::ASC) Order by the id column
 * @method FileCatQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method FileCatQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method FileCatQuery orderByAsProject($order = Criteria::ASC) Order by the as_project column
 * @method FileCatQuery orderByAsIncome($order = Criteria::ASC) Order by the as_income column
 * @method FileCatQuery orderByAsCost($order = Criteria::ASC) Order by the as_cost column
 * @method FileCatQuery orderByAsContractor($order = Criteria::ASC) Order by the as_contractor column
 * @method FileCatQuery orderByIsLocked($order = Criteria::ASC) Order by the is_locked column
 * @method FileCatQuery orderByYearId($order = Criteria::ASC) Order by the year_id column
 * @method FileCatQuery orderBySubFileCatId($order = Criteria::ASC) Order by the sub_file_cat_id column
 *
 * @method FileCatQuery groupById() Group by the id column
 * @method FileCatQuery groupByName() Group by the name column
 * @method FileCatQuery groupBySymbol() Group by the symbol column
 * @method FileCatQuery groupByAsProject() Group by the as_project column
 * @method FileCatQuery groupByAsIncome() Group by the as_income column
 * @method FileCatQuery groupByAsCost() Group by the as_cost column
 * @method FileCatQuery groupByAsContractor() Group by the as_contractor column
 * @method FileCatQuery groupByIsLocked() Group by the is_locked column
 * @method FileCatQuery groupByYearId() Group by the year_id column
 * @method FileCatQuery groupBySubFileCatId() Group by the sub_file_cat_id column
 *
 * @method FileCatQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method FileCatQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method FileCatQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method FileCatQuery leftJoinYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Year relation
 * @method FileCatQuery rightJoinYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Year relation
 * @method FileCatQuery innerJoinYear($relationAlias = null) Adds a INNER JOIN clause to the query using the Year relation
 *
 * @method FileCatQuery leftJoinSubFileCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the SubFileCat relation
 * @method FileCatQuery rightJoinSubFileCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SubFileCat relation
 * @method FileCatQuery innerJoinSubFileCat($relationAlias = null) Adds a INNER JOIN clause to the query using the SubFileCat relation
 *
 * @method FileCatQuery leftJoinFileCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCat relation
 * @method FileCatQuery rightJoinFileCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCat relation
 * @method FileCatQuery innerJoinFileCat($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCat relation
 *
 * @method FileCatQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method FileCatQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method FileCatQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method FileCatQuery leftJoinDocCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocCat relation
 * @method FileCatQuery rightJoinDocCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocCat relation
 * @method FileCatQuery innerJoinDocCat($relationAlias = null) Adds a INNER JOIN clause to the query using the DocCat relation
 *
 * @method FileCatQuery leftJoinAccountRelatedByFileCatLev1Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByFileCatLev1Id relation
 * @method FileCatQuery rightJoinAccountRelatedByFileCatLev1Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByFileCatLev1Id relation
 * @method FileCatQuery innerJoinAccountRelatedByFileCatLev1Id($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByFileCatLev1Id relation
 *
 * @method FileCatQuery leftJoinAccountRelatedByFileCatLev2Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByFileCatLev2Id relation
 * @method FileCatQuery rightJoinAccountRelatedByFileCatLev2Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByFileCatLev2Id relation
 * @method FileCatQuery innerJoinAccountRelatedByFileCatLev2Id($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByFileCatLev2Id relation
 *
 * @method FileCatQuery leftJoinAccountRelatedByFileCatLev3Id($relationAlias = null) Adds a LEFT JOIN clause to the query using the AccountRelatedByFileCatLev3Id relation
 * @method FileCatQuery rightJoinAccountRelatedByFileCatLev3Id($relationAlias = null) Adds a RIGHT JOIN clause to the query using the AccountRelatedByFileCatLev3Id relation
 * @method FileCatQuery innerJoinAccountRelatedByFileCatLev3Id($relationAlias = null) Adds a INNER JOIN clause to the query using the AccountRelatedByFileCatLev3Id relation
 *
 * @method FileCatQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method FileCatQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method FileCatQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method FileCat findOne(PropelPDO $con = null) Return the first FileCat matching the query
 * @method FileCat findOneOrCreate(PropelPDO $con = null) Return the first FileCat matching the query, or a new FileCat object populated from the query conditions when no match is found
 *
 * @method FileCat findOneByName(string $name) Return the first FileCat filtered by the name column
 * @method FileCat findOneBySymbol(string $symbol) Return the first FileCat filtered by the symbol column
 * @method FileCat findOneByAsProject(boolean $as_project) Return the first FileCat filtered by the as_project column
 * @method FileCat findOneByAsIncome(boolean $as_income) Return the first FileCat filtered by the as_income column
 * @method FileCat findOneByAsCost(boolean $as_cost) Return the first FileCat filtered by the as_cost column
 * @method FileCat findOneByAsContractor(boolean $as_contractor) Return the first FileCat filtered by the as_contractor column
 * @method FileCat findOneByIsLocked(boolean $is_locked) Return the first FileCat filtered by the is_locked column
 * @method FileCat findOneByYearId(int $year_id) Return the first FileCat filtered by the year_id column
 * @method FileCat findOneBySubFileCatId(int $sub_file_cat_id) Return the first FileCat filtered by the sub_file_cat_id column
 *
 * @method array findById(int $id) Return FileCat objects filtered by the id column
 * @method array findByName(string $name) Return FileCat objects filtered by the name column
 * @method array findBySymbol(string $symbol) Return FileCat objects filtered by the symbol column
 * @method array findByAsProject(boolean $as_project) Return FileCat objects filtered by the as_project column
 * @method array findByAsIncome(boolean $as_income) Return FileCat objects filtered by the as_income column
 * @method array findByAsCost(boolean $as_cost) Return FileCat objects filtered by the as_cost column
 * @method array findByAsContractor(boolean $as_contractor) Return FileCat objects filtered by the as_contractor column
 * @method array findByIsLocked(boolean $is_locked) Return FileCat objects filtered by the is_locked column
 * @method array findByYearId(int $year_id) Return FileCat objects filtered by the year_id column
 * @method array findBySubFileCatId(int $sub_file_cat_id) Return FileCat objects filtered by the sub_file_cat_id column
 */
abstract class BaseFileCatQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseFileCatQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\FileCat';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new FileCatQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   FileCatQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return FileCatQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof FileCatQuery) {
            return $criteria;
        }
        $query = new FileCatQuery(null, null, $modelAlias);

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
     * @return   FileCat|FileCat[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = FileCatPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(FileCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 FileCat A model object, or null if the key is not found
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
     * @return                 FileCat A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `symbol`, `as_project`, `as_income`, `as_cost`, `as_contractor`, `is_locked`, `year_id`, `sub_file_cat_id` FROM `file_cat` WHERE `id` = :p0';
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
            $obj = new FileCat();
            $obj->hydrate($row);
            FileCatPeer::addInstanceToPool($obj, (string) $key);
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
     * @return FileCat|FileCat[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|FileCat[]|mixed the list of results, formatted by the current formatter
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
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(FileCatPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(FileCatPeer::ID, $keys, Criteria::IN);
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
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(FileCatPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(FileCatPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FileCatPeer::ID, $id, $comparison);
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
     * @return FileCatQuery The current query, for fluid interface
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

        return $this->addUsingAlias(FileCatPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the symbol column
     *
     * Example usage:
     * <code>
     * $query->filterBySymbol('fooValue');   // WHERE symbol = 'fooValue'
     * $query->filterBySymbol('%fooValue%'); // WHERE symbol LIKE '%fooValue%'
     * </code>
     *
     * @param     string $symbol The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterBySymbol($symbol = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($symbol)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $symbol)) {
                $symbol = str_replace('*', '%', $symbol);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(FileCatPeer::SYMBOL, $symbol, $comparison);
    }

    /**
     * Filter the query on the as_project column
     *
     * Example usage:
     * <code>
     * $query->filterByAsProject(true); // WHERE as_project = true
     * $query->filterByAsProject('yes'); // WHERE as_project = true
     * </code>
     *
     * @param     boolean|string $asProject The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByAsProject($asProject = null, $comparison = null)
    {
        if (is_string($asProject)) {
            $asProject = in_array(strtolower($asProject), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FileCatPeer::AS_PROJECT, $asProject, $comparison);
    }

    /**
     * Filter the query on the as_income column
     *
     * Example usage:
     * <code>
     * $query->filterByAsIncome(true); // WHERE as_income = true
     * $query->filterByAsIncome('yes'); // WHERE as_income = true
     * </code>
     *
     * @param     boolean|string $asIncome The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByAsIncome($asIncome = null, $comparison = null)
    {
        if (is_string($asIncome)) {
            $asIncome = in_array(strtolower($asIncome), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FileCatPeer::AS_INCOME, $asIncome, $comparison);
    }

    /**
     * Filter the query on the as_cost column
     *
     * Example usage:
     * <code>
     * $query->filterByAsCost(true); // WHERE as_cost = true
     * $query->filterByAsCost('yes'); // WHERE as_cost = true
     * </code>
     *
     * @param     boolean|string $asCost The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByAsCost($asCost = null, $comparison = null)
    {
        if (is_string($asCost)) {
            $asCost = in_array(strtolower($asCost), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FileCatPeer::AS_COST, $asCost, $comparison);
    }

    /**
     * Filter the query on the as_contractor column
     *
     * Example usage:
     * <code>
     * $query->filterByAsContractor(true); // WHERE as_contractor = true
     * $query->filterByAsContractor('yes'); // WHERE as_contractor = true
     * </code>
     *
     * @param     boolean|string $asContractor The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByAsContractor($asContractor = null, $comparison = null)
    {
        if (is_string($asContractor)) {
            $asContractor = in_array(strtolower($asContractor), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FileCatPeer::AS_CONTRACTOR, $asContractor, $comparison);
    }

    /**
     * Filter the query on the is_locked column
     *
     * Example usage:
     * <code>
     * $query->filterByIsLocked(true); // WHERE is_locked = true
     * $query->filterByIsLocked('yes'); // WHERE is_locked = true
     * </code>
     *
     * @param     boolean|string $isLocked The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByIsLocked($isLocked = null, $comparison = null)
    {
        if (is_string($isLocked)) {
            $isLocked = in_array(strtolower($isLocked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(FileCatPeer::IS_LOCKED, $isLocked, $comparison);
    }

    /**
     * Filter the query on the year_id column
     *
     * Example usage:
     * <code>
     * $query->filterByYearId(1234); // WHERE year_id = 1234
     * $query->filterByYearId(array(12, 34)); // WHERE year_id IN (12, 34)
     * $query->filterByYearId(array('min' => 12)); // WHERE year_id >= 12
     * $query->filterByYearId(array('max' => 12)); // WHERE year_id <= 12
     * </code>
     *
     * @see       filterByYear()
     *
     * @param     mixed $yearId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterByYearId($yearId = null, $comparison = null)
    {
        if (is_array($yearId)) {
            $useMinMax = false;
            if (isset($yearId['min'])) {
                $this->addUsingAlias(FileCatPeer::YEAR_ID, $yearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearId['max'])) {
                $this->addUsingAlias(FileCatPeer::YEAR_ID, $yearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FileCatPeer::YEAR_ID, $yearId, $comparison);
    }

    /**
     * Filter the query on the sub_file_cat_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubFileCatId(1234); // WHERE sub_file_cat_id = 1234
     * $query->filterBySubFileCatId(array(12, 34)); // WHERE sub_file_cat_id IN (12, 34)
     * $query->filterBySubFileCatId(array('min' => 12)); // WHERE sub_file_cat_id >= 12
     * $query->filterBySubFileCatId(array('max' => 12)); // WHERE sub_file_cat_id <= 12
     * </code>
     *
     * @see       filterBySubFileCat()
     *
     * @param     mixed $subFileCatId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function filterBySubFileCatId($subFileCatId = null, $comparison = null)
    {
        if (is_array($subFileCatId)) {
            $useMinMax = false;
            if (isset($subFileCatId['min'])) {
                $this->addUsingAlias(FileCatPeer::SUB_FILE_CAT_ID, $subFileCatId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subFileCatId['max'])) {
                $this->addUsingAlias(FileCatPeer::SUB_FILE_CAT_ID, $subFileCatId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(FileCatPeer::SUB_FILE_CAT_ID, $subFileCatId, $comparison);
    }

    /**
     * Filter the query by a related Year object
     *
     * @param   Year|PropelObjectCollection $year The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByYear($year, $comparison = null)
    {
        if ($year instanceof Year) {
            return $this
                ->addUsingAlias(FileCatPeer::YEAR_ID, $year->getId(), $comparison);
        } elseif ($year instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FileCatPeer::YEAR_ID, $year->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByYear() only accepts arguments of type Year or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Year relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinYear($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Year');

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
            $this->addJoinObject($join, 'Year');
        }

        return $this;
    }

    /**
     * Use the Year relation Year object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\YearQuery A secondary query class using the current class as primary query
     */
    public function useYearQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinYear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Year', '\Oppen\ProjectBundle\Model\YearQuery');
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySubFileCat($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(FileCatPeer::SUB_FILE_CAT_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(FileCatPeer::SUB_FILE_CAT_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubFileCat() only accepts arguments of type FileCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SubFileCat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinSubFileCat($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SubFileCat');

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
            $this->addJoinObject($join, 'SubFileCat');
        }

        return $this;
    }

    /**
     * Use the SubFileCat relation FileCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useSubFileCatQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubFileCat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SubFileCat', '\Oppen\ProjectBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCat($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $fileCat->getSubFileCatId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            return $this
                ->useFileCatQuery()
                ->filterByPrimaryKeys($fileCat->getPrimaryKeys())
                ->endUse();
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
     * @return FileCatQuery The current query, for fluid interface
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
     * @return   \Oppen\ProjectBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useFileCatQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileCat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileCat', '\Oppen\ProjectBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $file->getFileCatId(), $comparison);
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
     * @return FileCatQuery The current query, for fluid interface
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
     * @return   \Oppen\ProjectBundle\Model\FileQuery A secondary query class using the current class as primary query
     */
    public function useFileQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'File', '\Oppen\ProjectBundle\Model\FileQuery');
    }

    /**
     * Filter the query by a related DocCat object
     *
     * @param   DocCat|PropelObjectCollection $docCat  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDocCat($docCat, $comparison = null)
    {
        if ($docCat instanceof DocCat) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $docCat->getFileCatId(), $comparison);
        } elseif ($docCat instanceof PropelObjectCollection) {
            return $this
                ->useDocCatQuery()
                ->filterByPrimaryKeys($docCat->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDocCat() only accepts arguments of type DocCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocCat relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinDocCat($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocCat');

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
            $this->addJoinObject($join, 'DocCat');
        }

        return $this;
    }

    /**
     * Use the DocCat relation DocCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\DocCatQuery A secondary query class using the current class as primary query
     */
    public function useDocCatQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocCat($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocCat', '\Oppen\ProjectBundle\Model\DocCatQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByFileCatLev1Id($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $account->getFileCatLev1Id(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            return $this
                ->useAccountRelatedByFileCatLev1IdQuery()
                ->filterByPrimaryKeys($account->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAccountRelatedByFileCatLev1Id() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccountRelatedByFileCatLev1Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinAccountRelatedByFileCatLev1Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccountRelatedByFileCatLev1Id');

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
            $this->addJoinObject($join, 'AccountRelatedByFileCatLev1Id');
        }

        return $this;
    }

    /**
     * Use the AccountRelatedByFileCatLev1Id relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountRelatedByFileCatLev1IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccountRelatedByFileCatLev1Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccountRelatedByFileCatLev1Id', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByFileCatLev2Id($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $account->getFileCatLev2Id(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            return $this
                ->useAccountRelatedByFileCatLev2IdQuery()
                ->filterByPrimaryKeys($account->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAccountRelatedByFileCatLev2Id() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccountRelatedByFileCatLev2Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinAccountRelatedByFileCatLev2Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccountRelatedByFileCatLev2Id');

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
            $this->addJoinObject($join, 'AccountRelatedByFileCatLev2Id');
        }

        return $this;
    }

    /**
     * Use the AccountRelatedByFileCatLev2Id relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountRelatedByFileCatLev2IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccountRelatedByFileCatLev2Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccountRelatedByFileCatLev2Id', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByAccountRelatedByFileCatLev3Id($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $account->getFileCatLev3Id(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            return $this
                ->useAccountRelatedByFileCatLev3IdQuery()
                ->filterByPrimaryKeys($account->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByAccountRelatedByFileCatLev3Id() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the AccountRelatedByFileCatLev3Id relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function joinAccountRelatedByFileCatLev3Id($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('AccountRelatedByFileCatLev3Id');

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
            $this->addJoinObject($join, 'AccountRelatedByFileCatLev3Id');
        }

        return $this;
    }

    /**
     * Use the AccountRelatedByFileCatLev3Id relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useAccountRelatedByFileCatLev3IdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinAccountRelatedByFileCatLev3Id($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'AccountRelatedByFileCatLev3Id', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 FileCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProject($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(FileCatPeer::ID, $project->getFileCatId(), $comparison);
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
     * @return FileCatQuery The current query, for fluid interface
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
     * @return   \Oppen\ProjectBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Project', '\Oppen\ProjectBundle\Model\ProjectQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   FileCat $fileCat Object to remove from the list of results
     *
     * @return FileCatQuery The current query, for fluid interface
     */
    public function prune($fileCat = null)
    {
        if ($fileCat) {
            $this->addUsingAlias(FileCatPeer::ID, $fileCat->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
