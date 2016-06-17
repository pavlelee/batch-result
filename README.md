#这个类可以很好的解决读取数据量非常大的需求，内存不至于溢出。

#使用方式
 - batch方式：
```
$batch = new BatchResult(function ($offset, $limit) use ($sysshop){
    return $sysshop->getUserAll("AND `isdel`=0 AND `status`=0 AND (`gid`<1 OR `gid`='{$_GET[id]}')", "ORDER BY `dateline` DESC LIMIT {$offset},{$limit}", 'id, name, aleph');
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
    return $sysshop->getUserAll("AND `isdel`=0 AND `status`=0 AND (`gid`<1 OR `gid`='{$_GET[id]}')", "ORDER BY `dateline` DESC LIMIT {$offset},{$limit}", 'id, name, aleph');
});
$batch->batchSize = 2000;
$batch->each = true;

foreach ($batch as $item){
    echo $item['name'] . $item['id'] . "\r\n";
}
```