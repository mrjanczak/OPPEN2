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
use AppBundle\Model\Account;
use AppBundle\Model\Doc;
use AppBundle\Model\DocCat;
use AppBundle\Model\DocCatPeer;
use AppBundle\Model\DocCatQuery;
use AppBundle\Model\FileCat;
use AppBundle\Model\Year;

/**
 * @method DocCatQuery orderById($order = Criteria::ASC) Order by the id column
 * @method DocCatQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method DocCatQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method DocCatQuery orderByDocNoTmp($order = Criteria::ASC) Order by the doc_no_tmp column
 * @method DocCatQuery orderByAsIncome($order = Criteria::ASC) Order by the as_income column
 * @method DocCatQuery orderByAsCost($order = Criteria::ASC) Order by the as_cost column
 * @method DocCatQuery orderByAsBill($order = Criteria::ASC) Order by the as_bill column
 * @method DocCatQuery orderByIsLocked($order = Criteria::ASC) Order by the is_locked column
 * @method DocCatQuery orderByYearId($order = Criteria::ASC) Order by the year_id column
 * @method DocCatQuery orderByFileCatId($order = Criteria::ASC) Order by the file_cat_id column
 * @method DocCatQuery orderByCommitmentAccId($order = Criteria::ASC) Order by the commitment_acc_id column
 * @method DocCatQuery orderByTaxCommitmentAccId($order = Criteria::ASC) Order by the tax_commitment_acc_id column
 *
 * @method DocCatQuery groupById() Group by the id column
 * @method DocCatQuery groupByName() Group by the name column
 * @method DocCatQuery groupBySymbol() Group by the symbol column
 * @method DocCatQuery groupByDocNoTmp() Group by the doc_no_tmp column
 * @method DocCatQuery groupByAsIncome() Group by the as_income column
 * @method DocCatQuery groupByAsCost() Group by the as_cost column
 * @method DocCatQuery groupByAsBill() Group by the as_bill column
 * @method DocCatQuery groupByIsLocked() Group by the is_locked column
 * @method DocCatQuery groupByYearId() Group by the year_id column
 * @method DocCatQuery groupByFileCatId() Group by the file_cat_id column
 * @method DocCatQuery groupByCommitmentAccId() Group by the commitment_acc_id column
 * @method DocCatQuery groupByTaxCommitmentAccId() Group by the tax_commitment_acc_id column
 *
 * @method DocCatQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method DocCatQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method DocCatQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method DocCatQuery leftJoinYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Year relation
 * @method DocCatQuery rightJoinYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Year relation
 * @method DocCatQuery innerJoinYear($relationAlias = null) Adds a INNER JOIN clause to the query using the Year relation
 *
 * @method DocCatQuery leftJoinFileCat($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCat relation
 * @method DocCatQuery rightJoinFileCat($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCat relation
 * @method DocCatQuery innerJoinFileCat($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCat relation
 *
 * @method DocCatQuery leftJoinCommitmentAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the CommitmentAcc relation
 * @method DocCatQuery rightJoinCommitmentAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CommitmentAcc relation
 * @method DocCatQuery innerJoinCommitmentAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the CommitmentAcc relation
 *
 * @method DocCatQuery leftJoinTaxCommitmentAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the TaxCommitmentAcc relation
 * @method DocCatQuery rightJoinTaxCommitmentAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the TaxCommitmentAcc relation
 * @method DocCatQuery innerJoinTaxCommitmentAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the TaxCommitmentAcc relation
 *
 * @method DocCatQuery leftJoinDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the Doc relation
 * @method DocCatQuery rightJoinDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Doc relation
 * @method DocCatQuery innerJoinDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the Doc relation
 *
 * @method DocCat findOne(PropelPDO $con = null) Return the first DocCat matching the query
 * @method DocCat findOneOrCreate(PropelPDO $con = null) Return the first DocCat matching the query, or a new DocCat object populated from the query conditions when no match is found
 *
 * @method DocCat findOneByName(string $name) Return the first DocCat filtered by the name column
 * @method DocCat findOneBySymbol(string $symbol) Return the first DocCat filtered by the symbol column
 * @method DocCat findOneByDocNoTmp(string $doc_no_tmp) Return the first DocCat filtered by the doc_no_tmp column
 * @method DocCat findOneByAsIncome(boolean $as_income) Return the first DocCat filtered by the as_income column
 * @method DocCat findOneByAsCost(boolean $as_cost) Return the first DocCat filtered by the as_cost column
 * @method DocCat findOneByAsBill(boolean $as_bill) Return the first DocCat filtered by the as_bill column
 * @method DocCat findOneByIsLocked(boolean $is_locked) Return the first DocCat filtered by the is_locked column
 * @method DocCat findOneByYearId(int $year_id) Return the first DocCat filtered by the year_id column
 * @method DocCat findOneByFileCatId(int $file_cat_id) Return the first DocCat filtered by the file_cat_id column
 * @method DocCat findOneByCommitmentAccId(int $commitment_acc_id) Return the first DocCat filtered by the commitment_acc_id column
 * @method DocCat findOneByTaxCommitmentAccId(int $tax_commitment_acc_id) Return the first DocCat filtered by the tax_commitment_acc_id column
 *
 * @method array findById(int $id) Return DocCat objects filtered by the id column
 * @method array findByName(string $name) Return DocCat objects filtered by the name column
 * @method array findBySymbol(string $symbol) Return DocCat objects filtered by the symbol column
 * @method array findByDocNoTmp(string $doc_no_tmp) Return DocCat objects filtered by the doc_no_tmp column
 * @method array findByAsIncome(boolean $as_income) Return DocCat objects filtered by the as_income column
 * @method array findByAsCost(boolean $as_cost) Return DocCat objects filtered by the as_cost column
 * @method array findByAsBill(boolean $as_bill) Return DocCat objects filtered by the as_bill column
 * @method array findByIsLocked(boolean $is_locked) Return DocCat objects filtered by the is_locked column
 * @method array findByYearId(int $year_id) Return DocCat objects filtered by the year_id column
 * @method array findByFileCatId(int $file_cat_id) Return DocCat objects filtered by the file_cat_id column
 * @method array findByCommitmentAccId(int $commitment_acc_id) Return DocCat objects filtered by the commitment_acc_id column
 * @method array findByTaxCommitmentAccId(int $tax_commitment_acc_id) Return DocCat objects filtered by the tax_commitment_acc_id column
 */
abstract class BaseDocCatQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseDocCatQuery object.
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
            $modelName = 'AppBundle\\Model\\DocCat';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new DocCatQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   DocCatQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return DocCatQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof DocCatQuery) {
            return $criteria;
        }
        $query = new DocCatQuery(null, null, $modelAlias);

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
     * @return   DocCat|DocCat[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = DocCatPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(DocCatPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 DocCat A model object, or null if the key is not found
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
     * @return                 DocCat A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `symbol`, `doc_no_tmp`, `as_income`, `as_cost`, `as_bill`, `is_locked`, `year_id`, `file_cat_id`, `commitment_acc_id`, `tax_commitment_acc_id` FROM `doc_cat` WHERE `id` = :p0';
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
            $obj = new DocCat();
            $obj->hydrate($row);
            DocCatPeer::addInstanceToPool($obj, (string) $key);
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
     * @return DocCat|DocCat[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|DocCat[]|mixed the list of results, formatted by the current formatter
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(DocCatPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(DocCatPeer::ID, $keys, Criteria::IN);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(DocCatPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(DocCatPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocCatPeer::ID, $id, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocCatPeer::NAME, $name, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
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

        return $this->addUsingAlias(DocCatPeer::SYMBOL, $symbol, $comparison);
    }

    /**
     * Filter the query on the doc_no_tmp column
     *
     * Example usage:
     * <code>
     * $query->filterByDocNoTmp('fooValue');   // WHERE doc_no_tmp = 'fooValue'
     * $query->filterByDocNoTmp('%fooValue%'); // WHERE doc_no_tmp LIKE '%fooValue%'
     * </code>
     *
     * @param     string $docNoTmp The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByDocNoTmp($docNoTmp = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($docNoTmp)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $docNoTmp)) {
                $docNoTmp = str_replace('*', '%', $docNoTmp);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(DocCatPeer::DOC_NO_TMP, $docNoTmp, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByAsIncome($asIncome = null, $comparison = null)
    {
        if (is_string($asIncome)) {
            $asIncome = in_array(strtolower($asIncome), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DocCatPeer::AS_INCOME, $asIncome, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByAsCost($asCost = null, $comparison = null)
    {
        if (is_string($asCost)) {
            $asCost = in_array(strtolower($asCost), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DocCatPeer::AS_COST, $asCost, $comparison);
    }

    /**
     * Filter the query on the as_bill column
     *
     * Example usage:
     * <code>
     * $query->filterByAsBill(true); // WHERE as_bill = true
     * $query->filterByAsBill('yes'); // WHERE as_bill = true
     * </code>
     *
     * @param     boolean|string $asBill The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByAsBill($asBill = null, $comparison = null)
    {
        if (is_string($asBill)) {
            $asBill = in_array(strtolower($asBill), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DocCatPeer::AS_BILL, $asBill, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByIsLocked($isLocked = null, $comparison = null)
    {
        if (is_string($isLocked)) {
            $isLocked = in_array(strtolower($isLocked), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(DocCatPeer::IS_LOCKED, $isLocked, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByYearId($yearId = null, $comparison = null)
    {
        if (is_array($yearId)) {
            $useMinMax = false;
            if (isset($yearId['min'])) {
                $this->addUsingAlias(DocCatPeer::YEAR_ID, $yearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearId['max'])) {
                $this->addUsingAlias(DocCatPeer::YEAR_ID, $yearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocCatPeer::YEAR_ID, $yearId, $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByFileCatId($fileCatId = null, $comparison = null)
    {
        if (is_array($fileCatId)) {
            $useMinMax = false;
            if (isset($fileCatId['min'])) {
                $this->addUsingAlias(DocCatPeer::FILE_CAT_ID, $fileCatId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileCatId['max'])) {
                $this->addUsingAlias(DocCatPeer::FILE_CAT_ID, $fileCatId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocCatPeer::FILE_CAT_ID, $fileCatId, $comparison);
    }

    /**
     * Filter the query on the commitment_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCommitmentAccId(1234); // WHERE commitment_acc_id = 1234
     * $query->filterByCommitmentAccId(array(12, 34)); // WHERE commitment_acc_id IN (12, 34)
     * $query->filterByCommitmentAccId(array('min' => 12)); // WHERE commitment_acc_id >= 12
     * $query->filterByCommitmentAccId(array('max' => 12)); // WHERE commitment_acc_id <= 12
     * </code>
     *
     * @see       filterByCommitmentAcc()
     *
     * @param     mixed $commitmentAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByCommitmentAccId($commitmentAccId = null, $comparison = null)
    {
        if (is_array($commitmentAccId)) {
            $useMinMax = false;
            if (isset($commitmentAccId['min'])) {
                $this->addUsingAlias(DocCatPeer::COMMITMENT_ACC_ID, $commitmentAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($commitmentAccId['max'])) {
                $this->addUsingAlias(DocCatPeer::COMMITMENT_ACC_ID, $commitmentAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocCatPeer::COMMITMENT_ACC_ID, $commitmentAccId, $comparison);
    }

    /**
     * Filter the query on the tax_commitment_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByTaxCommitmentAccId(1234); // WHERE tax_commitment_acc_id = 1234
     * $query->filterByTaxCommitmentAccId(array(12, 34)); // WHERE tax_commitment_acc_id IN (12, 34)
     * $query->filterByTaxCommitmentAccId(array('min' => 12)); // WHERE tax_commitment_acc_id >= 12
     * $query->filterByTaxCommitmentAccId(array('max' => 12)); // WHERE tax_commitment_acc_id <= 12
     * </code>
     *
     * @see       filterByTaxCommitmentAcc()
     *
     * @param     mixed $taxCommitmentAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function filterByTaxCommitmentAccId($taxCommitmentAccId = null, $comparison = null)
    {
        if (is_array($taxCommitmentAccId)) {
            $useMinMax = false;
            if (isset($taxCommitmentAccId['min'])) {
                $this->addUsingAlias(DocCatPeer::TAX_COMMITMENT_ACC_ID, $taxCommitmentAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($taxCommitmentAccId['max'])) {
                $this->addUsingAlias(DocCatPeer::TAX_COMMITMENT_ACC_ID, $taxCommitmentAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(DocCatPeer::TAX_COMMITMENT_ACC_ID, $taxCommitmentAccId, $comparison);
    }

    /**
     * Filter the query by a related Year object
     *
     * @param   Year|PropelObjectCollection $year The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByYear($year, $comparison = null)
    {
        if ($year instanceof Year) {
            return $this
                ->addUsingAlias(DocCatPeer::YEAR_ID, $year->getId(), $comparison);
        } elseif ($year instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocCatPeer::YEAR_ID, $year->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
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
     * @return   \AppBundle\Model\YearQuery A secondary query class using the current class as primary query
     */
    public function useYearQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinYear($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Year', '\AppBundle\Model\YearQuery');
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCat($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(DocCatPeer::FILE_CAT_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocCatPeer::FILE_CAT_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
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
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCommitmentAcc($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(DocCatPeer::COMMITMENT_ACC_ID, $account->getId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocCatPeer::COMMITMENT_ACC_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCommitmentAcc() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CommitmentAcc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function joinCommitmentAcc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CommitmentAcc');

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
            $this->addJoinObject($join, 'CommitmentAcc');
        }

        return $this;
    }

    /**
     * Use the CommitmentAcc relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useCommitmentAccQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCommitmentAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CommitmentAcc', '\AppBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByTaxCommitmentAcc($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(DocCatPeer::TAX_COMMITMENT_ACC_ID, $account->getId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(DocCatPeer::TAX_COMMITMENT_ACC_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByTaxCommitmentAcc() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the TaxCommitmentAcc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function joinTaxCommitmentAcc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('TaxCommitmentAcc');

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
            $this->addJoinObject($join, 'TaxCommitmentAcc');
        }

        return $this;
    }

    /**
     * Use the TaxCommitmentAcc relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \AppBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useTaxCommitmentAccQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinTaxCommitmentAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'TaxCommitmentAcc', '\AppBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Doc object
     *
     * @param   Doc|PropelObjectCollection $doc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 DocCatQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDoc($doc, $comparison = null)
    {
        if ($doc instanceof Doc) {
            return $this
                ->addUsingAlias(DocCatPeer::ID, $doc->getDocCatId(), $comparison);
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
     * @return DocCatQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   DocCat $docCat Object to remove from the list of results
     *
     * @return DocCatQuery The current query, for fluid interface
     */
    public function prune($docCat = null)
    {
        if ($docCat) {
            $this->addUsingAlias(DocCatPeer::ID, $docCat->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
