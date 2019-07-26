<?php
// 初始化 WebSocket 服务器，在本地监听 8000 端口
$server = new Swoole\WebSocket\Server("0.0.0.0", 8000);

// 建立连接时触发
$server->on('open', function (Swoole\WebSocket\Server $server, $request) {
    echo "server: handshake success with fd{$request->fd}\n";    
});

// 收到消息时触发推送
$server->on('message', function (Swoole\WebSocket\Server $server, $frame) {
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

// 关闭 WebSocket 连接时触发
$server->on('close', function ($ser, $fd) {
    echo "client {$fd} closed\n";
});

// 启动 WebSocket 服务器
$server->start();