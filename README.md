# 萤石开放平台PHP SDK

[![Donate with Bitcoin](https://en.cryptobadges.io/badge/micro/1BdJG31zinrMFWxRt2utGBU2jdpv8xSgju)](https://en.cryptobadges.io/donate/1BdJG31zinrMFWxRt2utGBU2jdpv8xSgju)

海康威视设备萤石开放平台(萤石云)PHP SDK,用于接入海康设备直播,通信等功能

官方文档:
> https://open.ys7.com/doc/zh/book/index/user.html

## Installation
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
    $client->ptz->start($deviceSerial, \Neteast\YS7\Enum\PTZ::DIRECTION_UP);
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

## TODOS
* 消息处理相关信号

## Contribute
对于需要使用并未封装的api,可依照本类库封装风格进行封装,通过pull request合作开发

## Changelog
### 0.1.0
* 基本功能封装
