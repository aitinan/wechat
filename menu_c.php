<?php
/**
 * 微信公众平台-自定义菜单功能源代码
 */

header('Content-Type: text/html; charset=UTF-8');

//更换成自己的APPID和APPSECRET
$APPID="wx334914a9f1915372";
$APPSECRET="8b6795d29047e55efc04a0b553c47003";

$TOKEN_URL="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$APPID."&secret=".$APPSECRET;

$json=file_get_contents($TOKEN_URL);
$result=json_decode($json);

$ACC_TOKEN=$result->access_token;

$data='{
		 "button":[
		 {
			   "name":"公共查询",
			   "sub_button":[
				{
				   "type":"click",
				   "name":"天气查询",
				   "key":"tianQi"
				},
				{
                   "type":"view",
                    "name":"公交查询",
				    "url":"http://zqz2000.applinzi.com/view/index.html"
				},
				{
				   "type":"click",
				   "name":"翻译查询",
				   "key":"fanYi"
				},
				{
				   "type":"click",
				   "name":"快递查询",
				   "key":"kuaiDi"
				},{
                   "type":"click",
				   "name":"微信精选",
				   "key":"wenZhang"
                }]
		  },
		  {
			   "name":"本地",
			   "sub_button":[
				{
				   "type":"view",
                    "name":"公交查询",
				    "url":"http://zqz2000.applinzi.com/view/index.html"
				}]
		   },
		   {
            	"name":"辅助",
                "sub_button":[{
                	"type":"view",
                    "name":"更新菜单",
				    "url":"http://zqz2000.applinzi.com/menu_c.php"
                },{
                	"type":"view",
                    "name":"查看菜单",
				    "url":"http://zqz2000.applinzi.com/menu_s.php"
                },{
                	"type":"view",
                    "name":"删除菜单",
				    "url":"http://zqz2000.applinzi.com/menu_d.php"
                }]
			  
		   }]
       }';

    $MENU_URL="https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$ACC_TOKEN;
    
    $ch = curl_init($MENU_URL);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data)));
    $info = curl_exec($ch);
    $menu = json_decode($info);
    print_r($info);		//创建成功返回：{"errcode":0,"errmsg":"ok"}
    
    if($menu->errcode == "0"){
        echo "菜单创建成功";
    }else{
        echo "菜单创建失败";
    }

?>