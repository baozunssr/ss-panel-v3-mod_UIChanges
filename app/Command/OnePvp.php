<?php


namespace App\Command;

use App\Models\User;
use App\Models\Ann;
use App\Services\Config;
use App\Services\Mail;
use App\Utils\Telegram;
use App\Utils\Tools;
use App\Services\Analytics;

class OnePvp
{

    public static function sendAllDailyMail($OnePvp_Id)
    {
        $users = User::all();
		$logs = Ann::orderBy('id', 'desc')->get();
		$text1="";
		
		foreach($logs as $log){
			if(strpos($log->content,"Links")===FALSE)
			{
				$text1=$text1.$log->content."<br><br>";
			}
		}
				
        foreach($users as $user){
			$lastday = round((($user->u+$user->d)-$user->last_day_t)/1024/1024,2);
			
			//if($user->sendDailyMail>=1)
			if($user->id == $OnePvp_Id)
			{
				echo "邮件已发送给ID: ".$user->id."  ";
				echo date('y-m-d h:i:s',time());
				echo "\n";
				$subject = Config::get('appName')."-每日流量报告以及公告";
				$to = $user->email;
				$text = "下面是公告正文:<br><br>".$text1."<br><br>(⊙o⊙)…！";
				
				
				try {
					Mail::send($to, $subject, 'news/daily-traffic-report.tpl', [
						"user" => $user,"text" => $text,"lastday"=>$lastday
					], [
					]);
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				$text="";
			}
        }
    }
	
	public static function sendNoticeMail($OnePvp_Id)//sendNotice
    {
        $users = User::all();
		$logs = Ann::orderBy('id', 'desc')->get();
		$text1="";
		
		foreach($logs as $log){
			if(strpos($log->content,"Links")===FALSE)
			{
				$text1=$text1.$log->content."<br><br>";
			}
		}
		
        foreach($users as $user){
			
			//if($user->sendDailyMail>=1)
			if($user->id == $OnePvp_Id)
			{
				echo "公告邮件已发送给ID: ".$user->id;
				echo "\n";
				$subject = Config::get('appName')."-系统公告";
				$to = $user->email;
				$text = "下面是公告正文:<br><br>".$text1."<br><br>看完啦!！   (⊙o⊙)…";
				
				
				try {
					Mail::send($to, $subject, 'news/Notice.tpl', [
						"user" => $user,"text" => $text
					], [
					]);
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				$text="";
			}
        }
		
    }

    public static function sendTelegram($OnePvp_Id)
    {
		echo "已发送给ID: $OnePvp_Id";
//        $users = User::all();		
//		$lastday_total = 0;
		
//        foreach($users as $user){
//			$lastday_total += (($user->u+$user->d)-$user->last_day_t);
//		}
		
//		$sts = new Analytics();
		
//		Telegram::Send("各位老爷少奶奶，我来为大家报告一下系统今天的运行状况哈~".
//		PHP_EOL.
//		"今日签到人数:".$sts->getTodayCheckinUser().PHP_EOL.
//		"今日使用总流量:".Tools::flowAutoShow($lastday_total).PHP_EOL.
//		"晚安~"
//		);
    }

}