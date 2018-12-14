<?php
namespace app\index\controller;
//作用取得客户端的ip、地理信息、浏览器、本地真实IP
 class Ip {
  ////获得访客浏览器类型
  function GetBrowser(){
   if(!empty($_SERVER['HTTP_USER_AGENT'])){
    $br = $_SERVER['HTTP_USER_AGENT'];
    if (preg_match('/MSIE/i',$br)) {    
               $br = 'MSIE';
             }elseif (preg_match('/Firefox/i',$br)) {
     $br = 'Firefox';
    }elseif (preg_match('/Chrome/i',$br)) {
     $br = 'Chrome';
       }elseif (preg_match('/Safari/i',$br)) {
     $br = 'Safari';
    }elseif (preg_match('/Opera/i',$br)) {
        $br = 'Opera';
    }else {
        $br = 'Other';
    }
    return $br;
   }else{return "获取浏览器信息失败！";} 
  }
   
  ////获得访客浏览器语言
  function GetLang(){
   if(!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])){
    $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
    $lang = substr($lang,0,5);
    if(preg_match("/zh-cn/i",$lang)){
     $lang = "简体中文";
    }elseif(preg_match("/zh/i",$lang)){
     $lang = "繁体中文";
    }else{
        $lang = "English";
    }
    return $lang;
     
   }else{return "获取浏览器语言失败！";}
  }
   
   ////获取访客操作系统
  function GetOs(){
   if(!empty($_SERVER['HTTP_USER_AGENT'])){
    $OS = $_SERVER['HTTP_USER_AGENT'];
      if (preg_match('/win/i',$OS)) {
     $OS = 'Windows';
    }elseif (preg_match('/mac/i',$OS)) {
     $OS = 'MAC';
    }elseif (preg_match('/linux/i',$OS)) {
     $OS = 'Linux';
    }elseif (preg_match('/unix/i',$OS)) {
     $OS = 'Unix';
    }elseif (preg_match('/bsd/i',$OS)) {
     $OS = 'BSD';
    }else {
     $OS = 'Other';
    }
          return $OS;  
   }else{return "获取访客操作系统信息失败！";}   
  }
   
  ////获得访客真实ip
  function Getip(){
      if(!empty($_SERVER["HTTP_CLIENT_IP"]))
      {
          $ip = $_SERVER["HTTP_CLIENT_IP"];
      }
      else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"]))
      {
          $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
      }
      else if(!empty($_SERVER["REMOTE_ADDR"]))
      {
          $ip = $_SERVER["REMOTE_ADDR"];
      }
      else
      {
          $ip = '';
      }
      echo $ip;
   if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){ //获取代理ip
    $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
   }else if(!empty($_SERVER["REMOTE_ADDR"])){
       $ips = explode(',',$_SERVER["REMOTE_ADDR"]);
   }else if(!empty($_SERVER["HTTP_CLIENT_IP"])){
       $ips = explode(',',$_SERVER["HTTP_CLIENT_IP"]);
   }
   else{
       $ips ='';
   }
   if($ip){
      $ips = array_unshift($ips,$ip); 
   }
    
   $count = count($ips);
   for($i=0;$i<$count;$i++){   
     if(!preg_match("/^(10|172\.16|192\.168)\./i",$ips[$i])){//排除局域网ip
      $ip = $ips[$i];
      break;    
      }  
   }  
   $tip = empty($_SERVER['REMOTE_ADDR']) ? $ip : $_SERVER['REMOTE_ADDR']; 
   if($tip=="127.0.0.1"){ //获得本地真实IP
      return $this->get_onlineip();   
   }else{
      return $tip; 
   }
  }
   
  ////获得本地真实IP
  function get_onlineip() {
      $mip = file_get_contents("http://city.ip138.com/city0.asp");
       if($mip){
           preg_match("/\[.*\]/",$mip,$sip);
           $p = array("/\[/","/\]/");
           return preg_replace($p,"",$sip[0]);
       }else{return "获取本地IP失败！";}
   }
   
  ////根据ip获得访客所在地地名
     ///
    function Getaddress($ip=''){
        if(empty($ip)){
       $ip = $this->Getip();
   }
            $arises = substr_count($ip,'.');
            $long = ip2long($ip);
           if($arises != 3 || $long == false || $long== -1){
           $msg = '无效ip地址，请重新输入！';
          return $msg;
        }
        $content = file_get_contents('http://www.ip138.com/ips1388.asp?action=2&ip='.$ip);
        $content = iconv('gb2312', 'utf-8', $content);
        $pos = stripos($content, '<li>本站主数据');
        if(!$pos){
        $msg = '没有查询到！';
        return $msg;
        }
        $endPos = stripos($content, '</li>', $pos);
        $jumpLen = strlen('<li>本站主数据：');
        $address = substr($content, $pos+$jumpLen, $endPos-$pos-$jumpLen);
        $msg = $address;
        return $msg;
    }

 }
 $gifo = new Ip();
 echo "你的ip:".$gifo->Getip();
// echo "<br/>所在地：".$gifo->Getaddress();
 
 echo "<br/>浏览器类型：".$gifo->GetBrowser();
 echo "<br/>浏览器语言：".$gifo->GetLang();
 echo "<br/>操作系统：".$gifo->GetOs();
?>