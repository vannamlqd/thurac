<?php
/**
* 
*/
use Carbon\Carbon;
class Helper
{
	public static function convertToTime($date){
		 $d = DateTime::createFromFormat('d-m-Y', $date);
			return $d->getTimestamp();
	}
	public static function convertTimeLikeDB($time){
		return Carbon::createFromFormat('d-m-Y', $time)->format('Y-m-d');
	}

	public static  function convertTimeLikeView($time){
		return Carbon::createFromFormat('Y-m-d', $time)->format('d-m-Y');
	}

	public static function sql_special($inp){
		if(!empty($inp) && is_string($inp)){ 
			$inp = trim($inp);
			return  htmlentities($inp);
		} 
	}
	public static function  clear_email($email){
		// $email = htmlentities($email);
		if (!empty($email) && is_string($email)) {
			try{
			$email_regex = "/[^0-9< ][A-z0-9_]+([.][A-z0-9_]+)*@[A-z0-9\-_]+([.][A-z0-9_]+)*[.][A-z]{2,4}/";
			preg_match($email_regex,$email,$matches);
			$email = $matches[0];
			}
			catch(\Exception $e){
				return $email;
			}
		}
		return $email;
	}
	public static function  clear_ip($ip){
		// $ip = htmlentities($ip);
		if (!empty($ip) && is_string($ip)) {
			try{
			$ip_regex = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/";
			preg_match_all($ip_regex, $ip, $matches);
			$ip = $matches[0];
			}
			catch(\Exception $e){
				return $ip;
			}
		}
		return $ip;
	}
	
public static function extract_email_address($string) {
	$emails = [];
    foreach(preg_split('/\s/', $string) as $token) {
        $email = filter_var(filter_var($token, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL);
        if ($email !== false) {
            $emails[] = $email;
        }
    }
    return $emails;
}

	public static function getIPFromString($string){
		if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $string, $ip_match)) {
			 return $ip_match[0];
		} else{
			return false;
		}
	}
	
}