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
use Oppen\ProjectBundle\Model\AccountPeer;
use Oppen\ProjectBundle\Model\AccountQuery;
use Oppen\ProjectBundle\Model\BookkEntry;
use Oppen\ProjectBundle\Model\Cost;
use Oppen\ProjectBundle\Model\DocCat;
use Oppen\ProjectBundle\Model\FileCat;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\Project;
use Oppen\ProjectBundle\Model\Year;

/**
 * @method AccountQuery orderById($order = Criteria::ASC) Order by the id column
 * @method AccountQuery orderByAccNo($order = Criteria::ASC) Order by the acc_no column
 * @method AccountQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method AccountQuery orderByReportSide($order = Criteria::ASC) Order by the report_side column
 * @method AccountQuery orderByAsBankAcc($order = Criteria::ASC) Order by the as_bank_acc column
 * @method AccountQuery orderByAsIncome($order = Criteria::ASC) Order by the as_income column
 * @method AccountQuery orderByAsCost($order = Criteria::ASC) Order by the as_cost column
 * @method AccountQuery orderByIncOpenB($order = Criteria::ASC) Order by the inc_open_b column
 * @method AccountQuery orderByIncCloseB($order = Criteria::ASC) Order by the inc_close_b column
 * @method AccountQuery orderByAsCloseB($order = Criteria::ASC) Order by the as_close_b column
 * @method AccountQuery orderByYearId($order = Criteria::ASC) Order by the year_id column
 * @method AccountQuery orderByFileCatLev1Id($order = Criteria::ASC) Order by the file_cat_lev1_id column
 * @method AccountQuery orderByFileCatLev2Id($order = Criteria::ASC) Order by the file_cat_lev2_id column
 * @method AccountQuery orderByFileCatLev3Id($order = Criteria::ASC) Order by the file_cat_lev3_id column
 * @method AccountQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method AccountQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method AccountQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 *
 * @method AccountQuery groupById() Group by the id column
 * @method AccountQuery groupByAccNo() Group by the acc_no column
 * @method AccountQuery groupByName() Group by the name column
 * @method AccountQuery groupByReportSide() Group by the report_side column
 * @method AccountQuery groupByAsBankAcc() Group by the as_bank_acc column
 * @method AccountQuery groupByAsIncome() Group by the as_income column
 * @method AccountQuery groupByAsCost() Group by the as_cost column
 * @method AccountQuery groupByIncOpenB() Group by the inc_open_b column
 * @method AccountQuery groupByIncCloseB() Group by the inc_close_b column
 * @method AccountQuery groupByAsCloseB() Group by the as_close_b column
 * @method AccountQuery groupByYearId() Group by the year_id column
 * @method AccountQuery groupByFileCatLev1Id() Group by the file_cat_lev1_id column
 * @method AccountQuery groupByFileCatLev2Id() Group by the file_cat_lev2_id column
 * @method AccountQuery groupByFileCatLev3Id() Group by the file_cat_lev3_id column
 * @method AccountQuery groupByTreeLeft() Group by the tree_left column
 * @method AccountQuery groupByTreeRight() Group by the tree_right column
 * @method AccountQuery groupByTreeLevel() Group by the tree_level column
 *
 * @method AccountQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method AccountQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method AccountQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method AccountQuery leftJoinYear($relationAlias = null) Adds a LEFT JOIN clause to the query using the Year relation
 * @method AccountQuery rightJoinYear($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Year relation
 * @method AccountQuery innerJoinYear($relationAlias = null) Adds a INNER JOIN clause to the query using the Year relation
 *
 * @method AccountQuery leftJoinFileCatLev1($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCatLev1 relation
 * @method AccountQuery rightJoinFileCatLev1($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCatLev1 relation
 * @method AccountQuery innerJoinFileCatLev1($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCatLev1 relation
 *
 * @method AccountQuery leftJoinFileCatLev2($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCatLev2 relation
 * @method AccountQuery rightJoinFileCatLev2($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCatLev2 relation
 * @method AccountQuery innerJoinFileCatLev2($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCatLev2 relation
 *
 * @method AccountQuery leftJoinFileCatLev3($relationAlias = null) Adds a LEFT JOIN clause to the query using the FileCatLev3 relation
 * @method AccountQuery rightJoinFileCatLev3($relationAlias = null) Adds a RIGHT JOIN clause to the query using the FileCatLev3 relation
 * @method AccountQuery innerJoinFileCatLev3($relationAlias = null) Adds a INNER JOIN clause to the query using the FileCatLev3 relation
 *
 * @method AccountQuery leftJoinDocCatRelatedByCommitmentAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocCatRelatedByCommitmentAccId relation
 * @method AccountQuery rightJoinDocCatRelatedByCommitmentAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocCatRelatedByCommitmentAccId relation
 * @method AccountQuery innerJoinDocCatRelatedByCommitmentAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the DocCatRelatedByCommitmentAccId relation
 *
 * @method AccountQuery leftJoinDocCatRelatedByTaxCommitmentAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the DocCatRelatedByTaxCommitmentAccId relation
 * @method AccountQuery rightJoinDocCatRelatedByTaxCommitmentAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the DocCatRelatedByTaxCommitmentAccId relation
 * @method AccountQuery innerJoinDocCatRelatedByTaxCommitmentAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the DocCatRelatedByTaxCommitmentAccId relation
 *
 * @method AccountQuery leftJoinBookkEntry($relationAlias = null) Adds a LEFT JOIN clause to the query using the BookkEntry relation
 * @method AccountQuery rightJoinBookkEntry($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BookkEntry relation
 * @method AccountQuery innerJoinBookkEntry($relationAlias = null) Adds a INNER JOIN clause to the query using the BookkEntry relation
 *
 * @method AccountQuery leftJoinProjectRelatedByIncomeAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectRelatedByIncomeAccId relation
 * @method AccountQuery rightJoinProjectRelatedByIncomeAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectRelatedByIncomeAccId relation
 * @method AccountQuery innerJoinProjectRelatedByIncomeAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectRelatedByIncomeAccId relation
 *
 * @method AccountQuery leftJoinProjectRelatedByCostAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectRelatedByCostAccId relation
 * @method AccountQuery rightJoinProjectRelatedByCostAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectRelatedByCostAccId relation
 * @method AccountQuery innerJoinProjectRelatedByCostAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectRelatedByCostAccId relation
 *
 * @method AccountQuery leftJoinProjectRelatedByBankAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the ProjectRelatedByBankAccId relation
 * @method AccountQuery rightJoinProjectRelatedByBankAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the ProjectRelatedByBankAccId relation
 * @method AccountQuery innerJoinProjectRelatedByBankAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the ProjectRelatedByBankAccId relation
 *
 * @method AccountQuery leftJoinIncomeRelatedByBankAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the IncomeRelatedByBankAccId relation
 * @method AccountQuery rightJoinIncomeRelatedByBankAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IncomeRelatedByBankAccId relation
 * @method AccountQuery innerJoinIncomeRelatedByBankAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the IncomeRelatedByBankAccId relation
 *
 * @method AccountQuery leftJoinIncomeRelatedByIncomeAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the IncomeRelatedByIncomeAccId relation
 * @method AccountQuery rightJoinIncomeRelatedByIncomeAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IncomeRelatedByIncomeAccId relation
 * @method AccountQuery innerJoinIncomeRelatedByIncomeAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the IncomeRelatedByIncomeAccId relation
 *
 * @method AccountQuery leftJoinCostRelatedByBankAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostRelatedByBankAccId relation
 * @method AccountQuery rightJoinCostRelatedByBankAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostRelatedByBankAccId relation
 * @method AccountQuery innerJoinCostRelatedByBankAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the CostRelatedByBankAccId relation
 *
 * @method AccountQuery leftJoinCostRelatedByCostAccId($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostRelatedByCostAccId relation
 * @method AccountQuery rightJoinCostRelatedByCostAccId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostRelatedByCostAccId relation
 * @method AccountQuery innerJoinCostRelatedByCostAccId($relationAlias = null) Adds a INNER JOIN clause to the query using the CostRelatedByCostAccId relation
 *
 * @method Account findOne(PropelPDO $con = null) Return the first Account matching the query
 * @method Account findOneOrCreate(PropelPDO $con = null) Return the first Account matching the query, or a new Account object populated from the query conditions when no match is found
 *
 * @method Account findOneByAccNo(string $acc_no) Return the first Account filtered by the acc_no column
 * @method Account findOneByName(string $name) Return the first Account filtered by the name column
 * @method Account findOneByReportSide(int $report_side) Return the first Account filtered by the report_side column
 * @method Account findOneByAsBankAcc(boolean $as_bank_acc) Return the first Account filtered by the as_bank_acc column
 * @method Account findOneByAsIncome(boolean $as_income) Return the first Account filtered by the as_income column
 * @method Account findOneByAsCost(boolean $as_cost) Return the first Account filtered by the as_cost column
 * @method Account findOneByIncOpenB(boolean $inc_open_b) Return the first Account filtered by the inc_open_b column
 * @method Account findOneByIncCloseB(boolean $inc_close_b) Return the first Account filtered by the inc_close_b column
 * @method Account findOneByAsCloseB(boolean $as_close_b) Return the first Account filtered by the as_close_b column
 * @method Account findOneByYearId(int $year_id) Return the first Account filtered by the year_id column
 * @method Account findOneByFileCatLev1Id(int $file_cat_lev1_id) Return the first Account filtered by the file_cat_lev1_id column
 * @method Account findOneByFileCatLev2Id(int $file_cat_lev2_id) Return the first Account filtered by the file_cat_lev2_id column
 * @method Account findOneByFileCatLev3Id(int $file_cat_lev3_id) Return the first Account filtered by the file_cat_lev3_id column
 * @method Account findOneByTreeLeft(int $tree_left) Return the first Account filtered by the tree_left column
 * @method Account findOneByTreeRight(int $tree_right) Return the first Account filtered by the tree_right column
 * @method Account findOneByTreeLevel(int $tree_level) Return the first Account filtered by the tree_level column
 *
 * @method array findById(int $id) Return Account objects filtered by the id column
 * @method array findByAccNo(string $acc_no) Return Account objects filtered by the acc_no column
 * @method array findByName(string $name) Return Account objects filtered by the name column
 * @method array findByReportSide(int $report_side) Return Account objects filtered by the report_side column
 * @method array findByAsBankAcc(boolean $as_bank_acc) Return Account objects filtered by the as_bank_acc column
 * @method array findByAsIncome(boolean $as_income) Return Account objects filtered by the as_income column
 * @method array findByAsCost(boolean $as_cost) Return Account objects filtered by the as_cost column
 * @method array findByIncOpenB(boolean $inc_open_b) Return Account objects filtered by the inc_open_b column
 * @method array findByIncCloseB(boolean $inc_close_b) Return Account objects filtered by the inc_close_b column
 * @method array findByAsCloseB(boolean $as_close_b) Return Account objects filtered by the as_close_b column
 * @method array findByYearId(int $year_id) Return Account objects filtered by the year_id column
 * @method array findByFileCatLev1Id(int $file_cat_lev1_id) Return Account objects filtered by the file_cat_lev1_id column
 * @method array findByFileCatLev2Id(int $file_cat_lev2_id) Return Account objects filtered by the file_cat_lev2_id column
 * @method array findByFileCatLev3Id(int $file_cat_lev3_id) Return Account objects filtered by the file_cat_lev3_id column
 * @method array findByTreeLeft(int $tree_left) Return Account objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return Account objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return Account objects filtered by the tree_level column
 */
abstract class BaseAccountQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseAccountQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\Account';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new AccountQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   AccountQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return AccountQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof AccountQuery) {
            return $criteria;
        }
        $query = new AccountQuery(null, null, $modelAlias);

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
     * @return   Account|Account[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = AccountPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(AccountPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Account A model object, or null if the key is not found
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
     * @return                 Account A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `acc_no`, `name`, `report_side`, `as_bank_acc`, `as_income`, `as_cost`, `inc_open_b`, `inc_close_b`, `as_close_b`, `year_id`, `file_cat_lev1_id`, `file_cat_lev2_id`, `file_cat_lev3_id`, `tree_left`, `tree_right`, `tree_level` FROM `account` WHERE `id` = :p0';
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
            $obj = new Account();
            $obj->hydrate($row);
            AccountPeer::addInstanceToPool($obj, (string) $key);
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
     * @return Account|Account[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Account[]|mixed the list of results, formatted by the current formatter
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
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(AccountPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(AccountPeer::ID, $keys, Criteria::IN);
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
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(AccountPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(AccountPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::ID, $id, $comparison);
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
     * @return AccountQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AccountPeer::ACC_NO, $accNo, $comparison);
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
     * @return AccountQuery The current query, for fluid interface
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

        return $this->addUsingAlias(AccountPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the report_side column
     *
     * Example usage:
     * <code>
     * $query->filterByReportSide(1234); // WHERE report_side = 1234
     * $query->filterByReportSide(array(12, 34)); // WHERE report_side IN (12, 34)
     * $query->filterByReportSide(array('min' => 12)); // WHERE report_side >= 12
     * $query->filterByReportSide(array('max' => 12)); // WHERE report_side <= 12
     * </code>
     *
     * @param     mixed $reportSide The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByReportSide($reportSide = null, $comparison = null)
    {
        if (is_array($reportSide)) {
            $useMinMax = false;
            if (isset($reportSide['min'])) {
                $this->addUsingAlias(AccountPeer::REPORT_SIDE, $reportSide['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($reportSide['max'])) {
                $this->addUsingAlias(AccountPeer::REPORT_SIDE, $reportSide['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::REPORT_SIDE, $reportSide, $comparison);
    }

    /**
     * Filter the query on the as_bank_acc column
     *
     * Example usage:
     * <code>
     * $query->filterByAsBankAcc(true); // WHERE as_bank_acc = true
     * $query->filterByAsBankAcc('yes'); // WHERE as_bank_acc = true
     * </code>
     *
     * @param     boolean|string $asBankAcc The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByAsBankAcc($asBankAcc = null, $comparison = null)
    {
        if (is_string($asBankAcc)) {
            $asBankAcc = in_array(strtolower($asBankAcc), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::AS_BANK_ACC, $asBankAcc, $comparison);
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
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByAsIncome($asIncome = null, $comparison = null)
    {
        if (is_string($asIncome)) {
            $asIncome = in_array(strtolower($asIncome), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::AS_INCOME, $asIncome, $comparison);
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
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByAsCost($asCost = null, $comparison = null)
    {
        if (is_string($asCost)) {
            $asCost = in_array(strtolower($asCost), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::AS_COST, $asCost, $comparison);
    }

    /**
     * Filter the query on the inc_open_b column
     *
     * Example usage:
     * <code>
     * $query->filterByIncOpenB(true); // WHERE inc_open_b = true
     * $query->filterByIncOpenB('yes'); // WHERE inc_open_b = true
     * </code>
     *
     * @param     boolean|string $incOpenB The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByIncOpenB($incOpenB = null, $comparison = null)
    {
        if (is_string($incOpenB)) {
            $incOpenB = in_array(strtolower($incOpenB), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::INC_OPEN_B, $incOpenB, $comparison);
    }

    /**
     * Filter the query on the inc_close_b column
     *
     * Example usage:
     * <code>
     * $query->filterByIncCloseB(true); // WHERE inc_close_b = true
     * $query->filterByIncCloseB('yes'); // WHERE inc_close_b = true
     * </code>
     *
     * @param     boolean|string $incCloseB The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByIncCloseB($incCloseB = null, $comparison = null)
    {
        if (is_string($incCloseB)) {
            $incCloseB = in_array(strtolower($incCloseB), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::INC_CLOSE_B, $incCloseB, $comparison);
    }

    /**
     * Filter the query on the as_close_b column
     *
     * Example usage:
     * <code>
     * $query->filterByAsCloseB(true); // WHERE as_close_b = true
     * $query->filterByAsCloseB('yes'); // WHERE as_close_b = true
     * </code>
     *
     * @param     boolean|string $asCloseB The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByAsCloseB($asCloseB = null, $comparison = null)
    {
        if (is_string($asCloseB)) {
            $asCloseB = in_array(strtolower($asCloseB), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(AccountPeer::AS_CLOSE_B, $asCloseB, $comparison);
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
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByYearId($yearId = null, $comparison = null)
    {
        if (is_array($yearId)) {
            $useMinMax = false;
            if (isset($yearId['min'])) {
                $this->addUsingAlias(AccountPeer::YEAR_ID, $yearId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($yearId['max'])) {
                $this->addUsingAlias(AccountPeer::YEAR_ID, $yearId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::YEAR_ID, $yearId, $comparison);
    }

    /**
     * Filter the query on the file_cat_lev1_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileCatLev1Id(1234); // WHERE file_cat_lev1_id = 1234
     * $query->filterByFileCatLev1Id(array(12, 34)); // WHERE file_cat_lev1_id IN (12, 34)
     * $query->filterByFileCatLev1Id(array('min' => 12)); // WHERE file_cat_lev1_id >= 12
     * $query->filterByFileCatLev1Id(array('max' => 12)); // WHERE file_cat_lev1_id <= 12
     * </code>
     *
     * @see       filterByFileCatLev1()
     *
     * @param     mixed $fileCatLev1Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByFileCatLev1Id($fileCatLev1Id = null, $comparison = null)
    {
        if (is_array($fileCatLev1Id)) {
            $useMinMax = false;
            if (isset($fileCatLev1Id['min'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV1_ID, $fileCatLev1Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileCatLev1Id['max'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV1_ID, $fileCatLev1Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::FILE_CAT_LEV1_ID, $fileCatLev1Id, $comparison);
    }

    /**
     * Filter the query on the file_cat_lev2_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileCatLev2Id(1234); // WHERE file_cat_lev2_id = 1234
     * $query->filterByFileCatLev2Id(array(12, 34)); // WHERE file_cat_lev2_id IN (12, 34)
     * $query->filterByFileCatLev2Id(array('min' => 12)); // WHERE file_cat_lev2_id >= 12
     * $query->filterByFileCatLev2Id(array('max' => 12)); // WHERE file_cat_lev2_id <= 12
     * </code>
     *
     * @see       filterByFileCatLev2()
     *
     * @param     mixed $fileCatLev2Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByFileCatLev2Id($fileCatLev2Id = null, $comparison = null)
    {
        if (is_array($fileCatLev2Id)) {
            $useMinMax = false;
            if (isset($fileCatLev2Id['min'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV2_ID, $fileCatLev2Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileCatLev2Id['max'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV2_ID, $fileCatLev2Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::FILE_CAT_LEV2_ID, $fileCatLev2Id, $comparison);
    }

    /**
     * Filter the query on the file_cat_lev3_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileCatLev3Id(1234); // WHERE file_cat_lev3_id = 1234
     * $query->filterByFileCatLev3Id(array(12, 34)); // WHERE file_cat_lev3_id IN (12, 34)
     * $query->filterByFileCatLev3Id(array('min' => 12)); // WHERE file_cat_lev3_id >= 12
     * $query->filterByFileCatLev3Id(array('max' => 12)); // WHERE file_cat_lev3_id <= 12
     * </code>
     *
     * @see       filterByFileCatLev3()
     *
     * @param     mixed $fileCatLev3Id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByFileCatLev3Id($fileCatLev3Id = null, $comparison = null)
    {
        if (is_array($fileCatLev3Id)) {
            $useMinMax = false;
            if (isset($fileCatLev3Id['min'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV3_ID, $fileCatLev3Id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileCatLev3Id['max'])) {
                $this->addUsingAlias(AccountPeer::FILE_CAT_LEV3_ID, $fileCatLev3Id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::FILE_CAT_LEV3_ID, $fileCatLev3Id, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(AccountPeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(AccountPeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(AccountPeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(AccountPeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(AccountPeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(AccountPeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(AccountPeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query by a related Year object
     *
     * @param   Year|PropelObjectCollection $year The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByYear($year, $comparison = null)
    {
        if ($year instanceof Year) {
            return $this
                ->addUsingAlias(AccountPeer::YEAR_ID, $year->getId(), $comparison);
        } elseif ($year instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountPeer::YEAR_ID, $year->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return AccountQuery The current query, for fluid interface
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
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCatLev1($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV1_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV1_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileCatLev1() only accepts arguments of type FileCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileCatLev1 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinFileCatLev1($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileCatLev1');

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
            $this->addJoinObject($join, 'FileCatLev1');
        }

        return $this;
    }

    /**
     * Use the FileCatLev1 relation FileCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useFileCatLev1Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileCatLev1($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileCatLev1', '\Oppen\ProjectBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCatLev2($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV2_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV2_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileCatLev2() only accepts arguments of type FileCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileCatLev2 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinFileCatLev2($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileCatLev2');

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
            $this->addJoinObject($join, 'FileCatLev2');
        }

        return $this;
    }

    /**
     * Use the FileCatLev2 relation FileCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useFileCatLev2Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileCatLev2($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileCatLev2', '\Oppen\ProjectBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related FileCat object
     *
     * @param   FileCat|PropelObjectCollection $fileCat The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFileCatLev3($fileCat, $comparison = null)
    {
        if ($fileCat instanceof FileCat) {
            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV3_ID, $fileCat->getId(), $comparison);
        } elseif ($fileCat instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(AccountPeer::FILE_CAT_LEV3_ID, $fileCat->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByFileCatLev3() only accepts arguments of type FileCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the FileCatLev3 relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinFileCatLev3($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('FileCatLev3');

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
            $this->addJoinObject($join, 'FileCatLev3');
        }

        return $this;
    }

    /**
     * Use the FileCatLev3 relation FileCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\FileCatQuery A secondary query class using the current class as primary query
     */
    public function useFileCatLev3Query($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinFileCatLev3($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'FileCatLev3', '\Oppen\ProjectBundle\Model\FileCatQuery');
    }

    /**
     * Filter the query by a related DocCat object
     *
     * @param   DocCat|PropelObjectCollection $docCat  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDocCatRelatedByCommitmentAccId($docCat, $comparison = null)
    {
        if ($docCat instanceof DocCat) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $docCat->getCommitmentAccId(), $comparison);
        } elseif ($docCat instanceof PropelObjectCollection) {
            return $this
                ->useDocCatRelatedByCommitmentAccIdQuery()
                ->filterByPrimaryKeys($docCat->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDocCatRelatedByCommitmentAccId() only accepts arguments of type DocCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocCatRelatedByCommitmentAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinDocCatRelatedByCommitmentAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocCatRelatedByCommitmentAccId');

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
            $this->addJoinObject($join, 'DocCatRelatedByCommitmentAccId');
        }

        return $this;
    }

    /**
     * Use the DocCatRelatedByCommitmentAccId relation DocCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\DocCatQuery A secondary query class using the current class as primary query
     */
    public function useDocCatRelatedByCommitmentAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocCatRelatedByCommitmentAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocCatRelatedByCommitmentAccId', '\Oppen\ProjectBundle\Model\DocCatQuery');
    }

    /**
     * Filter the query by a related DocCat object
     *
     * @param   DocCat|PropelObjectCollection $docCat  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByDocCatRelatedByTaxCommitmentAccId($docCat, $comparison = null)
    {
        if ($docCat instanceof DocCat) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $docCat->getTaxCommitmentAccId(), $comparison);
        } elseif ($docCat instanceof PropelObjectCollection) {
            return $this
                ->useDocCatRelatedByTaxCommitmentAccIdQuery()
                ->filterByPrimaryKeys($docCat->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByDocCatRelatedByTaxCommitmentAccId() only accepts arguments of type DocCat or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the DocCatRelatedByTaxCommitmentAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinDocCatRelatedByTaxCommitmentAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('DocCatRelatedByTaxCommitmentAccId');

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
            $this->addJoinObject($join, 'DocCatRelatedByTaxCommitmentAccId');
        }

        return $this;
    }

    /**
     * Use the DocCatRelatedByTaxCommitmentAccId relation DocCat object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\DocCatQuery A secondary query class using the current class as primary query
     */
    public function useDocCatRelatedByTaxCommitmentAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinDocCatRelatedByTaxCommitmentAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'DocCatRelatedByTaxCommitmentAccId', '\Oppen\ProjectBundle\Model\DocCatQuery');
    }

    /**
     * Filter the query by a related BookkEntry object
     *
     * @param   BookkEntry|PropelObjectCollection $bookkEntry  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBookkEntry($bookkEntry, $comparison = null)
    {
        if ($bookkEntry instanceof BookkEntry) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $bookkEntry->getAccountId(), $comparison);
        } elseif ($bookkEntry instanceof PropelObjectCollection) {
            return $this
                ->useBookkEntryQuery()
                ->filterByPrimaryKeys($bookkEntry->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByBookkEntry() only accepts arguments of type BookkEntry or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BookkEntry relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinBookkEntry($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BookkEntry');

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
            $this->addJoinObject($join, 'BookkEntry');
        }

        return $this;
    }

    /**
     * Use the BookkEntry relation BookkEntry object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\BookkEntryQuery A secondary query class using the current class as primary query
     */
    public function useBookkEntryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBookkEntry($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BookkEntry', '\Oppen\ProjectBundle\Model\BookkEntryQuery');
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProjectRelatedByIncomeAccId($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $project->getIncomeAccId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            return $this
                ->useProjectRelatedByIncomeAccIdQuery()
                ->filterByPrimaryKeys($project->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProjectRelatedByIncomeAccId() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProjectRelatedByIncomeAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinProjectRelatedByIncomeAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProjectRelatedByIncomeAccId');

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
            $this->addJoinObject($join, 'ProjectRelatedByIncomeAccId');
        }

        return $this;
    }

    /**
     * Use the ProjectRelatedByIncomeAccId relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectRelatedByIncomeAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProjectRelatedByIncomeAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProjectRelatedByIncomeAccId', '\Oppen\ProjectBundle\Model\ProjectQuery');
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProjectRelatedByCostAccId($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $project->getCostAccId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            return $this
                ->useProjectRelatedByCostAccIdQuery()
                ->filterByPrimaryKeys($project->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProjectRelatedByCostAccId() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProjectRelatedByCostAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinProjectRelatedByCostAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProjectRelatedByCostAccId');

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
            $this->addJoinObject($join, 'ProjectRelatedByCostAccId');
        }

        return $this;
    }

    /**
     * Use the ProjectRelatedByCostAccId relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectRelatedByCostAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProjectRelatedByCostAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProjectRelatedByCostAccId', '\Oppen\ProjectBundle\Model\ProjectQuery');
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProjectRelatedByBankAccId($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $project->getBankAccId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            return $this
                ->useProjectRelatedByBankAccIdQuery()
                ->filterByPrimaryKeys($project->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByProjectRelatedByBankAccId() only accepts arguments of type Project or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the ProjectRelatedByBankAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinProjectRelatedByBankAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('ProjectRelatedByBankAccId');

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
            $this->addJoinObject($join, 'ProjectRelatedByBankAccId');
        }

        return $this;
    }

    /**
     * Use the ProjectRelatedByBankAccId relation Project object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\ProjectQuery A secondary query class using the current class as primary query
     */
    public function useProjectRelatedByBankAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinProjectRelatedByBankAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'ProjectRelatedByBankAccId', '\Oppen\ProjectBundle\Model\ProjectQuery');
    }

    /**
     * Filter the query by a related Income object
     *
     * @param   Income|PropelObjectCollection $income  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncomeRelatedByBankAccId($income, $comparison = null)
    {
        if ($income instanceof Income) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $income->getBankAccId(), $comparison);
        } elseif ($income instanceof PropelObjectCollection) {
            return $this
                ->useIncomeRelatedByBankAccIdQuery()
                ->filterByPrimaryKeys($income->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIncomeRelatedByBankAccId() only accepts arguments of type Income or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IncomeRelatedByBankAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinIncomeRelatedByBankAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IncomeRelatedByBankAccId');

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
            $this->addJoinObject($join, 'IncomeRelatedByBankAccId');
        }

        return $this;
    }

    /**
     * Use the IncomeRelatedByBankAccId relation Income object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\IncomeQuery A secondary query class using the current class as primary query
     */
    public function useIncomeRelatedByBankAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncomeRelatedByBankAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IncomeRelatedByBankAccId', '\Oppen\ProjectBundle\Model\IncomeQuery');
    }

    /**
     * Filter the query by a related Income object
     *
     * @param   Income|PropelObjectCollection $income  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncomeRelatedByIncomeAccId($income, $comparison = null)
    {
        if ($income instanceof Income) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $income->getIncomeAccId(), $comparison);
        } elseif ($income instanceof PropelObjectCollection) {
            return $this
                ->useIncomeRelatedByIncomeAccIdQuery()
                ->filterByPrimaryKeys($income->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIncomeRelatedByIncomeAccId() only accepts arguments of type Income or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IncomeRelatedByIncomeAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinIncomeRelatedByIncomeAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IncomeRelatedByIncomeAccId');

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
            $this->addJoinObject($join, 'IncomeRelatedByIncomeAccId');
        }

        return $this;
    }

    /**
     * Use the IncomeRelatedByIncomeAccId relation Income object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\IncomeQuery A secondary query class using the current class as primary query
     */
    public function useIncomeRelatedByIncomeAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncomeRelatedByIncomeAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IncomeRelatedByIncomeAccId', '\Oppen\ProjectBundle\Model\IncomeQuery');
    }

    /**
     * Filter the query by a related Cost object
     *
     * @param   Cost|PropelObjectCollection $cost  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostRelatedByBankAccId($cost, $comparison = null)
    {
        if ($cost instanceof Cost) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $cost->getBankAccId(), $comparison);
        } elseif ($cost instanceof PropelObjectCollection) {
            return $this
                ->useCostRelatedByBankAccIdQuery()
                ->filterByPrimaryKeys($cost->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCostRelatedByBankAccId() only accepts arguments of type Cost or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostRelatedByBankAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinCostRelatedByBankAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostRelatedByBankAccId');

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
            $this->addJoinObject($join, 'CostRelatedByBankAccId');
        }

        return $this;
    }

    /**
     * Use the CostRelatedByBankAccId relation Cost object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostQuery A secondary query class using the current class as primary query
     */
    public function useCostRelatedByBankAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostRelatedByBankAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostRelatedByBankAccId', '\Oppen\ProjectBundle\Model\CostQuery');
    }

    /**
     * Filter the query by a related Cost object
     *
     * @param   Cost|PropelObjectCollection $cost  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 AccountQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostRelatedByCostAccId($cost, $comparison = null)
    {
        if ($cost instanceof Cost) {
            return $this
                ->addUsingAlias(AccountPeer::ID, $cost->getCostAccId(), $comparison);
        } elseif ($cost instanceof PropelObjectCollection) {
            return $this
                ->useCostRelatedByCostAccIdQuery()
                ->filterByPrimaryKeys($cost->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCostRelatedByCostAccId() only accepts arguments of type Cost or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostRelatedByCostAccId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function joinCostRelatedByCostAccId($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostRelatedByCostAccId');

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
            $this->addJoinObject($join, 'CostRelatedByCostAccId');
        }

        return $this;
    }

    /**
     * Use the CostRelatedByCostAccId relation Cost object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostQuery A secondary query class using the current class as primary query
     */
    public function useCostRelatedByCostAccIdQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostRelatedByCostAccId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostRelatedByCostAccId', '\Oppen\ProjectBundle\Model\CostQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Account $account Object to remove from the list of results
     *
     * @return AccountQuery The current query, for fluid interface
     */
    public function prune($account = null)
    {
        if ($account) {
            $this->addUsingAlias(AccountPeer::ID, $account->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // nested_set behavior

    /**
     * Filter the query to restrict the result to root objects
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function treeRoots()
    {
        return $this->addUsingAlias(AccountPeer::LEFT_COL, 1, Criteria::EQUAL);
    }

    /**
     * Returns the objects in a certain tree, from the tree scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function inTree($scope = null)
    {
        return $this->addUsingAlias(AccountPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     Account $account The object to use for descendant search
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function descendantsOf($account)
    {
        return $this
            ->inTree($account->getScopeValue())
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Account $account The object to use for branch search
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function branchOf($account)
    {
        return $this
            ->inTree($account->getScopeValue())
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     Account $account The object to use for child search
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function childrenOf($account)
    {
        return $this
            ->descendantsOf($account)
            ->addUsingAlias(AccountPeer::LEVEL_COL, $account->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     Account $account The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function siblingsOf($account, PropelPDO $con = null)
    {
        if ($account->isRoot()) {
            return $this->
                add(AccountPeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($account->getParent($con))
                ->prune($account);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     Account $account The object to use for ancestors search
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function ancestorsOf($account)
    {
        return $this
            ->inTree($account->getScopeValue())
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(AccountPeer::RIGHT_COL, $account->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     Account $account The object to use for roots search
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function rootsOf($account)
    {
        return $this
            ->inTree($account->getScopeValue())
            ->addUsingAlias(AccountPeer::LEFT_COL, $account->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(AccountPeer::RIGHT_COL, $account->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(AccountPeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(AccountPeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    AccountQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(AccountPeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(AccountPeer::RIGHT_COL);
        }
    }

    /**
     * Returns a root node for the tree
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     Account The tree root object
     */
    public function findRoot($scope = null, $con = null)
    {
        return $this
            ->addUsingAlias(AccountPeer::LEFT_COL, 1, Criteria::EQUAL)
            ->inTree($scope)
            ->findOne($con);
    }

    /**
     * Returns the root objects for all trees.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return    mixed the list of results, formatted by the current formatter
     */
    public function findRoots($con = null)
    {
        return $this
            ->treeRoots()
            ->find($con);
    }

    /**
     * Returns a tree of objects
     *
     * @param      int $scope		Scope to determine which tree node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($scope = null, $con = null)
    {
        return $this
            ->inTree($scope)
            ->orderByBranch()
            ->find($con);
    }

}
