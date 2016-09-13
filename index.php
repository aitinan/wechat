<?php

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->responseMsg();
//$wechatObj->valid();

//include('./server/weather.php');

class wechatCallbackapiTest
{

    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
                
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $RX_TYPE = trim($postObj->MsgType);

                switch($RX_TYPE)
                {
                    case "text":
                        $resultStr = $this->handleText($postObj);
                        break;
                    case "event":
                        $resultStr = $this->handleEvent($postObj);
                        break;
                    default:
                        $resultStr = "Unknow msg type: ".$RX_TYPE;
                        break;
                }
                echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }
    
    //接受文本处理
    public function handleText($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(!empty( $keyword ))
        {
            $msgType = "text";
            $contentStr = "欢迎来到赵启泽的个人公众号!"."\n".
                          "【1】 <a href='https://www.cnblogs.com/zqzjs'>个人博客网站</a>"."\n".
                          "【2】 <a href='https://github.com/zhaoqize'>github</a>"."\n".
                          "【3】 <a href='http://honghuangpower.site'>开发中的网站</a>"."\n\n".
                          "最近关注的技术"."\n".
                          "react,reactnative,vuejs,webpack,eslint"."\n";
                          
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            echo $resultStr;
        }else{
            echo "Input something...";
        }
    }
    
    //点击事件处理
    public function handleEvent($object)
    {
        $contentStr = "";
        switch ($object->Event)
        {
            case "subscribe":
                $contentStr = "感谢您关注【zhaoqize】";
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "tianQi":
                        
                    $url = "http://op.juhe.cn/onebox/weather/query?cityname=%E6%AD%A6%E6%B1%89&key=87ffc29722810c9dcaa06d6f5a8a7700";
                    $output = $this->httpRequest($url);
                    $weather = json_decode($output, true);
                        
                        $data = $weather['result']['data']['weather'][1]['info'];
                        $contentStr = "【武汉天气】"."\n".
                                      " 早晨：".$data['dawn'][1]."  温度：".$data['dawn'][2]."\n".
                                      " 白天：".$data['day'][1]."  温度：".$data['day'][2]."\n".
                                      " 晚上：".$data['night'][1]."  温度：".$data['night'][2];
                        break;
                    default:
                        $contentStr = "点击事件";
                        break;
                }
                break;
            
            default :
                $contentStr = "感谢您关注【zhaoqize】";
                break;
        }
        $resultStr = $this->responseText($object, $contentStr);
        return $resultStr;
    }
    
    //响应文本处理
    public function responseText($object, $content, $flag=0)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>%d</FuncFlag>
                    </xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $flag);
        return $resultStr;
    }
    
    //天气
    public function weather(){
        
        $url = "http://op.juhe.cn/onebox/weather/query?cityname=%E6%AD%A6%E6%B1%89&key=87ffc29722810c9dcaa06d6f5a8a7700";
        $output = httpRequest($url);
        $weather = json_decode($output, true); 
        
         return $weather['reason'];
        
    }
    
    function httpRequest($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        if ($output === FALSE){
            return "cURL Error: ". curl_error($ch);
        }
        return $output;
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];    
                
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>