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
use Oppen\ProjectBundle\Model\Contract;
use Oppen\ProjectBundle\Model\Report;
use Oppen\ProjectBundle\Model\Template;
use Oppen\ProjectBundle\Model\TemplatePeer;
use Oppen\ProjectBundle\Model\TemplateQuery;

/**
 * @method TemplateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TemplateQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method TemplateQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method TemplateQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method TemplateQuery orderByAsContract($order = Criteria::ASC) Order by the as_contract column
 * @method TemplateQuery orderByAsReport($order = Criteria::ASC) Order by the as_report column
 * @method TemplateQuery orderByAsBooking($order = Criteria::ASC) Order by the as_booking column
 * @method TemplateQuery orderByAsTransfer($order = Criteria::ASC) Order by the as_transfer column
 * @method TemplateQuery orderByContents($order = Criteria::ASC) Order by the contents column
 * @method TemplateQuery orderByData($order = Criteria::ASC) Order by the data column
 *
 * @method TemplateQuery groupById() Group by the id column
 * @method TemplateQuery groupByName() Group by the name column
 * @method TemplateQuery groupBySymbol() Group by the symbol column
 * @method TemplateQuery groupByType() Group by the type column
 * @method TemplateQuery groupByAsContract() Group by the as_contract column
 * @method TemplateQuery groupByAsReport() Group by the as_report column
 * @method TemplateQuery groupByAsBooking() Group by the as_booking column
 * @method TemplateQuery groupByAsTransfer() Group by the as_transfer column
 * @method TemplateQuery groupByContents() Group by the contents column
 * @method TemplateQuery groupByData() Group by the data column
 *
 * @method TemplateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TemplateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TemplateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TemplateQuery leftJoinReport($relationAlias = null) Adds a LEFT JOIN clause to the query using the Report relation
 * @method TemplateQuery rightJoinReport($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Report relation
 * @method TemplateQuery innerJoinReport($relationAlias = null) Adds a INNER JOIN clause to the query using the Report relation
 *
 * @method TemplateQuery leftJoinContract($relationAlias = null) Adds a LEFT JOIN clause to the query using the Contract relation
 * @method TemplateQuery rightJoinContract($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Contract relation
 * @method TemplateQuery innerJoinContract($relationAlias = null) Adds a INNER JOIN clause to the query using the Contract relation
 *
 * @method Template findOne(PropelPDO $con = null) Return the first Template matching the query
 * @method Template findOneOrCreate(PropelPDO $con = null) Return the first Template matching the query, or a new Template object populated from the query conditions when no match is found
 *
 * @method Template findOneByName(string $name) Return the first Template filtered by the name column
 * @method Template findOneBySymbol(string $symbol) Return the first Template filtered by the symbol column
 * @method Template findOneByType(int $type) Return the first Template filtered by the type column
 * @method Template findOneByAsContract(boolean $as_contract) Return the first Template filtered by the as_contract column
 * @method Template findOneByAsReport(boolean $as_report) Return the first Template filtered by the as_report column
 * @method Template findOneByAsBooking(boolean $as_booking) Return the first Template filtered by the as_booking column
 * @method Template findOneByAsTransfer(boolean $as_transfer) Return the first Template filtered by the as_transfer column
 * @method Template findOneByContents(string $contents) Return the first Template filtered by the contents column
 * @method Template findOneByData(string $data) Return the first Template filtered by the data column
 *
 * @method array findById(int $id) Return Template objects filtered by the id column
 * @method array findByName(string $name) Return Template objects filtered by the name column
 * @method array findBySymbol(string $symbol) Return Template objects filtered by the symbol column
 * @method array findByType(int $type) Return Template objects filtered by the type column
 * @method array findByAsContract(boolean $as_contract) Return Template objects filtered by the as_contract column
 * @method array findByAsReport(boolean $as_report) Return Template objects filtered by the as_report column
 * @method array findByAsBooking(boolean $as_booking) Return Template objects filtered by the as_booking column
 * @method array findByAsTransfer(boolean $as_transfer) Return Template objects filtered by the as_transfer column
 * @method array findByContents(string $contents) Return Template objects filtered by the contents column
 * @method array findByData(string $data) Return Template objects filtered by the data column
 */
abstract class BaseTemplateQuery extends ModelCriteria
{
    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BaseTemplateQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\Template';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TemplateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TemplateQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TemplateQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TemplateQuery) {
            return $criteria;
        }
        $query = new TemplateQuery(null, null, $modelAlias);

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
     * @return   Template|Template[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TemplatePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TemplatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 Template A model object, or null if the key is not found
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
     * @return                 Template A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `contents`, `data` FROM `template` WHERE `id` = :p0';
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
            $obj = new Template();
            $obj->hydrate($row);
            TemplatePeer::addInstanceToPool($obj, (string) $key);
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
     * @return Template|Template[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|Template[]|mixed the list of results, formatted by the current formatter
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
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TemplatePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TemplatePeer::ID, $keys, Criteria::IN);
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
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TemplatePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TemplatePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TemplatePeer::ID, $id, $comparison);
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
     * @return TemplateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplatePeer::NAME, $name, $comparison);
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
     * @return TemplateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplatePeer::SYMBOL, $symbol, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type >= 12
     * $query->filterByType(array('max' => 12)); // WHERE type <= 12
     * </code>
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(TemplatePeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(TemplatePeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TemplatePeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the as_contract column
     *
     * Example usage:
     * <code>
     * $query->filterByAsContract(true); // WHERE as_contract = true
     * $query->filterByAsContract('yes'); // WHERE as_contract = true
     * </code>
     *
     * @param     boolean|string $asContract The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByAsContract($asContract = null, $comparison = null)
    {
        if (is_string($asContract)) {
            $asContract = in_array(strtolower($asContract), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplatePeer::AS_CONTRACT, $asContract, $comparison);
    }

    /**
     * Filter the query on the as_report column
     *
     * Example usage:
     * <code>
     * $query->filterByAsReport(true); // WHERE as_report = true
     * $query->filterByAsReport('yes'); // WHERE as_report = true
     * </code>
     *
     * @param     boolean|string $asReport The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByAsReport($asReport = null, $comparison = null)
    {
        if (is_string($asReport)) {
            $asReport = in_array(strtolower($asReport), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplatePeer::AS_REPORT, $asReport, $comparison);
    }

    /**
     * Filter the query on the as_booking column
     *
     * Example usage:
     * <code>
     * $query->filterByAsBooking(true); // WHERE as_booking = true
     * $query->filterByAsBooking('yes'); // WHERE as_booking = true
     * </code>
     *
     * @param     boolean|string $asBooking The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByAsBooking($asBooking = null, $comparison = null)
    {
        if (is_string($asBooking)) {
            $asBooking = in_array(strtolower($asBooking), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplatePeer::AS_BOOKING, $asBooking, $comparison);
    }

    /**
     * Filter the query on the as_transfer column
     *
     * Example usage:
     * <code>
     * $query->filterByAsTransfer(true); // WHERE as_transfer = true
     * $query->filterByAsTransfer('yes'); // WHERE as_transfer = true
     * </code>
     *
     * @param     boolean|string $asTransfer The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByAsTransfer($asTransfer = null, $comparison = null)
    {
        if (is_string($asTransfer)) {
            $asTransfer = in_array(strtolower($asTransfer), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplatePeer::AS_TRANSFER, $asTransfer, $comparison);
    }

    /**
     * Filter the query on the contents column
     *
     * Example usage:
     * <code>
     * $query->filterByContents('fooValue');   // WHERE contents = 'fooValue'
     * $query->filterByContents('%fooValue%'); // WHERE contents LIKE '%fooValue%'
     * </code>
     *
     * @param     string $contents The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByContents($contents = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($contents)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $contents)) {
                $contents = str_replace('*', '%', $contents);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TemplatePeer::CONTENTS, $contents, $comparison);
    }

    /**
     * Filter the query on the data column
     *
     * Example usage:
     * <code>
     * $query->filterByData('fooValue');   // WHERE data = 'fooValue'
     * $query->filterByData('%fooValue%'); // WHERE data LIKE '%fooValue%'
     * </code>
     *
     * @param     string $data The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function filterByData($data = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($data)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $data)) {
                $data = str_replace('*', '%', $data);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(TemplatePeer::DATA, $data, $comparison);
    }

    /**
     * Filter the query by a related Report object
     *
     * @param   Report|PropelObjectCollection $report  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TemplateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByReport($report, $comparison = null)
    {
        if ($report instanceof Report) {
            return $this
                ->addUsingAlias(TemplatePeer::ID, $report->getTemplateId(), $comparison);
        } elseif ($report instanceof PropelObjectCollection) {
            return $this
                ->useReportQuery()
                ->filterByPrimaryKeys($report->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByReport() only accepts arguments of type Report or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Report relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function joinReport($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Report');

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
            $this->addJoinObject($join, 'Report');
        }

        return $this;
    }

    /**
     * Use the Report relation Report object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Oppen\ProjectBundle\Model\ReportQuery A secondary query class using the current class as primary query
     */
    public function useReportQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinReport($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Report', '\Oppen\ProjectBundle\Model\ReportQuery');
    }

    /**
     * Filter the query by a related Contract object
     *
     * @param   Contract|PropelObjectCollection $contract  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 TemplateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByContract($contract, $comparison = null)
    {
        if ($contract instanceof Contract) {
            return $this
                ->addUsingAlias(TemplatePeer::ID, $contract->getTemplateId(), $comparison);
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
     * @return TemplateQuery The current query, for fluid interface
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
     * @return   \Oppen\ProjectBundle\Model\ContractQuery A secondary query class using the current class as primary query
     */
    public function useContractQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinContract($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Contract', '\Oppen\ProjectBundle\Model\ContractQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   Template $template Object to remove from the list of results
     *
     * @return TemplateQuery The current query, for fluid interface
     */
    public function prune($template = null)
    {
        if ($template) {
            $this->addUsingAlias(TemplatePeer::ID, $template->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }


        return $this->preDelete($con);
    }

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into TemplateArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(TemplatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
    }

}
