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
use Oppen\ProjectBundle\Model\CostDocIncome;
use Oppen\ProjectBundle\Model\CostIncome;
use Oppen\ProjectBundle\Model\File;
use Oppen\ProjectBundle\Model\Income;
use Oppen\ProjectBundle\Model\IncomeDoc;
use Oppen\ProjectBundle\Model\IncomePeer;
use Oppen\ProjectBundle\Model\IncomeQuery;
use Oppen\ProjectBundle\Model\Project;

/**
 * @method IncomeQuery orderById($order = Criteria::ASC) Order by the id column
 * @method IncomeQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method IncomeQuery orderByShortname($order = Criteria::ASC) Order by the shortname column
 * @method IncomeQuery orderByValue($order = Criteria::ASC) Order by the value column
 * @method IncomeQuery orderByComment($order = Criteria::ASC) Order by the comment column
 * @method IncomeQuery orderByShow($order = Criteria::ASC) Order by the show column
 * @method IncomeQuery orderByProjectId($order = Criteria::ASC) Order by the project_id column
 * @method IncomeQuery orderByFileId($order = Criteria::ASC) Order by the file_id column
 * @method IncomeQuery orderByBankAccId($order = Criteria::ASC) Order by the bank_acc_id column
 * @method IncomeQuery orderByIncomeAccId($order = Criteria::ASC) Order by the income_acc_id column
 * @method IncomeQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method IncomeQuery groupById() Group by the id column
 * @method IncomeQuery groupByName() Group by the name column
 * @method IncomeQuery groupByShortname() Group by the shortname column
 * @method IncomeQuery groupByValue() Group by the value column
 * @method IncomeQuery groupByComment() Group by the comment column
 * @method IncomeQuery groupByShow() Group by the show column
 * @method IncomeQuery groupByProjectId() Group by the project_id column
 * @method IncomeQuery groupByFileId() Group by the file_id column
 * @method IncomeQuery groupByBankAccId() Group by the bank_acc_id column
 * @method IncomeQuery groupByIncomeAccId() Group by the income_acc_id column
 * @method IncomeQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method IncomeQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method IncomeQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method IncomeQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method IncomeQuery leftJoinProject($relationAlias = null) Adds a LEFT JOIN clause to the query using the Project relation
 * @method IncomeQuery rightJoinProject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Project relation
 * @method IncomeQuery innerJoinProject($relationAlias = null) Adds a INNER JOIN clause to the query using the Project relation
 *
 * @method IncomeQuery leftJoinFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the File relation
 * @method IncomeQuery rightJoinFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the File relation
 * @method IncomeQuery innerJoinFile($relationAlias = null) Adds a INNER JOIN clause to the query using the File relation
 *
 * @method IncomeQuery leftJoinBankAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the BankAcc relation
 * @method IncomeQuery rightJoinBankAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the BankAcc relation
 * @method IncomeQuery innerJoinBankAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the BankAcc relation
 *
 * @method IncomeQuery leftJoinIncomeAcc($relationAlias = null) Adds a LEFT JOIN clause to the query using the IncomeAcc relation
 * @method IncomeQuery rightJoinIncomeAcc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IncomeAcc relation
 * @method IncomeQuery innerJoinIncomeAcc($relationAlias = null) Adds a INNER JOIN clause to the query using the IncomeAcc relation
 *
 * @method IncomeQuery leftJoinIncomeDoc($relationAlias = null) Adds a LEFT JOIN clause to the query using the IncomeDoc relation
 * @method IncomeQuery rightJoinIncomeDoc($relationAlias = null) Adds a RIGHT JOIN clause to the query using the IncomeDoc relation
 * @method IncomeQuery innerJoinIncomeDoc($relationAlias = null) Adds a INNER JOIN clause to the query using the IncomeDoc relation
 *
 * @method IncomeQuery leftJoinCostIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostIncome relation
 * @method IncomeQuery rightJoinCostIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostIncome relation
 * @method IncomeQuery innerJoinCostIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the CostIncome relation
 *
 * @method IncomeQuery leftJoinCostDocIncome($relationAlias = null) Adds a LEFT JOIN clause to the query using the CostDocIncome relation
 * @method IncomeQuery rightJoinCostDocIncome($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CostDocIncome relation
 * @method IncomeQuery innerJoinCostDocIncome($relationAlias = null) Adds a INNER JOIN clause to the query using the CostDocIncome relation
 *
 * @method Income findOne(PropelPDO $con = null) Return the first Income matching the query
 * @method Income findOneOrCreate(PropelPDO $con = null) Return the first Income matching the query, or a new Income object populated from the query conditions when no match is found
 *
 * @method Income findOneByName(string $name) Return the first Income filtered by the name column
 * @method Income findOneByShortname(string $shortname) Return the first Income filtered by the shortname column
 * @method Income findOneByValue(double $value) Return the first Income filtered by the value column
 * @method Income findOneByComment(string $comment) Return the first Income filtered by the comment column
 * @method Income findOneByShow(boolean $show) Return the first Income filtered by the show column
 * @method Income findOneByProjectId(int $project_id) Return the first Income filtered by the project_id column
 * @method Income findOneByFileId(int $file_id) Return the first Income filtered by the file_id column
 * @method Income findOneByBankAccId(int $bank_acc_id) Return the first Income filtered by the bank_acc_id column
 * @method Income findOneByIncomeAccId(int $income_acc_id) Return the first Income filtered by the income_acc_id column
 * @method Income findOneBySortableRank(int $sortable_rank) Return the first Income filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return Income objects filtered by the id column
 * @method array findByName(string $name) Return Income objects filtered by the name column
 * @method array findByShortname(string $shortname) Return Income objects filtered by the shortname column
 * @method array findByValue(double $value) Return Income objects filtered by the value column
 * @method array findByComment(string $comment) Return Income objects filtered by the comment column
 * @method array findByShow(boolean $show) Return Income objects filtered by the show column
 * @method array findByProjectId(int $project_id) Return Income objects filtered by the project_id column
 * @method array findByFileId(int $file_id) Return Income objects filtered by the file_id column
 * @method array findByBankAccId(int $bank_acc_id) Return Income objects filtered by the bank_acc_id column
 * @method array findByIncomeAccId(int $income_acc_id) Return Income objects filtered by the income_acc_id column
 * @method array findBySortableRank(int $sortable_rank) Return Income objects filtered by the sortable_rank column
 */
abstract class BaseIncomeQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseIncomeQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\Income';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new IncomeQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   IncomeQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return IncomeQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof IncomeQuery) {
            return $criteria;
        }
        $query = new IncomeQuery(null, null, $modelAlias);

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
     * @return   Income|Income[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = IncomePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Income A model object, or null if the key is not found
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
     * @return                 Income A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `shortname`, `value`, `comment`, `show`, `project_id`, `file_id`, `bank_acc_id`, `income_acc_id`, `sortable_rank` FROM `income` WHERE `id` = :p0';
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
            $obj = new Income();
            $obj->hydrate($row);
            IncomePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Income|Income[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Income[]|mixed the list of results, formatted by the current formatter
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
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(IncomePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(IncomePeer::ID, $keys, Criteria::IN);
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
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(IncomePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(IncomePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::ID, $id, $comparison);
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
     * @return IncomeQuery The current query, for fluid interface
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

        return $this->addUsingAlias(IncomePeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the shortname column
     *
     * Example usage:
     * <code>
     * $query->filterByShortname('fooValue');   // WHERE shortname = 'fooValue'
     * $query->filterByShortname('%fooValue%'); // WHERE shortname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $shortname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByShortname($shortname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($shortname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $shortname)) {
                $shortname = str_replace('*', '%', $shortname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IncomePeer::SHORTNAME, $shortname, $comparison);
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
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByValue($value = null, $comparison = null)
    {
        if (is_array($value)) {
            $useMinMax = false;
            if (isset($value['min'])) {
                $this->addUsingAlias(IncomePeer::VALUE, $value['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($value['max'])) {
                $this->addUsingAlias(IncomePeer::VALUE, $value['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::VALUE, $value, $comparison);
    }

    /**
     * Filter the query on the comment column
     *
     * Example usage:
     * <code>
     * $query->filterByComment('fooValue');   // WHERE comment = 'fooValue'
     * $query->filterByComment('%fooValue%'); // WHERE comment LIKE '%fooValue%'
     * </code>
     *
     * @param     string $comment The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByComment($comment = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($comment)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $comment)) {
                $comment = str_replace('*', '%', $comment);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(IncomePeer::COMMENT, $comment, $comparison);
    }

    /**
     * Filter the query on the show column
     *
     * Example usage:
     * <code>
     * $query->filterByShow(true); // WHERE show = true
     * $query->filterByShow('yes'); // WHERE show = true
     * </code>
     *
     * @param     boolean|string $show The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByShow($show = null, $comparison = null)
    {
        if (is_string($show)) {
            $show = in_array(strtolower($show), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(IncomePeer::SHOW, $show, $comparison);
    }

    /**
     * Filter the query on the project_id column
     *
     * Example usage:
     * <code>
     * $query->filterByProjectId(1234); // WHERE project_id = 1234
     * $query->filterByProjectId(array(12, 34)); // WHERE project_id IN (12, 34)
     * $query->filterByProjectId(array('min' => 12)); // WHERE project_id >= 12
     * $query->filterByProjectId(array('max' => 12)); // WHERE project_id <= 12
     * </code>
     *
     * @see       filterByProject()
     *
     * @param     mixed $projectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByProjectId($projectId = null, $comparison = null)
    {
        if (is_array($projectId)) {
            $useMinMax = false;
            if (isset($projectId['min'])) {
                $this->addUsingAlias(IncomePeer::PROJECT_ID, $projectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($projectId['max'])) {
                $this->addUsingAlias(IncomePeer::PROJECT_ID, $projectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::PROJECT_ID, $projectId, $comparison);
    }

    /**
     * Filter the query on the file_id column
     *
     * Example usage:
     * <code>
     * $query->filterByFileId(1234); // WHERE file_id = 1234
     * $query->filterByFileId(array(12, 34)); // WHERE file_id IN (12, 34)
     * $query->filterByFileId(array('min' => 12)); // WHERE file_id >= 12
     * $query->filterByFileId(array('max' => 12)); // WHERE file_id <= 12
     * </code>
     *
     * @see       filterByFile()
     *
     * @param     mixed $fileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByFileId($fileId = null, $comparison = null)
    {
        if (is_array($fileId)) {
            $useMinMax = false;
            if (isset($fileId['min'])) {
                $this->addUsingAlias(IncomePeer::FILE_ID, $fileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($fileId['max'])) {
                $this->addUsingAlias(IncomePeer::FILE_ID, $fileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::FILE_ID, $fileId, $comparison);
    }

    /**
     * Filter the query on the bank_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByBankAccId(1234); // WHERE bank_acc_id = 1234
     * $query->filterByBankAccId(array(12, 34)); // WHERE bank_acc_id IN (12, 34)
     * $query->filterByBankAccId(array('min' => 12)); // WHERE bank_acc_id >= 12
     * $query->filterByBankAccId(array('max' => 12)); // WHERE bank_acc_id <= 12
     * </code>
     *
     * @see       filterByBankAcc()
     *
     * @param     mixed $bankAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByBankAccId($bankAccId = null, $comparison = null)
    {
        if (is_array($bankAccId)) {
            $useMinMax = false;
            if (isset($bankAccId['min'])) {
                $this->addUsingAlias(IncomePeer::BANK_ACC_ID, $bankAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($bankAccId['max'])) {
                $this->addUsingAlias(IncomePeer::BANK_ACC_ID, $bankAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::BANK_ACC_ID, $bankAccId, $comparison);
    }

    /**
     * Filter the query on the income_acc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByIncomeAccId(1234); // WHERE income_acc_id = 1234
     * $query->filterByIncomeAccId(array(12, 34)); // WHERE income_acc_id IN (12, 34)
     * $query->filterByIncomeAccId(array('min' => 12)); // WHERE income_acc_id >= 12
     * $query->filterByIncomeAccId(array('max' => 12)); // WHERE income_acc_id <= 12
     * </code>
     *
     * @see       filterByIncomeAcc()
     *
     * @param     mixed $incomeAccId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterByIncomeAccId($incomeAccId = null, $comparison = null)
    {
        if (is_array($incomeAccId)) {
            $useMinMax = false;
            if (isset($incomeAccId['min'])) {
                $this->addUsingAlias(IncomePeer::INCOME_ACC_ID, $incomeAccId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($incomeAccId['max'])) {
                $this->addUsingAlias(IncomePeer::INCOME_ACC_ID, $incomeAccId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::INCOME_ACC_ID, $incomeAccId, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(IncomePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(IncomePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(IncomePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related Project object
     *
     * @param   Project|PropelObjectCollection $project The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByProject($project, $comparison = null)
    {
        if ($project instanceof Project) {
            return $this
                ->addUsingAlias(IncomePeer::PROJECT_ID, $project->getId(), $comparison);
        } elseif ($project instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomePeer::PROJECT_ID, $project->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return IncomeQuery The current query, for fluid interface
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
     * Filter the query by a related File object
     *
     * @param   File|PropelObjectCollection $file The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByFile($file, $comparison = null)
    {
        if ($file instanceof File) {
            return $this
                ->addUsingAlias(IncomePeer::FILE_ID, $file->getId(), $comparison);
        } elseif ($file instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomePeer::FILE_ID, $file->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return IncomeQuery The current query, for fluid interface
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
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByBankAcc($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(IncomePeer::BANK_ACC_ID, $account->getId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomePeer::BANK_ACC_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByBankAcc() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the BankAcc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function joinBankAcc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('BankAcc');

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
            $this->addJoinObject($join, 'BankAcc');
        }

        return $this;
    }

    /**
     * Use the BankAcc relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useBankAccQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinBankAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'BankAcc', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related Account object
     *
     * @param   Account|PropelObjectCollection $account The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncomeAcc($account, $comparison = null)
    {
        if ($account instanceof Account) {
            return $this
                ->addUsingAlias(IncomePeer::INCOME_ACC_ID, $account->getId(), $comparison);
        } elseif ($account instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(IncomePeer::INCOME_ACC_ID, $account->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByIncomeAcc() only accepts arguments of type Account or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IncomeAcc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function joinIncomeAcc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IncomeAcc');

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
            $this->addJoinObject($join, 'IncomeAcc');
        }

        return $this;
    }

    /**
     * Use the IncomeAcc relation Account object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\AccountQuery A secondary query class using the current class as primary query
     */
    public function useIncomeAccQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncomeAcc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IncomeAcc', '\Oppen\ProjectBundle\Model\AccountQuery');
    }

    /**
     * Filter the query by a related IncomeDoc object
     *
     * @param   IncomeDoc|PropelObjectCollection $incomeDoc  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByIncomeDoc($incomeDoc, $comparison = null)
    {
        if ($incomeDoc instanceof IncomeDoc) {
            return $this
                ->addUsingAlias(IncomePeer::ID, $incomeDoc->getIncomeId(), $comparison);
        } elseif ($incomeDoc instanceof PropelObjectCollection) {
            return $this
                ->useIncomeDocQuery()
                ->filterByPrimaryKeys($incomeDoc->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByIncomeDoc() only accepts arguments of type IncomeDoc or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the IncomeDoc relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function joinIncomeDoc($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('IncomeDoc');

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
            $this->addJoinObject($join, 'IncomeDoc');
        }

        return $this;
    }

    /**
     * Use the IncomeDoc relation IncomeDoc object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\IncomeDocQuery A secondary query class using the current class as primary query
     */
    public function useIncomeDocQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinIncomeDoc($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'IncomeDoc', '\Oppen\ProjectBundle\Model\IncomeDocQuery');
    }

    /**
     * Filter the query by a related CostIncome object
     *
     * @param   CostIncome|PropelObjectCollection $costIncome  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostIncome($costIncome, $comparison = null)
    {
        if ($costIncome instanceof CostIncome) {
            return $this
                ->addUsingAlias(IncomePeer::ID, $costIncome->getIncomeId(), $comparison);
        } elseif ($costIncome instanceof PropelObjectCollection) {
            return $this
                ->useCostIncomeQuery()
                ->filterByPrimaryKeys($costIncome->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCostIncome() only accepts arguments of type CostIncome or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostIncome relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function joinCostIncome($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostIncome');

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
            $this->addJoinObject($join, 'CostIncome');
        }

        return $this;
    }

    /**
     * Use the CostIncome relation CostIncome object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostIncomeQuery A secondary query class using the current class as primary query
     */
    public function useCostIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostIncome', '\Oppen\ProjectBundle\Model\CostIncomeQuery');
    }

    /**
     * Filter the query by a related CostDocIncome object
     *
     * @param   CostDocIncome|PropelObjectCollection $costDocIncome  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 IncomeQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCostDocIncome($costDocIncome, $comparison = null)
    {
        if ($costDocIncome instanceof CostDocIncome) {
            return $this
                ->addUsingAlias(IncomePeer::ID, $costDocIncome->getIncomeId(), $comparison);
        } elseif ($costDocIncome instanceof PropelObjectCollection) {
            return $this
                ->useCostDocIncomeQuery()
                ->filterByPrimaryKeys($costDocIncome->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByCostDocIncome() only accepts arguments of type CostDocIncome or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CostDocIncome relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function joinCostDocIncome($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CostDocIncome');

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
            $this->addJoinObject($join, 'CostDocIncome');
        }

        return $this;
    }

    /**
     * Use the CostDocIncome relation CostDocIncome object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\CostDocIncomeQuery A secondary query class using the current class as primary query
     */
    public function useCostDocIncomeQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCostDocIncome($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CostDocIncome', '\Oppen\ProjectBundle\Model\CostDocIncomeQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Income $income Object to remove from the list of results
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function prune($income = null)
    {
        if ($income) {
            $this->addUsingAlias(IncomePeer::ID, $income->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return IncomeQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {

        IncomePeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    IncomeQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(IncomePeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    IncomeQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(IncomePeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(IncomePeer::RANK_COL));
                break;
            default:
                throw new PropelException('IncomeQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    Income
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param int $scope Scope to determine which objects node to return

     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {


        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param int $scope Scope to determine which objects node to return

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . IncomePeer::RANK_COL . ')');

        IncomePeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     int $scope		The scope value as scalar type or array($value1, ...).

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . IncomePeer::RANK_COL . ')');
        IncomePeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(IncomePeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

}
