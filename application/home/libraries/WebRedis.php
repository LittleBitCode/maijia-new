<?php
// +----------------------------------------------------------------------
// |  WebRedis
// +----------------------------------------------------------------------
// | Copyright (c) 2017年  All rights reserved.
// +----------------------------------------------------------------------
// | File:   
// |
// | Author:    zhichao_hu
// | Created:   2017-07-04
// +----------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class WebRedis
{

    public function __construct()
    {
        #parent::__construct();
        #加载redis配置文件
        $this->config->item('config');
    }


    public $database = array(
        'all' => 0,
    );

    public $_redis = null;

    /**
     * 连接缓存数据库
     */
    public function connect($db, $mode = 'r')
    {
        if ($mode == 'w') {
            $conf = $this->config->write_redis;  // 写入主库
        } else {
            $conf = $this->config->redis;
        }
        $this->_redis = new Redis();
        $this->_redis->connect($conf->host, $conf->port);
        if (!empty($conf->auth_password))
            $this->_redis->auth($conf->auth_password);

        //选择数据库
        $this->_redis->select(intval($this->database[$db]));
    }

    /**
     * 判断redis 是否连通
     */
    public function is_pang()
    {
        $pattern = '/PONG/';
        $ping = $this->_redis->ping();
        return (preg_match($pattern, $ping));
    }

    /**
     * 写入数据
     */
    function write($key, $data, $db, $lifetime = 0)
    {
        $this->connect($db, 'w');
        $this->_redis->multi();
        if (is_array($data))
            $info = json_encode($data);
        else {
            $info = $data;
        }

        if (empty($info))
            $this->logger->error($info);

        $this->_redis->set($key, $info);
        if ($lifetime > 0)
            $this->_redis->setTimeout($key, $lifetime);
        // 执行事务
        $this->_redis->exec();
    }

    /**
     *
     */
    function write_simple($key, $value, $db, $lifetime = 0)
    {
        $this->connect($db, 'w');
        $this->_redis->multi();
        $this->_redis->set($key, $value);
        if ($lifetime > 0)
            $this->_redis->setTimeout($key, $lifetime);
        $this->_redis->exec();
    }

    /**
     * 删除数据
     * @return {int}
     * @param $db string 数据库编号
     * @param $key string 键名
     */
    function remove($key, $db)
    {
        $this->connect($db, 'w');
        if ($this->_redis->exists($key)) {
            return $this->_redis->del($key);
        }
        return 0;
    }

    /**
     * 读取数据
     * @param $key 关键字
     * @param $db 数据库标识
     * @param $type 类型 0:不做任何处理 1：string 2:hash 3:list 4:set 5:zset
     */
    function read($key, $db, $type = 1)
    {
        $data = null;
        $this->connect($db);
        if ($this->_redis->exists($key)) {
            $value = '';
            switch ($type) {
                case 0:
                    $data = $this->_redis->get($key);
                    break;
                case 1:
                    $value = $this->_redis->get($key);
                    if (!empty($value) && $value != 'NODATA')
                        $data = json_decode($value, TRUE);
                    else {
                        $data = 'NODATA';
                    }
                    break;
                case 2:
                    $data = $this->_redis->hGetAll($key);
                    break;
            }
        }
        return $data;
    }

    /**
     * 读取多条数据
     * @param $keys
     * @param $db 数据库标识
     * @param $type 类型： 0:不做任何处理 1：string 2:hash 3:list 4:set 5:zset
     */
    function read_multi($keys, $db, $type = 1)
    {
        $data = null;
        $this->connect($db);
        if (is_array($keys) && !empty($keys)) {
            $data = $this->_redis->getMultiple($keys);
        }
        return $data;
    }


    function incr($key, $db)
    {
        $this->connect($db, 'w');
        return $this->_redis->incr($key);
    }

    public function decr($key, $db)
    {
        $this->connect($db, 'w');
        return $this->_redis->decr($key);
    }

    function exists($key, $db)
    {
        $this->connect($db);
        return $this->_redis->exists($key);
    }


    /**
     * Hash Set
     */
    public function hset($key, $field, $value, $db)
    {
        $this->connect($db, 'w');
        $this->_redis->hSet($key, $field, $value);
    }

    /**
     * 获取集合几个成员数
     */
    public function scard($key, $db)
    {
        $this->connect($db);
        return $this->_redis->scard($key);
    }

    /**
     * setnx
     */
    public function setnx($key, $db, $value, $lifetime)
    {
        $this->connect($db, 'w');
        $this->_redis->multi();
        $this->_redis->setnx($key, $value);
        if ($lifetime > 0) {
            $this->_redis->setTimeout($key, $lifetime);
        }
        return $this->_redis->exec();
    }
}
