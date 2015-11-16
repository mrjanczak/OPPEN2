<?php

namespace Oppen\ProjectBundle\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Oppen\ProjectBundle\Model\TemplateArchive;
use Oppen\ProjectBundle\Model\TemplateArchivePeer;
use Oppen\ProjectBundle\Model\TemplateArchiveQuery;

/**
 * @method TemplateArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method TemplateArchiveQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method TemplateArchiveQuery orderBySymbol($order = Criteria::ASC) Order by the symbol column
 * @method TemplateArchiveQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method TemplateArchiveQuery orderByAsContract($order = Criteria::ASC) Order by the as_contract column
 * @method TemplateArchiveQuery orderByAsReport($order = Criteria::ASC) Order by the as_report column
 * @method TemplateArchiveQuery orderByAsBooking($order = Criteria::ASC) Order by the as_booking column
 * @method TemplateArchiveQuery orderByAsTransfer($order = Criteria::ASC) Order by the as_transfer column
 * @method TemplateArchiveQuery orderByContents($order = Criteria::ASC) Order by the contents column
 * @method TemplateArchiveQuery orderByData($order = Criteria::ASC) Order by the data column
 * @method TemplateArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method TemplateArchiveQuery groupById() Group by the id column
 * @method TemplateArchiveQuery groupByName() Group by the name column
 * @method TemplateArchiveQuery groupBySymbol() Group by the symbol column
 * @method TemplateArchiveQuery groupByType() Group by the type column
 * @method TemplateArchiveQuery groupByAsContract() Group by the as_contract column
 * @method TemplateArchiveQuery groupByAsReport() Group by the as_report column
 * @method TemplateArchiveQuery groupByAsBooking() Group by the as_booking column
 * @method TemplateArchiveQuery groupByAsTransfer() Group by the as_transfer column
 * @method TemplateArchiveQuery groupByContents() Group by the contents column
 * @method TemplateArchiveQuery groupByData() Group by the data column
 * @method TemplateArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method TemplateArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method TemplateArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method TemplateArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method TemplateArchive findOne(PropelPDO $con = null) Return the first TemplateArchive matching the query
 * @method TemplateArchive findOneOrCreate(PropelPDO $con = null) Return the first TemplateArchive matching the query, or a new TemplateArchive object populated from the query conditions when no match is found
 *
 * @method TemplateArchive findOneByName(string $name) Return the first TemplateArchive filtered by the name column
 * @method TemplateArchive findOneBySymbol(string $symbol) Return the first TemplateArchive filtered by the symbol column
 * @method TemplateArchive findOneByType(int $type) Return the first TemplateArchive filtered by the type column
 * @method TemplateArchive findOneByAsContract(boolean $as_contract) Return the first TemplateArchive filtered by the as_contract column
 * @method TemplateArchive findOneByAsReport(boolean $as_report) Return the first TemplateArchive filtered by the as_report column
 * @method TemplateArchive findOneByAsBooking(boolean $as_booking) Return the first TemplateArchive filtered by the as_booking column
 * @method TemplateArchive findOneByAsTransfer(boolean $as_transfer) Return the first TemplateArchive filtered by the as_transfer column
 * @method TemplateArchive findOneByContents(string $contents) Return the first TemplateArchive filtered by the contents column
 * @method TemplateArchive findOneByData(string $data) Return the first TemplateArchive filtered by the data column
 * @method TemplateArchive findOneByArchivedAt(string $archived_at) Return the first TemplateArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return TemplateArchive objects filtered by the id column
 * @method array findByName(string $name) Return TemplateArchive objects filtered by the name column
 * @method array findBySymbol(string $symbol) Return TemplateArchive objects filtered by the symbol column
 * @method array findByType(int $type) Return TemplateArchive objects filtered by the type column
 * @method array findByAsContract(boolean $as_contract) Return TemplateArchive objects filtered by the as_contract column
 * @method array findByAsReport(boolean $as_report) Return TemplateArchive objects filtered by the as_report column
 * @method array findByAsBooking(boolean $as_booking) Return TemplateArchive objects filtered by the as_booking column
 * @method array findByAsTransfer(boolean $as_transfer) Return TemplateArchive objects filtered by the as_transfer column
 * @method array findByContents(string $contents) Return TemplateArchive objects filtered by the contents column
 * @method array findByData(string $data) Return TemplateArchive objects filtered by the data column
 * @method array findByArchivedAt(string $archived_at) Return TemplateArchive objects filtered by the archived_at column
 */
abstract class BaseTemplateArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseTemplateArchiveQuery object.
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
            $modelName = 'Oppen\\ProjectBundle\\Model\\TemplateArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new TemplateArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   TemplateArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return TemplateArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof TemplateArchiveQuery) {
            return $criteria;
        }
        $query = new TemplateArchiveQuery(null, null, $modelAlias);

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
     * @return   TemplateArchive|TemplateArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = TemplateArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(TemplateArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 TemplateArchive A model object, or null if the key is not found
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
     * @return                 TemplateArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `name`, `symbol`, `type`, `as_contract`, `as_report`, `as_booking`, `as_transfer`, `contents`, `data`, `archived_at` FROM `template_archive` WHERE `id` = :p0';
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
            $obj = new TemplateArchive();
            $obj->hydrate($row);
            TemplateArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return TemplateArchive|TemplateArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|TemplateArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(TemplateArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(TemplateArchivePeer::ID, $keys, Criteria::IN);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(TemplateArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(TemplateArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TemplateArchivePeer::ID, $id, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplateArchivePeer::NAME, $name, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplateArchivePeer::SYMBOL, $symbol, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(TemplateArchivePeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(TemplateArchivePeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TemplateArchivePeer::TYPE, $type, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByAsContract($asContract = null, $comparison = null)
    {
        if (is_string($asContract)) {
            $asContract = in_array(strtolower($asContract), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplateArchivePeer::AS_CONTRACT, $asContract, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByAsReport($asReport = null, $comparison = null)
    {
        if (is_string($asReport)) {
            $asReport = in_array(strtolower($asReport), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplateArchivePeer::AS_REPORT, $asReport, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByAsBooking($asBooking = null, $comparison = null)
    {
        if (is_string($asBooking)) {
            $asBooking = in_array(strtolower($asBooking), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplateArchivePeer::AS_BOOKING, $asBooking, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByAsTransfer($asTransfer = null, $comparison = null)
    {
        if (is_string($asTransfer)) {
            $asTransfer = in_array(strtolower($asTransfer), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(TemplateArchivePeer::AS_TRANSFER, $asTransfer, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplateArchivePeer::CONTENTS, $contents, $comparison);
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
     * @return TemplateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(TemplateArchivePeer::DATA, $data, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(TemplateArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(TemplateArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(TemplateArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   TemplateArchive $templateArchive Object to remove from the list of results
     *
     * @return TemplateArchiveQuery The current query, for fluid interface
     */
    public function prune($templateArchive = null)
    {
        if ($templateArchive) {
            $this->addUsingAlias(TemplateArchivePeer::ID, $templateArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
