<?php
   
function D($table = null) {
    static $DB;
    if (!$DB) {
        $DB = new Db();
    }
    $table or $table = $_ENV['table'] or $table = $_ENV['mod'];
    $DB->table($table);
    return $DB;
}
function i($input) {
    // 开启魔法符号时,无需处理
    if (get_magic_quotes_gpc()) {
        return $input;
    }
    if (is_array($input)) {
        foreach ($input as $k => $v) {
            $k = addslashes($k);
            $input[$k] = i($v);
        }
    } else {
        $input = addslashes($input);
    }
    return $input;
}
function o($output) {
    // 开启魔法符号时,无需处理
    if (get_magic_quotes_gpc()) {
        return $output;
    }
    if (is_array($output)) {
        foreach ($output as $k => $v) {
            $k = stripslashes($k);
            $output[$k] = o($v);
        }
    } else {
        $output = stripslashes($output);
    }
    return $output;
}
function is_assoc($var) {
    if (is_array($var)) {
        $keys = array_keys($var);
        return array_keys($keys) !== $keys;
    }
    return false;
}
/**
 * 数据库管理对象
 * @category	system
 * @package		lib
 */
class Db {

    /**
     * 数据查询参数组
     * @var	array
     */
    private $_;

    /**
     * 字段运算标识符串
     * @var	string
     */
    static $fieldSigns = 'dg|dl|de|eq|gt|ge|lt|le|lk|ik|in|nl|ni';

    /**
     * 最近查询的sql
     * @var	string
     */
    static $sql;

    /**
     * 服务器连接组
     * @var	resource
     */
    static $servers;

    /**
     * 服务器配置组
     * @var	array
     */
    static $conf;

    /**
     * 构造并配置对象
     */
    function __construct($table = null, $serverId = 0, $serverConf = null) {
        self::$conf or self::$conf = &$_ENV['db'];

        // 参数位调整
        if (is_array($table)) {
            $serverId = $table;
            $table = null;
        }
        $this->link($serverId, $serverConf);
        is_null($table) or $this->table($table);
    }

    /**
     * 指定数据库服务器连接
     * @param	string	$serverId     服务器ID
     * @param	string	$serverConf   服务器配置
     * @return Db
     */
    function link($serverId = 0, $serverConf = null) {
        // 数组形式
        if (is_array($serverId)) {
            $serverConf = $serverId;
            $serverId = $serverConf['id'] ? $serverConf['id'] : count(self::$conf);
        }
        if (is_array($serverConf)) {
            self::$conf[$serverId] = $serverConf;
        }
        $this->_['conf'] = &self::$conf[$serverId];
        $this->_['serverId'] = $serverId;
        return $this;
    }

    /**
     * 清空查询参数
     */
    function clear() {
        if (is_array($this->_)) {
            // 保留的参数列表
            $keep = array('lastSql', 'serverId', 'conf');
            // 清除参数
            foreach ($this->_ as $k => $v) {
                if (!in_array($k, $keep)) {
                    unset($this->_[$k]);
                }
            }
        }
        return $this;
    }

    /**
     * 获取sql
     */
    function sql() {
        return $this->_['lastSql'];
    }

    /**
     * 获取当前服务器连接
     * @param		string		$sql	标准SQL语句
     * @return		resource
     */
    function getLink() {
        $serverId = $this->_['serverId'] or $serverId = 0;
        $server = &self::$servers[$serverId];
        // 连接数据库
        if (!$server or $server->ping() == false) {
            self::$conf or self::$conf = &$_ENV['db'];
            $c = &self::$conf[$serverId];
            $c or die("Server#$serverId not found");
            $server = new mysqli($c['host'], $c['user'], $c['pwd'], $c['db'], $c['port']);
            if ($server->connect_errno) {
                die('Connect failed');
            }

            if($server->get_server_info() > '5.0.1') {
                $server->query("SET sql_mode=''");
            }
            // 设置编码
            $server->set_charset($c['charset']);
        }

        
        return $server;
    }
    
    /**
     * 执行SQL
     * @param		string		$sql	标准SQL语句
     * @return		resource
     */
    function cmd($sql) {
        $link = $this->getLink();

        // 记录所执行的SQL
        $this->_['lastSql'] = $sql;

        // 清空SQL拼接参数
        $this->clear();

        // 执行SQL并返回
        $query = $link->query($sql);

        // 调试模式,记录sql
        if ($_ENV['debug']) {
            $_ENV['sql'][] = array('sql' => $sql, 'result' => $query);
        }

        return $query;
    }

    /**
     * 表名配置
     * @param		string		$name	 数据表名（不含前缀）
     * @return		Db
     */
    function table($name) {
        $names = explode(' ', $name);
        // 表全名,含前缀
        $this->_['table'] = $this->t($names[0]);

        // 表别名,缺省值为表逻辑名
        $this->_['tableAlias'] = i($names[1] ? $names[1] : $names[0]);
        return $this;
    }

    /**
     * 获取带有表前缀的表全名
     * @param		string		$name	 数据表名（不含前缀）
     * @return		string
     */
    function t($name) {
        return $this->_['conf']['prefix'] . $name;
    }
    
    /**
     * 指定表主键
     * @param		string		$pk	 表主键
     * @return		Db
     */
    function pk($pk = 'id') {
        $this->_['pk'] = $pk;
        return $this;
    }
    
    /**
     * 获取表主键
     * @return		string
     */
    function getPk() {
        return $this->_['pk'] ? $this->_['pk'] : 'id';
    }

    /**
     * 并表配置
     * 可连续使用多次
     * @param		string		$table	 并表名（不含前缀）
     * @param		string		$field	 并表查询字段
     * @param		string		$on		 并表条件
     * @return		Db
     */
    function join($table, $field = null, $on = null) {
        
        $cfg = array();

        /* 配置表别名 */

        // 字串形式
        if (strpos($table, ' ')) {
            $arr = explode(' ', $table);
            $jTable = $arr[0];
            $cfg['alias'] = $arr[1];
        }
        // 自动指定
        else {
            $jTable = $table;
            $cfg['alias'] = $table;
        }

        /* 配置查询字段 */
        $cfg['field'] = array();

        // 字串转数组
        if (!is_array($field)) {
            $field = explode(',', $field);
        }

        foreach ($field as $k => $f) {
            $f = trim($f);
            if ($f === '') {
                continue;
            }

            // 字段别名
            if (!is_numeric($k)) {
                $f = $k . ' ' . $f;
            }

            // 添加表前缀
            if (!strpos($f, '.')) {
                $cfg['field'][] = $cfg['alias'] . '.' . $f;
            }
        }

        /* 并表条件 */

        // 默认值
        if (is_null($on)) {
            $cfg['on'] = $this->_['tableAlias'] . '.' . $jTable . '_id = ' . $cfg['alias'] . '.id';
        }
        // 字串形式
        elseif (strpos($on, '=')) {
            $cfg['on'] = $on;
        }
        // 仅指定外键(默认主键为id)
        else {
            $cfg['on'] = $this->_['tableAlias'] . '.' . $on . ' = ' . $cfg['alias'] . '.id';
        }
        $this->_['join'][$jTable] = $cfg;
        return $this;
    }

    /**
     * 字段配置
     * <code>
     * 	// 以下参数体等效
     * 	@('id,name');
     * 	@('id',	'name');
     * 	@(array('id', 'name'));
     * 	// 指定字段别名
     * 	@(array('id', 'name' => 'alias'));
     * </code>
     * @param	mixed	$args	数据字段
     * @return	Db
     */
    function field() {
        $args = func_get_args();
        is_array($args[0]) && $args = $args[0];
        foreach ($args as $key => &$arg) {
            // 字段别名
            if (!is_numeric($key)) {
                $arg = $key . ' ' . $arg;
            }
        }
        $this->_['field'] = join(',', $args);
        return $this;
    }

    /**
     * 获取带有表(别名)前缀的字段全名
     * @param	string	$fieldName	字段名
     * @param	string	$tableName	表(别)名
     * @param	int		$flag		补全‘`’符号
     * @return	string
     */
    function prefixedField($fieldName, $tableName = null, $flag = 1) {
        $field = $fieldName;
        // 并表查询 - 字段前缀处理
        if ($this->_['join']) {
            // 有前缀
            if ($tableName) {
                // 未指定别名的表,前缀为表全名
                if (isset($this->_['join'][$tableName])) {
                    $tableName = $this->t($tableName);
                }
            }
            // 无前缀,视为主表字段,自动别名
            else {
                $tableName = $this->_['tableAlias'];
            }
        }
        if ($tableName) {
            $field = $tableName . '.' . $field;
        }
        $flag and $field = '`' . str_replace('.', '`.`', $field) . '`';
        return $field;
    }

    /**
     * LIMIT分页配置
     * <code>
     * 	// 以下参数体等效
     * 	@(3	,10)
     * 	@(array('page' => 3, 'limit' =>	10));
     * </code>
     * @param	int		$page	页码
     * @param	int		$limit	页大小
     * @return	Db
     */
    function page($page, $limit = null) {
        if (is_array($page)) {
            isset($page['limit']) and $limit = $page['limit'];
            isset($page['page']) and $page = $page['page'];
        }
        $this->_['limit'] or $this->_['limit'] = intval(is_null($limit) ? 10 : $limit);
        $this->_['offset'] = ( --$page) * $this->_['limit'];
        return $this;
    }

    /**
     * LIMIT配置
     * <code>
     * 	// 以下参数体等效
     * 	@(10 ,30);
     * 	@((array('offset' => 30, 'limit' =>	10));
     * </code>
     * @param	mixed	$limit		记录数
     * @param	int		$offset		界点，默认为0
     * @return	Db
     */
    function limit($limit, $offset = 0) {
        if (is_array($limit)) {
            isset($limit['offset']) and $offset = $limit['offset'];
            isset($limit['limit']) and $limit = $limit['limit'];
        }
        $this->_['limit'] = (int) $limit;
        $this->_['offset'] = (int) $offset;
        return $this;
    }

    /**
     * 配置排序
     * <code>
     * 	// 以下参数体等效
     * 	@('id desc,	age	asc');
     * 	@('id desc', 'age asc');
     * 	@(array('id desc', 'age asc'));
     * </code>
     * @param		string|array	$args		排序字段（数组）
     * @return		Db
     */
    function order() {
        // 捕捉参数
        $args = func_get_args();
        if (is_array($args[0])) {
            $args = $args[0];
        } elseif (strpos($args[0], ',')) {
            $args = explode(',', $args[0]);
        }

        // 格式校验、排序拼接
        foreach ($args as $key => $val) {
            if (preg_match('/^((\w+)\.)?(\w+)([-\s](desc|asc))?$/i', $val, $matches)) {
                list($x0, $x1, $table, $field, $x2, $type) = $matches;
                $order[] = $this->prefixedField($field, $table) . ' ' . $type;
            }
        }

        // 应用排序
        $order and $this->_['order'] = join(',', $order);
        return $this;
    }

    /**
     * 配置缓存
     * @param	int		$time	周期
     * @param	string	$key	键名,非纯数字
     * @return	bool
     */
    function keep($x = null, $y = null) {
        if (is_numeric($x)) {
            $this->_['cacheKey'] = $y;
            $this->_['cacheTime'] = $x;
        } elseif (is_string($x)) {
            $this->_['cacheKey'] = $x;
            $this->_['cacheTime'] = is_int($y) ? $y : 3600;
        } else {
            $this->_['cacheKey'] = null;
            $this->_['cacheTime'] = 3600;
        }
        return $this;
    }

    /**
     * 综合配置
     * @param	mixed	$opt	配置项
     * @return	Db
     */
    function opt($opt) {
        $pk = $this->getPk();
        if (is_array($opt)) {
            if (is_assoc($opt)) {
                // 分页
                if (isset($opt['p'])) {
                    $this->page($opt['p'], $opt['n']);
                    unset($opt['p'], $opt['n']);
                }

                // LIMIT
                if (isset($opt['n'])) {
                    $this->limit($opt['n'], $opt['s']);
                    unset($opt['s'], $opt['n']);
                }

                // 排序
                if (isset($opt['o'])) {
                    $this->order($opt['o']);
                    unset($opt['o']);
                }

                // 字段
                if (isset($opt['f'])) {
                    $this->field($opt['f']);
                    unset($opt['f']);
                }


                // WHERE
                if ($opt) {
                    $opt = i($opt); // !!数据过滤
                    $where = array();
                    // 字段正则
                    $fieldExp = '/(^(\w+)\.)?(\w+?)(-(' . self::$fieldSigns . '))?$/';
                    foreach ($opt as $key => $val) {
                        preg_match($fieldExp, $key, $matches);
                        // $x0, $x1, 表前缀, 字段名, $x2, 运算符
                        list($x0, $x1, $tableName, $fieldName, $x2, $sign) = $matches;

                        // 获取字段名
                        $field = $this->prefixedField($fieldName, $tableName);

                        // 运算符 - 条件规则转换
                        switch ($sign) {
                            // 等于
                            case 'eq':
                                $where[] = "$field = '$val'";
                                break;
                            // 小于
                            case 'lt':
                                $where[] = "$field < '$val'";
                                break;
                            // 大于
                            case 'gt':
                                $where[] = "$field > '$val'";
                                break;
                            // 小于等于
                            case 'le':
                                $where[] = "$field <= '$val'";
                                break;
                            // 大于等于
                            case 'ge':
                                $where[] = "$field >= '$val'";
                                break;
                            // 时间大于
                            case 'dg':
                                $val = str2time($val);
                                $where[] = "$field >= '$val'";
                                break;
                            // 时间等于
                            case 'de':
                                $val = str2time($val);
                                $where[] = "$field >= '$val'";
                                break;
                            // 时间小于
                            case 'dl':
                                $val = str2time($val);
                                $where[] = "$field <= '$val'";
                                break;
                            // 匹配前缀
                            case 'lk':
                                $where[] = "$field LIKE	'$val%'";
                                break;
                            // 匹配包含
                            case 'ik':
                                $where[] = "$field LIKE	'%$val%'";
                                break;
                            // 匹配空或非空
                            case 'nl':
                                $where[] = "$field IS " . ($val ? 'NOT' : '') . " NULL";
                                break;
                            // IN语句
                            case 'in':
                            // NOT IN语句
                            case 'ni':
                                is_array($val) or $val = explode(',', $val);
                                $val = join("','", array_unique($val));
                                $where[] = "$field " . ($sign == 'ni' ? 'NOT' : '') . " IN('$val')";
                                break;
                            // 等于
                            default:
                                $where[] = "$field = '$val'";
                                break;
                        }
                    }
                    $this->_['where'] = join(' AND ', $where);
                }
            }
            // 整型数组，转换成IN条件语句
            elseif ($opt) {
                $opt = array_unique(array_map('intval', $opt));
                $field = $this->_['join'] ? $this->_['tableAlias'] . '.'.$pk : $pk;
                $this->_['where'] = $field . ' IN(' . join(',', $opt) . ')';
            }
        }
        // 数字，生成快捷id条件查询
        elseif (is_numeric($opt)) {
            /* 10 => "id = 10" */
            $field = $this->_['join'] ? $this->_['tableAlias'] . '.'.$pk : $pk;
            $this->_['where'] = $field . " = {$opt}";
        }
        // 字符串
        elseif (is_string($opt)) {
            // 标准SQL
            if (preg_match('/^(SELECT|SHOW).+/i', $opt)) {
                $this->_['sql'] = $opt;
            }
            // WHERE语句
            else {
                $this->_['where'] = $opt;
            }
        }
        return $this;
    }

    /**
     * 数据查询
     * @param	mixed	$opt		查询配置项
     * @param	mixed	$forcedOpt	强制限定项
     * @param	bool	$count		是否计数
     * @return	array
     */
    function get(/* $opt = null, $forcedOpt = array(), $count = false */) {
        $args = func_get_args();

        // 计数标识
        $countFlag = end($args) === true;

        // 配置条件
        if (isset($args[0]) && !is_bool($args[0])) {
            // 强制限定项
            if (isset($args[1]) && is_array($args[1])) {
                $args[0] = array_merge($args[0], $args[1]);
            }
            $this->opt($args[0]);
        }

        // SQL句首
        $sql = $this->_['sql'];
        $this->_['sql'] = null; // !!置空

        if (!$sql) {
            $this->_['field'] or $this->_['field'] = '*';
            // 并表
            if ($this->_['join']) {
                $joinField = array();
                // 给主表字段加别名
                if ($this->_['field'] == '*') {
                    $joinField = array($this->_['tableAlias'] . '.*');
                }
                // 给主表字段加前缀名
                else {
                    $joinField = explode(',', $this->_['field']);
                    foreach ($joinField as &$f) {
                        if (!strpos($f, '.')) {
                            $f = $this->_['tableAlias'] . '.' . trim($f);
                        }
                    }
                }

                $joinSql = '';
                foreach ($this->_['join'] as $jTable => $j) {
                    $tableName = $this->t($jTable);
                    $joinField = array_merge($joinField, $j['field']);
                    $joinSql .= ' INNER JOIN ' . $tableName;
                    if ($j['alias'] != $tableName) {
                        $joinSql .= ' AS ' . $j['alias'];
                    }
                    $joinSql .= ' ON ' . $j['on'];
                }

                $joinField = join(',', $joinField);
            }

            $sql = 'SELECT ';
            $field = empty($joinField) ? ($this->_['field'] ? i($this->_['field']) : '*') : i($joinField);
            $sql .= $field;

            $sql .= ' FROM ';
            if (!$this->_['table']) {
                return false;
            }
            $sql .= '`' . $this->_['table'] . '`';

            if (isset($joinSql)) {
                $sql .= ' AS ' . $this->_['tableAlias'] . ' ' . i($joinSql) . ' ';
            }
        }

        // 条件
        if ($this->_['where']) {
            $sql .= ' WHERE ' . $this->_['where'];
        }

        // 排序
        if ($this->_['order'] && !stripos($sql, 'ORDER BY	')) {
            $sql .= ' ORDER BY ' . $this->_['order'];
        }

        // 数目
        if ($this->_['limit'] || $countFlag) {
            $this->_['limit'] || $this->_['limit'] = 10;
            $this->_['offset'] || $this->_['offset'] = 0;
            $sql .= ' LIMIT ' . $this->_['offset'] . ',' . $this->_['limit'];
        }

        // 初始化查询结果
        $result = false;

        // 获取缓存配置
        $cacheKey = $this->_['cacheKey'];
        $cacheTime = $this->_['cacheTime'];
        $this->_['cacheKey'] = $this->_['cacheTime'] = null; // !!置空
        // 读取SQL对应的缓存
        if ($cacheTime) {
            if (!$cacheKey) {
                $cacheKey = md5($countFlag . $sql);
            }
            $result = Mem($cacheKey);
        }

        // 若缓存失效，从数据库中读取
        if ($result === null || $result === false) {
            // 保留计数参数
            if ($countFlag) {
                $limit = $this->_['limit'];
                $offset = $this->_['offset'];
            }
            // 获取数据
            if ($query = $this->cmd($sql)) {
                if (PHP_VERSION > 5.3) {
                    $result = $query->fetch_all(MYSQLI_ASSOC);
                } else {
                    while ($tmp = $query->fetch_assoc()) {
                        $result[] = $tmp;
                    }
                }
            }
            $result || $result = array();
            // 计数
            if ($countFlag) {
                $count = $this->count($sql);
                $result = array(
                    'data' => $result,
                    'count' => $count,
                    'limit' => $limit,
                    'page' => ($offset / $limit) + 1,
                );
                $this->_['lastSql'] = $sql; // !!重置
            }
            // 将读取的数据更新到缓存中
            if ($cacheKey) {
                Mem($cacheKey, $result, $cacheTime);
            }
        }
        // !!清空缓存查询参数
        if ($cacheKey) {
            $this->clear();
        }
        return o($result);
    }

    /**
     * 单条数据查询
     * @param	string|array	$opt	查询配置项（数组）
     * @return	array
     */
    function one($opt = null) {
        $this->_['limit'] = 1;
        $items = $this->get($opt);
        if ($items) {
            return $items[0];
        }
        return array();
    }

    /**
     * 字段最大值查询
     * @param	string|array	$opt	查询配置
     * @param	string|array	$field	字段名
     * @return	array
     */
    function max($field = 'id', $opt = null) {
        $this->field('max(' . i($field) . ')');
        $entries = $this->get($opt);
        return current($entries[0]);
    }

    /**
     * 字段最小值查询
     * @param	string|array	$opt	查询配置
     * @param	string|array	$field	字段名
     * @return	array
     */
    function min($field = 'id', $opt = null) {
        $this->field('min(' . i($field) . ')');
        $entries = $this->get($opt);
        return current($entries[0]);
    }

    /**
     * 字段平均值查询
     * @param	string|array	$opt	查询配置
     * @param	string|array	$field	字段名
     * @return	array
     */
    function avg($field = 'id', $opt = null) {
        $this->field('avg(' . i($field) . ')');
        $entries = $this->get($opt);
        return current($entries[0]);
    }

    /**
     * 字段汇总值查询
     * @param	string|array	$opt	查询配置
     * @param	string|array	$field	字段名
     * @return	array
     */
    function sum($field = 'id', $opt = null) {
        $this->field('sum(' . i($field) . ')');
        $entries = $this->get($opt);
        return current($entries[0]);
    }

    /**
     * 单记录单字段查询
     * @param	string|array	$opt	查询配置
     * @param	string|array	$field	字段名
     * @return	array
     */
    function val($opt = null, $field = null) {
        $field && $this->field($field);
        $one = $this->one($opt);
        return $one ? current($one) : '';
    }

    /**
     * 返回记录列表中指定的元素列
     * @param   string   $arrList   数组列表
     * @param   string   $col       列名称
     * @param   string   $key       索引键,选填
     * @return array
     */
    function col($opt, $col = null, $key = null) {
        if ($col and $key) {
            $this->field($col, $key);
        }
        $data = $this->get($opt);
        return col($data, $col, $key);
    }

    /**
     * 数据更新(添加)
     * @param	array	$data	待写入的数据数组
     * @param	mixed	$where	更新条件，为空时则进行添加操作
     * @return	bool
     */
    function set($data = null, $where = null) {
        // 表主键
        $pk = $this->getPk();
        // 事先获取表名,避免函数执行期间被篡改
        $table = $this->_['table'];

        if (is_null($data)) {
            // 赋值器数组
            if (is_array($this->_['data'])) {
                $data = $this->_['data'];
            }
            // 自动数据校验
            elseif ($_POST) {
                $where = true;
                $data = Conf::filter($_POST);
            }
        }
        // 配置更新条件
        $where && $this->opt($where);

        // 若id字段非空,则标记为更新动作
        if ($where === true) {
            if (is_numeric($data[$pk])) {
                $id = (int) $data[$pk];
                $this->_['where'] = "$pk = $id";
            }
            unset($data[$pk]);
        }

        $where = $this->_['where'];
        $sql = $where ? 'UPDATE ' : 'INSERT INTO ';
        $sql .= '`' . $table . '` SET ';

        foreach ($data as $key => $val) {
            $key = i($key);
            if (is_null($val)) {
                $sql .= "`{$key}` = NULL,";
            } elseif(strpos($key,'-')){
                list($key, $sign) = explode('-',$key);
                $val = i($val);
                switch($sign){
                    case 'append':
                        $sql .= "`{$key}` = concat( ifnull(`{$key}`, ''),'{$val}'),"; 
                        break;
                }
            }else{
                $val = i($val);
                // 压缩非标量类型数据
                if (!is_scalar($val)) {
                    $val = serialize($val);
                }
                $sql .= "`{$key}` = '{$val}',";
            }
        }

        $sql = rtrim($sql, ',');

        if ($where) {
            $sql .= ' WHERE ' . $where;
        }
        $flag = $this->cmd($sql);

        if ($where) {
            // 优先返回记录id
            if ($flag && isset($id)) {
                $flag = $id;
            }
        } else {
            $flag = $this->getLink()->insert_id;
        }

        return (int) $flag;
    }

    /**
     * 批量数据添加
     * @param	array	$arrList	数据记录队列
     * @return	bool
     */
    function bulkAdd($arrList, $replaceMode = 1) {
        if (!is_array($arrList)) {
            return false;
        }

        // 数据过滤
        $arrList = i($arrList);

        // 拼装SQL
        $cmd = $replaceMode ? 'REPLACE' : 'INSERT';
        $sql = "{$cmd}	INTO `{$this->_['table']}`(`";
        $sql .= join('`,`', array_keys(current($arrList)));
        $sql .= "`)VALUES";
        foreach ($arrList as $arr) {
            $sql .= '("' . join('","', $arr) . '"),';
        }
        $sql = rtrim($sql, ',');

        return $this->cmd($sql);
    }

    /**
     * 数据更新(REPLACE方式)
     * @param	array	$data	待写数组
     * @return	bool
     */
    function replace($data) {
        $sql = 'REPLACE INTO ';
        $sql .= '`' . $this->_['table'] . '`	SET	';
        foreach ($data as $key => $val) {
            $key = i($key);
            $val = i($val);
            $sql .= "`{$key}` =	'{$val}',";
        }
        $sql = rtrim($sql, ',');
        return $this->cmd($sql);
    }

    /**
     * 数据删除
     * @param	mixed	$opt	删除条件
     * @return	bool
     */
    function del($opt = null) {
        is_null($opt) || $this->opt($opt);
        $sql = 'DELETE FROM	' . $this->_['table'];
        if ($this->_['where']) {
            $sql .= ' WHERE	' . $this->_['where'];
            return $this->cmd($sql);
        }
        return false;
    }

    /**
     * 清空数据表
     * @return	bool
     */
    function flush() {
        $sql = 'TRUNCATE TABLE ' . $this->_['table'];
        return $this->cmd($sql);
    }

    /**
     * 记录计数
     * @param	mixed	$opt	统计条件
     * @return	int
     */
    function count() {
        $args = func_get_args();

        // 配置条件
        !isset($args[0]) or is_bool($args[0]) or $this->opt($args[0]);

        // 强制计数转换
        
        if ($this->_['sql']) {
            $sql = preg_replace(
                array('/(SELECT\s+)([\s\S]+?)(\s+FROM\s+)/i', '/(ORDER\s+BY.+)?(\s+LIMIT\s+.+)/i'), 
                array('$1COUNT(0) AS num$3', ''), 
                $this->_['sql']
            );
        }
        // 拼装SQL
        else {
            $sql = 'SELECT COUNT(0) AS num FROM ' . $this->_['table'];
            if($this->_['where']){
                $sql .= ' WHERE ' . $this->_['where'];
                $this->_['where'] = null;
            }
        }
        if ($item = $this->one($sql)) {
            return current($item);
        }
        return 0;
    }

    /**
     * 计数增减
     * <code>
     * 	@('score' ,-2);
     * 	@(array('score' => 10, 'level' => 1));
     * </code>
     * @param   mixed   $field  待增减字段
     * @param   int     $step   增减数
     * @return  bool
     */
    function step($field, $step = null, $opt = null) {
        $item = array();
        // 数组参数
        if (is_array($field)) {
            $item = $field;
            // 参数二为筛选条件
            is_null($step) or $this->opt($step);
        }
        // 标量参数
        else {
            // 默认步长为1
            is_null($step) and $step = 1;
            // 参数二为筛选条件
            is_null($opt) || $this->opt($opt);
            $item[$field] = $step;
        }
        // 组装步段
        foreach ($item as $key => $val) {
            $val = (int) $val;
            if ($val > 0) {
                $val = '+' . $val;
            }
            $steps[] = '`' . $key . '`=`' . $key . '`' . $val;
        }
        // 拼接SQL
        if (isset($steps)) {
            $sql = 'UPDATE ' . $this->_['table'] . "	SET	" . join(',', $steps);
            if ($this->_['where']) {
                $sql .= ' WHERE ' . $this->_['where'];
            }
            return $this->cmd($sql);
        }
        return false;
    }

    /**
     * 获取表字段结构信息
     * @param	mixed	$table	表名
     * @return	array
     */
    function cols($table = null) {
        $table = $table ? $this->t($table) : $this->_['table'];
        return $this->get("SHOW FULL COLUMNS FROM `" . $table . '`');
    }

    /**
     * 获取制表SQL
     * @param	mixed	$table	表名
     * @return	array
     */
    function tab($table = null) {
        $table = $table ? $this->t($table) : $this->_['table'];
        $info = $this->get("SHOW CREATE TABLE `" . $table . '`');
        $info = $info[0];
        preg_match("/COMMENT='(.+?)'$/i", $info['Create Table'], $m);
        // 表名
        $return['table'] = $info['Table'];
        // 制表SQL
        $return['sql'] = $info['Create Table'];
        // 表注释
        $return['description'] = $m[1];
        return $return;
    }

    /**
     * 获取视图数据
     * 
     * @param   int		$viewMode   视图类型
     * @param   mixed	$args		数据条件
     * @return	array
     */
    function data($viewMode = 1, $args = false) {
        $args === false && $args = $_GET;
        $conf = $_ENV['conf'] or $conf = Conf::getConf();
        
        // 逻辑删除模式
        $conf['delmode'] and $args['del'] = (int) $args['del'];
        
        $struct = $conf['struct'];
        if ($viewMode == $_ENV['.TABLE']) {
            // 设置默认排序
            if (!isset($args['o']) && isset($conf['order'])) {
                $args['o'] = $conf['order'];
            }
            // 设置单页记录数
            if (isset($conf['limit'])) {
                $args['n'] = $args['n'] ? min($args['n'], $conf['limit']) : $conf['limit'];
            }
        }

        // JOIN参数
        $sqlField = array('m.*');
        $sqlJoin = array();
        $countJoin = 0;

        foreach ($struct as $fname => &$f) {
            // 验证可见度
            if (!$f['read'] & $viewMode || $fname[0] === '-') {
                continue;
            }

            // 拼接联表查询SQL
            if ($f['join']) {
                list($jTable, $jTitle, $jId) = explode(',', $f['join']);
                // 默认值
                $jTitle or $jTitle = 'title';
                $jId or $jId = 'id';
                $jTable = $this->t($jTable);
                $jTableAlias = 'j' . $countJoin++;
                $f['join'] = array(
                    'table' => $jTable, // 外表名
                    'tableAlias' => $jTableAlias, // 外表别名
                    'key' => $jId, // 外表连接字段
                    'alias' => $jTitle, // 外表连接字段别名
                );
                $sqlField[] = $jTableAlias . '.' . $jTitle . ' AS `alias-' . $fname . '`';
                $sqlJoin[] = ' INNER JOIN ' . $jTable . ' AS ' . $jTableAlias . ' ON ' . $jTableAlias . '.' . $jId . '=m.' . $fname;
            }
        }
        $this->_['sql'] = 'SELECT ' . join(',', $sqlField) . ' FROM `' . $this->_['table'] . '` AS m';
        if ($sqlJoin) {
            $this->_['sql'] .= join(' ', $sqlJoin);
        }

        // 数组查询参数
        if (is_array($args)) {
            $opt = array();
            $exp = '/^(\w+?)(-(' . self::$fieldSigns . '))?$/i';
            foreach ($args as $k => $v) {
                if (preg_match($exp, $k, $matches)) {
                    list($getName, $fieldName, $sign) = $matches;
                    if (isset($struct[$fieldName])) {
                        // 搜索外表字段
                        $join = $struct[$fieldName]['join'];
                        if ($join && !is_numeric($v)) {
                            $opt[$join['tableAlias'] . '.' . $join['alias'] . $sign] = $v;
                        }
                        // 搜索主表字段
                        else {
                            $opt['m.' . $k] = $v;
                        }
                    } else {
                        $opt[$k] = $v;
                    }
                }
            }
        }
        // 字串查询参数
        else {
            $opt = $args;
        }
        $this->opt($opt);
        if ($viewMode == 1) {
            return $this->get(true);
        }
        return $this->one();
    }

    /**
     * 赋值器
     */
    function __set($name, $value) {
        if ($name !== '_') {
            $this->_['data'][$name] = $value;
        }
    }

}?>
