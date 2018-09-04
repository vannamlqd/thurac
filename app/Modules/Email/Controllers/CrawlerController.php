<?php
namespace App\Modules\Email\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Email;
use App\Sender;
use App\Ip;
use App\Domain;
use App\Origin;
use Session;
use Validator;
use File;
use Excel;
// use DB;
require_once('app/Helpers/Email.php');

/**
* 
*/
class Vncert
{
	public function getIP($string){
		$ip = explode('Email from ', $string);
		if (count($ip) > 1) {
			$ip = explode(' ', $ip[1]);
			return $ip[0]; 
		} else{
			return false;
		}
	}

	public function getEmail($string){
		$email = explode('"From ', $string);
		if (count($email) > 1) {
			$email = explode(' ', $email[1]);
			if(!empty($email[0])) return $email[0];
			else return false;
		} else{
			return false;
		}
	}

	public function getSub($string){
		$sub = explode('Subject: ', $string);
		if (count($sub) > 1) {
			$sub = explode(PHP_EOL, $sub[1]);
			if(!empty($sub[0])) return $sub[0];
			else return false;
		} else{
			return false;
		}
	}

	public function getDomain($string){
		$domain = explode('@', $string);
		if(count($domain) > 1){
			return $domain[1];
		} else{
			return false;
		}
	}
}

/**
* 
*/
class Gmail
{
	public function getIP($string){
	}

	public function getEmail($string){
		$email = explode('From:', $string);
		if (count($email) < 2) {
			$email = explode('Từ:', $string);
		}

		if (count($email) > 1) {
			$email = explode(PHP_EOL, $email[1]);
			$email = \Helper::extract_email_address($email[0]);
			if(count($email)) return $email[0];
			else return false;
		} else return false;
	}

	public function getSub($string){
		$sub = explode('Chủ đề:', $string);
		if (count($sub) < 2) {
			$sub = explode('Subject:', $string);
		}

		if (count($sub) > 1) {
			$sub = explode(PHP_EOL, $sub[1]);
			if(!empty($sub[0])) return $sub[0];
			else return false;
		} else{
			return false;
		}
	}

	public function getDomain($string){
		$domain = explode('@', $string);
		if(count($domain) > 1){
			return $domain[1];
		} else{
			return false;
		}
	}
}

class CrawlerController extends Controller{

	public function getGmail(){
		$emailData = [];
		$emails = self::getEmailViaImap('imap.gmail.com:993/imap/ssl/novalidate-cert','tiepnhanthurac@gmail.com','antispam123','',1,5,'SUBJECT "Fwd: "','[Gmail]/Trash');
		$emailGmail = new Gmail();
		foreach ($emails as $key => $email) {
			if (strpos($email, 'From:') == false && strpos($email, 'Từ:') == false) {
				$email = base64_decode($email);
			}
			$emailData[] = [
				'ip' => '',
				'email' => strtolower($emailGmail->getEmail($email)),
				'domain' => strtolower($emailGmail->getDomain($emailGmail->getEmail($email))),
				"to" => "",
				"description" => $email,
				"subject" => $emailGmail->getSub($email),
				"public" => 0,
				'status' => self::getType($email),
				'collect_id' => 3,
				'country_code' => "US",
				'time_create' => time(),
			];
		}


		foreach ($emailData as $key => $email) {
			try{
				if(!empty($email['email']) && !empty($email['subject']) && !empty($email['domain'])){
					echo "Add 1 mail<br>";
					$sender_id 						= self::addSender($email['email']);
					$ip_id 								= self::addIp($email['ip']);
					$domain_id 						= self::addDomain($email['domain']);
					$email['sender_id'] 	= $sender_id;
					$email['ip_id'] 			= $ip_id;
					$email['domain_id'] 	= $domain_id;
					Email::create($email);
				}
			}catch(\Exception $e){
				continue;
			}
		}
	}
	
	
	public function getTrap(){
		$emailData = [];
		$emails = self::getEmailViaImap('imap.gmail.com:993/imap/ssl/novalidate-cert','baythurac2@gmail.com','123456Aa@','',1,5,'SUBJECT "Fwd: "','[Gmail]/Trash');
		$emailGmail = new Gmail();
		foreach ($emails as $key => $email) {
			if (strpos($email, 'From:') == false && strpos($email, 'Từ:') == false) {
				$email = base64_decode($email);
			}
			$emailData[] = [
				'ip' => '',
				'email' => strtolower($emailGmail->getEmail($email)),
				'domain' => strtolower($emailGmail->getDomain($emailGmail->getEmail($email))),
				"to" => "",
				"description" => $email,
				"subject" => $emailGmail->getSub($email),
				"public" => 1,
				'status' => self::getType($email),
				'collect_id' => 1,
				'country_code' => "US",
				'time_create' => time(),
			];
		}


		foreach ($emailData as $key => $email) {
			try{
				if(!empty($email['email']) && !empty($email['subject']) && !empty($email['domain'])){
					echo "Add 1 mail<br>";
					$sender_id 						= self::addSender($email['email']);
					$ip_id 								= self::addIp($email['ip']);
					$domain_id 						= self::addDomain($email['domain']);
					$email['sender_id'] 	= $sender_id;
					$email['ip_id'] 			= $ip_id;
					$email['domain_id'] 	= $domain_id;
					Email::create($email);
				}
			}catch(\Exception $e){
				continue;
			}
		}
	}

	public function getEmail(){
		$emailData = [];
		$emails = self::getEmailViaImap('mail.vncert.vn:993/imap/ssl/novalidate-cert','report@vncert.vn','s3cur1Ty@2016','spam_1',1,5,'TEXT "Offending message" ','Trash');
		foreach ($emails as $key => $email) {
			$emailVnCert = new Vncert();
			$emailData[] = [
				'ip' => strtolower($emailVnCert->getIP($email)),
				'email' => strtolower($emailVnCert->getEmail($email)),
				'domain' => strtolower($emailVnCert->getDomain($emailVnCert->getEmail($email))),
				"to" => "",
				"description" => $email,
				"subject" => $emailVnCert->getSub($email),
				"public" => 1,
				'status' => self::getType($email),
				'collect_id' => 2,
				'country_code' => "US",
				'time_create' => time(),
			];
		}

		foreach ($emailData as $key => $email) {
			try{
				if(!empty($email['email']) && !empty($email['ip']) && !empty($email['domain'])){
					echo "Add 1 mail<br>";
					$sender_id 						= self::addSender($email['email']);
					$ip_id 								= self::addIp($email['ip']);
					$domain_id 						= self::addDomain($email['domain']);
					$email['sender_id'] 	= $sender_id;
					$email['ip_id'] 			= $ip_id;
					$email['domain_id'] 	= $domain_id;
					Email::create($email);
				}
			}catch(\Exception $e){
				continue;
			}
		}
	}

	public function moveEmailAfterRead($mailBox, $mailBox_search, $int, $folder){
		imap_mail_move( $mailBox,$mailBox_search[$int], $folder);
		imap_expunge($mailBox);
		return array($mailBox, $mailBox_search);
	}


	public function getEmailViaImap($url, $username, $password, $folder, $limitDate = 1, $limitEmail = 2, $search = "",$trash = 'Trash'){
		$connectString = "{". $url ."}". $folder;
		$mailBox = imap_open($connectString, $username, $password);
		$date = date("j-M-Y", strtotime("-". $limitDate ." day"));
		$mailBox_search = imap_search($mailBox, $search);
		$count = count($mailBox_search);
		if($count < 1) return [];
		
		$result = [];
		$int = 0;
		while ($int < $count && $int < $limitEmail){
			try{
				$result[$int] = imap_fetchbody($mailBox,$mailBox_search[$int],1);
				list($mailBox, $mailBox_search) = self::moveEmailAfterRead($mailBox, $mailBox_search,$int, $trash);
			} catch(\Exception $e){
				list($mailBox, $mailBox_search) = self::moveEmailAfterRead($mailBox, $mailBox_search,$int, $trash);
				continue;
			}
			$int++;
		}

		imap_close($mailBox);
		return $result;
	}


	public function addSender($from){
		$firstOrCreate = Sender::firstOrCreate(['from' => $from]);
		if (isset($firstOrCreate->id)) {
			return $firstOrCreate->id;
		}
		Sender::where('sender_id',$firstOrCreate->sender_id)->update(['time'=>$firstOrCreate->time+1]);
		return $firstOrCreate->sender_id ;
	}
	public function addDomain($domain){
		$firstOrCreate = Domain::firstOrCreate(['domain' => $domain]);
		if (isset($firstOrCreate->id)) {
			return $firstOrCreate->id;
		}
		Domain::where('domain_id',$firstOrCreate->domain_id)->update(['time'=>$firstOrCreate->time+1]);
		return  $firstOrCreate->domain_id ;
	}
	public function addIp($ip){
		$firstOrCreate = Ip::firstOrCreate(['ip' => $ip]);
		if (isset($firstOrCreate->id)) {
			return $firstOrCreate->id;
		}
		Ip::where('ip_id',$firstOrCreate->ip_id)->update(['time'=>$firstOrCreate->time+1]);
		return $firstOrCreate->ip_id ;
	}

	public function getType($string){
		if (strpos($string, 'Nguyễn Phú Trọng') !== false) {
		    return 1;
		} elseif (strpos($string, 'Nguyễn Tấn Dũng') !== false) {
		    return 1;
		} elseif (strpos($string, 'Trương Tấn Sang') !== false) {
		    return 1;
		} elseif (strpos($string, 'Tô Huy Rứa') !== false) {
		    return 1;
		} elseif (strpos($string, 'attach') !== false) {
		    return 2;
		} elseif (strpos($string, '.zip') !== false) {
		    return 2;
		} elseif (strpos($string, '.exe') !== false) {
		    return 2;
		} elseif (strpos($string, '.rar') !== false) {
		    return 2;
		} elseif (strpos($string, 'filename') !== false) {
		    return 2;
		} elseif (strpos($string, 'làm tình') !== false) {
		    return 3;
		} elseif (strpos($string, 'sex') !== false) {
		    return 3;
		} elseif (strpos($string, 'porn') !== false) {
		    return 3;
		} elseif (strpos($string, 'pennis') !== false) {
		    return 3;
		} elseif (strpos($string, 'pussy') !== false) {
		    return 3;
		} elseif (strpos($string, 'dương vật') !== false) {
		    return 3;
		} elseif (strpos($string, 'âm đạo') !== false) {
		    return 3;
		} elseif (strpos($string, 'pussy') !== false) {
		    return 3;
		} else
			return 4;
	}
}