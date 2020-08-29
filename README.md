# 萤石开放平台PHP SDK

海康威视设备萤石开放平台(萤石云)PHP SDK,用于接入海康设备直播,通信等功能

## 安装
    composer require neteast\ys7

## Quickstart

    use Neteast\YS7\YS7Auth;
    use Neteast\YS7\YS7Client;

    $auth = new YS7Auth($appKey, $appSecret);
    $client = new YS7Client($auth);

    // 新增设备
    $client->device->add($deviceSerial, $validateCode);

    // 获取设备列表
    $devices = $client->device->list();

    // 获取设备信息
    $info = $client->device->info();

    // 获取摄像头列表
    $cameras = $client->device->camera->list();

    // 根据设备获取摄像头列表
    $cameras = $client->device->camera->listByDevice($deviceSerial);

    // 关闭加密功能
    $client->device->configuration->setEncrypt($deviceSerial, $validateCode, false);

    // 开启下线通知
    $client->device->configuration->setNotify($deviceSerial, true);

    // 控制云台转动
    $client->ptz->start($deviceSerial, 0);
    sleep(1);
    $client->ptz->stop();

    // 开通直播功能
    $client->live->open($deviceSerial, $channelNo);

    // 获取直播地址
    $data = $client->live->address($deviceSerial, $expiresIn, $channelNo);

    // 通知
    $consumer = $client->consumer();
    $consumer->addHandler(function($message, \Neteast\YS7\Message\Consumer $consumer, YS7Client $client) {
        // 你的处理业务逻辑
    });

    // 开始消费消息
    while(true) {
        $consumer->consume();
        sleep(30);
    }
