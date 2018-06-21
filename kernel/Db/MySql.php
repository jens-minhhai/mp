<?php

namespace Kernel\Db;

use PDO;
use PDOException;

class MySql
{
    use JsonTrait;

    private $connection = null;
    private $transactionLevel = 0;
    private $table = [];
    private $join = [];
    private $column = [];
    private $condition = [];
    private $group = [];
    private $having = [];
    private $order = [];
    private $limit = 200;
    private $offset = null;

    private $prefix = '';
    private $log = [];

    private $deletedIndicator = 'deleted';
    private $deleteByIndicator = 'deleted_by';

    public function unlimit()
    {
        $this->limit = $this->unlimit;

        return $this;
    }

    public function alive(bool $positive = true)
    {
        $result = $this->buildAlive($this->deleteByIndicator, $positive);
        array_push($this->condition, $result);

        return $this;
    }

    public function buildAlive(string $column, bool $positive = true)
    {
        $operator = $positive ? '=' : '>';

        return $this->buildWhere($column, 0, $operator);
    }

    public function meta(bool $default = false)
    {
        $shadow = clone $this;
        $query = 'DESCRIBE ' . $shadow->buildTableWithoutAlias();
        $tmp = $shadow->execute($query);

        $result = [];
        foreach ($tmp as $item) {
            $value = null;
            if ($default) {
                $value = ($item['Default'] != 'CURRENT_TIMESTAMP' ? $item['Default'] : date('Y-m-d H:m:s'));
            }
            $result[$item['Field']] = $value;
        }

        return $result;
    }

    public function exclude(array $exclude = [])
    {
        $column = $this->meta();

        foreach ($exclude as $field) {
            unset($column[$field]);
        }

        return $this->field(array_keys($column));
    }

    public function upsert(array $data, array $escape = [], array $field = ['id'])
    {
        $shadow = clone $this;
        $shadow->log = [];

        $existed = $shadow->existed($field);

        if ($shadow->log()) {
            $this->log = array_merge($this->log, $shadow->log());
        }

        if ($existed) {
            return $this->update($data, $escape);
        }

        return $this->insert($data, $escape);
    }

    public function existed(array $field = ['id'])
    {
        $closure = function () use ($field) {
            return $this->field($field);
        };

        $query = $this->buildExist($this, $closure);
        $query = 'SELECT ' . $query . ' AS total';

        $result = $this->execute($query);
        $result = current($result);

        return $result['total'] > 0;
    }

    public function buildExist($orm, $closure = null)
    {
        $query = $closure->call($orm)
            ->unlimit()
            ->buildQuery();

        return 'EXISTS (' . $query . ')';
    }

    public function exist($closure = null)
    {
        $shadow = clone $this;
        $shadow->reset();

        $query = $this->buildExist($shadow, $closure);
        array_push($this->condition, $query);

        return $this;
    }

    public function buildQuery()
    {
        $table = $this->buildTable();
        $column = $this->buildColumn();

        $query = "SELECT {$column} FROM {$table}";

        if ($this->join) {
            $query .= ' ' . $this->buildJoin();
        }

        if ($this->condition) {
            $query .= ' WHERE ' . $this->buildCondition($this->condition);
        }

        if ($this->group) {
            $query .= ' GROUP BY ' . $this->buildGroup();
        }

        if ($this->having) {
            $query .= ' HAVING ' . $this->buildHaving();
        }

        if ($this->order) {
            $query .= ' ORDER BY ' . $this->buildOrder();
        }

        $query .= ' LIMIT ' . $this->buildLimit();

        return $query;
    }

    public function insert(array $data, array $escape = [])
    {
        $table = $this->buildTableWithoutAlias();

        $value = '';
        $column = '';
        foreach ($data as $key => $element) {
            $column .= "`{$key}`, ";

            $type = $escape[$key] ?? '';
            $value .= $this->escape($element, $type) . ', ';
        }

        $column = trim($column, ', ');
        $value = trim($value, ', ');
        $query = "INSERT INTO {$table} ({$column}) VALUES ({$value})";

        return $this->executeNonQuery($query);
    }

    public function lastInsertedId()
    {
        return $this->connection->lastInsertId();
    }

    public function modify(array $data)
    {
        $update = '';
        foreach ($data as $field => $list) {
            $case = '';
            foreach ($list as $condition => $info) {
                $type = $info[2] ?? '';
                $condition = $this->makeDynamic($info[0]);
                $condition = '(' . $this->buildCondition($condition) . ')';

                $case .= "WHEN {$condition} THEN " . $this->escape($info[1], $type) . ' ';
            }
            $update .= "`{$field}` = CASE " . $case . "ELSE `{$field}` END, ";
        }

        $table = $this->buildTableWithoutAlias();

        $condition = '';
        if ($this->condition) {
            $condition = 'WHERE ' . $this->buildCondition($this->condition);
        }

        $update = trim($update, ', ');
        $query = "UPDATE {$table} SET {$update} {$condition}";

        return $this->executeNonQuery($query);
    }

    public function update(array $data, array $escape = [])
    {
        $update = '';
        foreach ($data as $field => $value) {
            $type = $escape[$field] ?? '';
            $update .= "`{$field}` = " . $this->escape($value, $type) . ', ';
        }

        $table = $this->buildTableWithoutAlias();

        $condition = '';
        if ($this->condition) {
            $condition = 'WHERE ' . $this->buildCondition($this->condition);
        }

        $update = trim($update, ', ');
        $query = "UPDATE {$table} SET {$update} {$condition}";

        return $this->executeNonQuery($query);
    }

    public function delete(int $deleted_by = 0)
    {
        $data = [
            $this->deletedIndicator => function () {
                return 'NOW()';
            },
            $this->deleteByIndicator => $deleted_by
        ];

        return $this->update($data);
    }

    public function destroy()
    {
        $table = $this->buildTableWithoutAlias();

        $query = "DELETE FROM {$table}";
        if ($this->condition) {
            $query .= ' WHERE ' . $this->buildCondition($this->condition);
        }

        return $this->executeNonQuery($query);
    }

    public function group(string $column)
    {
        $this->group[$column] = $column;

        return $this;
    }

    protected function buildGroup()
    {
        $result = [];
        foreach ($this->group as $column) {
            $result[] = $this->column($column);
        }

        return implode(', ', $result);
    }

    public function having($condition = null)
    {
        $result = $this->makeDynamic([$condition]);
        array_push($this->having, $this->buildCondition($result));

        return $this;
    }

    protected function buildHaving()
    {
        return implode(' AND ', $this->having);
    }

    public function join(string $table, array $condition = [], string $type = 'inner', string $alias = '')
    {
        $alias = $alias ? $alias : $table;

        $table = $this->table($table);
        $type = mb_strtoupper($type);
        $this->join[] = compact('table', 'condition', 'type', 'alias');

        return $this;
    }

    public function build(string $type = 'where', ...$args)
    {
        $func = 'build' . ucfirst($type);

        return call_user_func_array([$this, $func], $args);
    }

    public function first()
    {
        $data = $this->limit(1)->get();
        if ($data) {
            return current($data);
        }

        return [];
    }

    public function get()
    {
        $query = $this->buildQuery();

        return $this->execute($query);
    }

    public function begin()
    {
        if ($this->transactionLevel == 0) {
            $this->connection->beginTransaction();
            $this->log[] = [
                'query' => 'BEGIN',
                'affected' => 0,
                'time' => 0
            ];
        } else {
            $query = "SAVEPOINT LEVEL{$this->transactionLevel}";
            $this->executeNonQuery($query);
        }

        $this->transactionLevel++;

        return $this;
    }

    public function commit()
    {
        $this->transactionLevel--;

        if ($this->transactionLevel == 0) {
            $this->connection->commit();
            $this->log[] = [
                'query' => 'COMMIT',
                'affected' => 0,
                'time' => 0
            ];
        } else {
            $query = "RELEASE SAVEPOINT LEVEL{$this->transactionLevel}";
            $this->executeNonQuery($query);
        }

        return $this;
    }

    public function rollback()
    {
        $this->transactionLevel--;
        if ($this->transactionLevel == 0) {
            $this->connection->rollback();
            $this->log[] = [
                'query' => 'ROLLBACK',
                'affected' => 0,
                'time' => 0
            ];
        } else {
            $query = "ROLLBACK TO SAVEPOINT LEVEL{$this->transactionLevel}";
            $this->executeNonQuery($query);
        }

        return $this;
    }

    public function reset()
    {
        $this->table = [];
        $this->join = [];
        $this->column = [];
        $this->condition = [];
        $this->group = [];
        $this->having = [];
        $this->order = [];
        $this->limit = 200;
        $this->unlimit = 1000;
        $this->offset = null;

        return $this;
    }

    public function execute(string $query)
    {
        $start = microtime(true);
        $sql = $this->connection->prepare($query);

        $result = [];
        $affected = 0;
        if ($sql->execute()) {
            $affected = $sql->rowCount();
            $result = $sql->fetchAll();
        } else {
            $this->handleError($sql, $query);
        }

        $duration = microtime(true) - $start;

        $this->log[] = [
            'query' => $query,
            'affected' => $affected,
            'time' => round($duration, 5)
        ];

        $this->stop();

        return $result;
    }

    public function executeNonQuery(string $query)
    {
        $start = microtime(true);
        $sql = $this->connection->prepare($query);

        $result = $sql->execute();
        if (!$result) {
            $this->handleError($sql, $query);
        }

        $duration = microtime(true) - $start;

        $this->log[] = [
            'query' => $query,
            'affected' => $sql->rowCount(),
            'time' => round($duration, 5)
        ];

        $this->stop();

        return $result;
    }

    public function limit(int $limit, $offset = null)
    {
        $this->limit = min($this->limit, $limit);
        if (is_null($offset)) {
            $this->offset = $offset;
        }

        return $this;
    }

    public function offset(string $offset)
    {
        $this->offset = $offset;

        return $this;
    }

    public function order(string $column, string $direction = 'ASC')
    {
        $this->order[$column] = $direction;

        return $this;
    }

    public function from(string $table, string $alias = '')
    {
        if (empty($alias)) {
            $alias = $table;
        }

        $table = $this->table($table);
        $this->table = array_merge($this->table, [$alias => $table]);

        return $this;
    }

    public function field(array $column)
    {
        $this->column['column'] = $this->column['column'] ?? [];
        $this->column['column'] = array_merge($this->column['column'], $column);

        return $this;
    }

    public function select(... $column)
    {
        return $this->field($column);
    }

    public function selectString()
    {
        if (!isset($this->column['string'])) {
            $this->column['string'] = [];
        }
        $this->column['string'] = array_merge($this->column['string'], func_get_args());

        return $this;
    }

    public function subquery($column, $orm, string $operator = 'IN')
    {
        $result = $this->buildSubQuery($column, $orm, $operator);

        array_push($this->condition, $result);

        return $this;
    }

    public function buildSubQuery($column, $orm, $operator)
    {
        $shadow = clone $this;
        $shadow->reset();
        $subQuery = $orm->call($shadow)
            ->unlimit()
            ->buildQuery();

        return $this->column($column) . " {$operator} ({$subQuery})";
    }

    public function null(string $column, bool $positive = true)
    {
        $result = $this->buildNull($column, $positive);
        array_push($this->condition, $result);

        return $this;
    }

    public function raw(string $condition)
    {
        array_push($this->condition, $condition);

        return $this;
    }

    protected function buildRaw(string $condition = '')
    {
        return $condition;
    }

    public function or()
    {
        $result = call_user_func_array(array($this, 'buildOr'), func_get_args());
        array_push($this->condition, $result);

        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $result = $this->buildWhere($column, $value, $operator);
        array_push($this->condition, $result);

        return $this;
    }

    public function not($column, $value)
    {
        return $this->where($column, $value, '<>');
    }

    public function in($column, $value, $positive = true)
    {
        $result = $this->buildIn($column, $value, $positive);

        array_push($this->condition, $result);

        return $this;
    }

    public function like($column, $value, $positive = true)
    {
        $result = $this->buildLike($column, $value, $positive);

        array_push($this->condition, $result);

        return $this;
    }

    public function regexColumn($regex = '', $value = '', $operator = 'IN')
    {
        $value = $this->escape($value);
        $regex .= " {$operator} {$value}";

        array_push($this->condition, $regex);

        return $this;
    }

    public function regexValue($column = '', $regex = '', $operator = 'IN')
    {
        $regex = $this->column($column) . " {$operator} {$regex}";

        array_push($this->condition, $regex);

        return $this;
    }

    protected function buildRegex($column, $regex = '', $operator = 'IN')
    {
        return $this->column($column) . " {$operator} {$regex}";
    }

    public function between($column, $min = 0, $max = 0)
    {
        $result = $this->buildBetween($column, $min, $max);
        array_push($this->condition, $result);

        return $this;
    }

    public function inside($column, $min = 0, $max = 0, $equal = false)
    {
        $result = $this->buildInside($column, $min, $max, $equal);
        array_push($this->condition, $result);

        return $this;
    }

    public function outside($column, $min = 0, $max = 0, $equal = false)
    {
        $result = $this->buildOutside($column, $min, $max, $equal);
        array_push($this->condition, $result);

        return $this;
    }

    public function log()
    {
        return $this->log;
    }

    protected function buildOr()
    {
        $result = $this->makeDynamic(func_get_args());

        return '(' . implode($result, ' OR ') . ')';
    }

    protected function buildJoin()
    {
        $query = [];
        foreach ($this->join as $join) {
            $condition = $this->makeDynamic([$join['condition']]);

            $string = "{$join['type']} JOIN {$join['table']} AS `{$join['alias']}` ON " .
                    $this->buildCondition($condition);

            array_push($query, $string);
        }

        return implode(' ', $query);
    }

    protected function buildWhere($column, $value, $operator = '=')
    {
        return $this->column($column) . " {$operator} " . $this->escape($value);
    }

    protected function buildIn($column, $value, $positive = true)
    {
        $operator = $positive ? 'IN' : 'NOT IN';

        return $this->column($column) . " {$operator} (" . implode(', ', $value) . ')';
    }

    protected function buildLike($column, $value, $positive = true)
    {
        $operator = $positive ? 'LIKE' : 'NOT LIKE';

        return $this->column($column) . " {$operator} " . $this->quote($value);
    }

    protected function buildNull($column, $positive = true)
    {
        $operator = $positive ? 'IS' : 'IS NOT';

        return $this->column($column) . " {$operator} NULL";
    }

    protected function buildBetween($column, $min = 0, $max = 0)
    {
        return '(' . $this->column($column) . " BETWEEN {$min} AND {$max})";
    }

    protected function buildInside($column, $min = 0, $max = 0, $equal = false)
    {
        $equal = $equal ? '=' : '';
        $column = $this->column($column);

        return "({$min} <{$equal} {$column} AND {$column} <{$equal} {$max})";
    }

    protected function buildOutside($column, $min = 0, $max = 0, $equal = false)
    {
        $equal = $equal ? '=' : '';
        $column = $this->column($column);

        return "({$column} <{$equal} {$min} OR {$max} <{$equal} {$column})";
    }

    protected function buildOrder()
    {
        $result = [];
        foreach ($this->order as $column => $direction) {
            $result[] = $this->column($column) . " {$direction}";
        }

        return implode(', ', $result);
    }

    protected function buildLimit()
    {
        $result = $this->limit;
        if ($this->offset) {
            $result .= ', ' . $this->offset;
        }

        return $result;
    }

    public function disconnect()
    {
        $attr = ['connection', 'table', 'join', 'column', 'condition', 'group', 'having', 'order', 'limit', 'offset'];
        foreach ($attr as $f) {
            unset($this->$f);
        }
    }

    public function connect(array $config = [])
    {
        if (is_null($this->connection)) {
            $attr = [
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES '{$config['charset']}' COLLATE '{$config['collation']}'"
            ];

            $dsn = $config['driver'] . ':host=' . $config['host'] . ';'
                . 'port=' . $config['port'] . ';'
                . 'dbname=' . $config['database'];

            try {
                $this->connection = new PDO($dsn, $config['username'], $config['password'], $attr);
            } catch (PDOException $e) {
                die('Cannot the connect to Database with PDO.<br /><br />' . $e->getMessage());
            }
        }

        $this->prefix = $config['prefix'] ?? '';
    }

    protected function buildCondition($condition = [])
    {
        return implode(' AND ', $condition);
    }

    protected function buildTable()
    {
        $result = '';
        foreach ($this->table as $alias => $table) {
            $result .= $table . ' AS `' . $alias . '`, ';
        }

        return trim($result, ', ');
    }

    protected function buildTableWithoutAlias()
    {
        $result = '';
        foreach ($this->table as $alias => $table) {
            $result .= $table . ', ';
        }

        return trim($result, ', ');
    }

    protected function buildColumn()
    {
        $result = '';
        extract($this->column);

        foreach ($column as $field) {
            $result .= $this->column($field) . ', ';
        }

        if (isset($string)) {
            $result .= implode(', ', $string);
        }

        return trim($result, ', ');
    }

    protected function makeDynamic(array $criteria)
    {
        $result = [];
        foreach ($criteria as $item) {
            if (is_callable($item)) {
                $condition = [];
                foreach ($item() as $list) {
                    $func = key($list);
                    $params = current($list);

                    array_unshift($params, $func);
                    array_push($condition, call_user_func_array([$this, 'build'], $params));
                }
                $item = '(' . $this->buildCondition($condition) . ')';
            } elseif (is_array($item)) {
                $condition = [];
                $func = key($item);
                $params = current($item);

                array_unshift($params, $func);
                array_push($condition, call_user_func_array([$this, 'build'], $params));

                $item = '(' . $this->buildCondition($condition) . ')';
            }

            array_push($result, $item);
        }

        return $result;
    }

    private function escape($value, string $type = '')
    {
        $raw = [
            'raw',
            'number'
        ];
        if (is_int($value) || is_float($value) || in_array($type, $raw)) {
            return $value;
        }

        if (gettype($value) == 'object') {
            return $value->call($this);
        }

        return $this->quote($value ?? '');
    }

    private function quote(string $string)
    {
        return $this->connection->quote($string);
    }

    private function column(string $column)
    {
        if (mb_strpos($column, '.')) {
            return $column;
        }

        return "`{$column}`";
    }

    private function handleError($sql, string $query)
    {
        $error = $sql->errorInfo();

        array_push($error, $query);
        array_push($error, $this->log);
        container('log')->error(print_r($error, true));

        if (env('APP_ENV') || env('APP_DEBUG')) {
            $text = sprintf('Syntax error: <b>%s</b><br />Error:%s', $query, $error[2]);
            die($text);
        }
        abort(500);
    }

    public function shift()
    {
        $new = (clone $this)->reset();
        $new->anonymous = $this;

        return $new;
    }

    public function stop()
    {
        $this->reset();
        if (!empty($this->anonymous)) {
            $this->anonymous->log = $this->log;
        }
    }

    public function table(string $table)
    {
        return $this->prefix . $table;
    }
}
