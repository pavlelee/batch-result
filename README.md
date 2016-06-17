#这个类可以很好的解决读取数据量非常大的需求，内存不至于溢出。

#使用方式
 - batch方式：
```
$batch = new BatchResult(function ($offset, $limit){
    //你自己的查询逻辑
    return $db->query("SELECT * FROM test LIMIT {$offset},{$limit}")->findAll();
});
$batch->batchSize = 2000;
$batch->each = false;

foreach ($batch as $items){
    foreach ($items as $item){
        echo $item['name'] . $item['id'] . "\r\n";
    }
}
```

 - each方式：
```
$batch = new BatchResult(function ($offset, $limit) use ($sysshop){
    //你自己的查询逻辑
    return $db->query("SELECT * FROM test LIMIT {$offset},{$limit}")->findAll();
});
$batch->batchSize = 2000;
$batch->each = true;

foreach ($batch as $item){
    echo $item['name'] . $item['id'] . "\r\n";
}
```