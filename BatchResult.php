<?php

/**
 * 批量获取数据
 * User: 李鹏飞 <523260513@qq.com>
 * Date: 2015/12/29
 * Time: 16:26
 */
class BatchResult implements \Iterator
{
    public $batchSize = 100;
    public $each = true;

    /**
     * @var callable
     */
    private $_callback;
    private $_key;
    private $_offset;
    private $_value;
    private $_batch;

    /**
     * @inheritDoc
     */
    public function __construct(callable $callback)
    {
        $this->_callback = $callback;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->_value;
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if ($this->_batch === null || !$this->each || $this->each && next($this->_batch) === false) {
            $this->fetchData();
            reset($this->_batch);
        }


        if($this->each){
            $this->_value = current($this->_batch);
            if (key($this->_batch) !== null) {
                $this->_key++;
            } else {
                $this->_key = null;
            }
        }else{
            $this->_value = $this->_batch;
            $this->_key = $this->_key === null ? 0 : $this->_key + 1;
        }
    }

    /**
     * 获取数据，也是这个批量数据的核心
     */
    protected function fetchData(){
        $this->_batch = call_user_func($this->_callback, $this->_offset, $this->batchSize);
        $this->_offset += $this->batchSize;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->_key;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return !empty($this->_value);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->_key = 0;
        $this->_value = null;
        $this->_batch = null;
        $this->_offset = 0;

        $this->next();
    }
}