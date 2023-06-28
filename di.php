<?php

class DataBase
{
    protected $adapter; //專門儲存外部傳入的變數
    public function __construct(Adapter $adapter)
    {
        // $this->adapter = new MysqlAdapter; // 如果使用此會容易有要更動時，導致各種地方都要改
        $this->adapter = $adapter; // 因此改用此方式
    }
}

interface Adapter
{
    // 從此定義每個函式都該長甚麼樣子，如此一來以下的MysqlAdapter就可以繼續擴充下去(新增PgsqlAdapter)
}

class MysqlAdapter implements Adapter
{
    
}
class PgsqlAdapter implements Adapter
{
    
}










