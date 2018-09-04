<?php
namespace App\Modules\Email\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Email;
use App\Sender;
use App\Ip;
use App\Domain;
use App\Origin;
use App\Type;
use Session;
use Validator;
use File;
use Excel;
// use DB;
require_once('app/Helpers/Email.php');

class EmailController extends Controller{
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
		//parent::__construct();
		Domain::where('time','<=',0)->delete();
		Ip::where('time','<=',0)->delete();
		Sender::where('time','<=',0)->delete();
	}

	public function home(Request $request){
		$origins = Origin::all();
		$emails = self::getFilter($request)->select(['ip','domain','email','status','collect_id','created_at'])->paginate(20);

		$thongke = self::getFilter($request)->select(['ip','domain','email','status','collect_id','created_at'])->get();
		$type = [
			1 => 0,
			2 => 0,
			3 => 0,
			4 => 0,
		];
		$source = [
			1 => 0,
			2 => 0,
			3 => 0,
		];

		$days= [];

		foreach ($thongke as $key => $value) {
			$emailType = empty($value->status)? "4" : $value->status;
			$emailSource = empty($value->collect_id)? "3" : $value->collect_id;
			$type[$emailType]++;
			$source[$emailSource]++;
			$daystring = date("Y-m-d", strtotime($value->created_at));
			$days[$daystring][$emailSource] = empty($days[$daystring][$emailSource]) ? 1 : $days[$daystring][$emailSource] +  1;
		}
		return view('Email::home', ['origins' => $origins, 'allemail' => $emails, 'type' => $type, 'source' => $source, 'days' => $days]);
	}

	//Edit origin
	public function getEditOrigin($id){
		$origin = Origin::where('origin_id',$id)->first();
		return view('Email::editorigin',['origin'=>$origin]);
	}
	public function postEditOrigin(Request $request,$id){
		$this->validate($request,
			[
				'name'=>'required',
				'address'=>'required',
				'typemap'=>'required',
				'account'=>'required',
				'maxget'=>'required|numeric',
			],
			[
				'name.required'=>'Tên nguồn trống',
				'address.required'=>'Địa chỉ nguồn trống',
				'typemap.required'=>'Kiểu thông tin nguồn trống',
				'account.required'=>'THông tin cấu hình nguồn trống',
				'maxget.required'=>'Số mails get tối đa trống',
				'maxget.numeric'=>'Số mails get tối đa là 1 số',
			]
		);
		$data = [
		'name'=>$request->name,
		'address'=>$request->address,
		'typemap'=>$request->typemap,
		'account'=>$request->account,
		'maxget'=>$request->maxget,
		];
		try{
		Origin::where('origin_id',$id)->update($data);
		return redirect()->route('indexorigin')->with(['bag_message'=>'Cập nhật thành công','bag_level'=>'success']);
		}
		catch(\Exception $e){
		return redirect()->route('indexorigin')->with(['bag_message'=>'Cập nhật thất bại','bag_level'=>'danger']);
		}
	} 
	//Add origin
	public function getAddOrigin(){
		return view("Email::addorigin");
	}
	public function postAddOrigin(Request $request){
		$this->validate($request,
			[
				'name'=>'required',
				'address'=>'required',
				'typemap'=>'required',
				'account'=>'required',
				'maxget'=>'required|numeric',
			],
			[
				'name.required'=>'Tên nguồn trống',
				'address.required'=>'Địa chỉ nguồn trống',
				'typemap.required'=>'Kiểu thông tin nguồn trống',
				'account.required'=>'THông tin cấu hình nguồn trống',
				'maxget.required'=>'Số mails get tối đa trống',
				'maxget.numeric'=>'Số mails get tối đa là 1 số',
			]
		);
		$data = [
		'name'=>$request->name,
		'address'=>$request->address,
		'typemap'=>$request->typemap,
		'account'=>$request->account,
		'maxget'=>$request->maxget,
		];
		try{
		Origin::create($data);
		return redirect()->route('indexorigin')->with(['bag_message'=>'Thêm mới thành công','bag_level'=>'success']);
		}
		catch(\Exception $e){
		return redirect()->route('indexorigin')->with(['bag_message'=>'Thêm mới thất bại','bag_level'=>'danger']);
		}
	}
	//export
	public function exportxls($model, Request $request){
		if($model == 'Email')
		$data =Email::
		join('origins','origins.origin_id','=','emails.collect_id')
		->select('emails.email','emails.to','emails.subject','emails.ip','emails.created_at','origins.name')
		->where('emails.public',1)
		->orderby('emails.id','desc');
		elseif($model == 'Sender')
		$data = Sender::
		join('emails','senders.sender_id','=','emails.sender_id')
		->select('senders.from','senders.time','senders.created_at')
		->where('emails.public','1')
		->groupby('senders.sender_id')
		->orderby('senders.sender_id','desc');
		elseif($model == 'Domain')
		$data =  Domain::
		join('emails','domains.domain_id','=','emails.domain_id')
		->select('domains.domain','domains.time','domains.created_at')
		->where('emails.public','1')
		->groupby('domains.domain_id')
		->orderby('domains.domain_id','desc');
		elseif($model == 'Ip')
		$data = Ip::
			join('emails', 'ips.ip_id', '=', 'emails.ip_id')
			->select('ips.ip','ips.time','ips.created_at')
			->where('emails.public',1)
			->groupBy('ips.ip_id')
			->orderby('ips.ip_id','desc');
		
	$datefrom 	= isset($request->datefrom) && !empty($request->datefrom) ? \Helper::convertToTime($request->datefrom) : "";
	$dateto 	= isset($request->dateto) && !empty($request->dateto) ? \Helper::convertToTime($request->dateto) : "";
	
	if(!empty($datefrom)){
		$data = $data->where('emails.time_create','>',$datefrom);
	}

	if(!empty($dateto)){
		$data = $data->where('emails.time_create','<',$dateto);
	}
		
	$data = $data->get();
		self::array_to_csv_download($data->toArray());
	}
	
	
	public function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
    // open raw memory as file so no temp files needed, you might run out of memory though
    $f = fopen('php://memory', 'w'); 
    // loop over the input array
    foreach ($array as $line) { 
        // generate csv lines from the inner arrays
        fputcsv($f, $line, $delimiter); 
    }
    // reset the file pointer to the start of the file
    fseek($f, 0);
    // tell the browser it's going to be a csv file
    header('Content-Type: application/csv');
    // tell the browser we want to save it instead of displaying it
    header('Content-Disposition: attachment; filename="'.$filename.'";');
    // make php send the generated csv lines to the browser
    fpassthru($f);
}
	
	//liststatistic
	public function indexstatistic(Request $request){
		$origins = Origin::all();
		$search_type = $request->get('filter_type');
		$from 		= isset($request->from) ? $request->from : "";
		$to 		= isset($request->to) ? $request->to : "";
		$ip 		= isset($request->ip) ? $request->ip : "";
		$collect 	= isset($request->collect) ? $request->collect : "";
		$status 	= isset($request->status) ? $request->status : "";
		$datefrom 	= isset($request->datefrom) && !empty($request->datefrom) ? \Helper::convertToTime($request->datefrom) : "";
		$dateto 	= isset($request->dateto) && !empty($request->dateto) ? \Helper::convertToTime($request->dateto) : "";
		
		if(!empty($request->get('_token'))){
				$emails = self::getFilter($request)->select(['ip','domain','email','status','collect_id','created_at'])->paginate(20);	
		} else{
				$emails = [];
		}
		
		$data = [
			'from' => $from,
			'to' => $to,
			'ip' => $ip,
			'collect' => $collect,
			'status' => $status,
			'datefrom' => isset($request->datefrom)?$request->datefrom:"",
			'dateto' => isset($request->dateto)?$request->dateto:"",
			'keyworld' => $request->get('keyworld'),
			'filter_type' => $request->get('filter_type'),
		];

		return view('Email::indexstatistic', ['data' =>  $data, 'origins' => $origins, 'allemail' => $emails]);
	}

	public function postStatistic(Request $request){
	}

	//list
	public function indexreport(Request $request){
		$emails = Email::where('public','0')->orderby('id','DESC')->paginate(20);
		$page = $request->get('page');
		if(count($emails) < 1 && $page > 1){
			return redirect()->route('indexreport', array('page' => $page - 1));
		}

		return view('Email::indexreport',['emails' => $emails]);
	}
	//list
	public function indexorigin(){
		$origins = Origin::orderby('origin_id','desc')->get();
		return view("Email::indexorigin",['origins'=>$origins]);
	}
	public function indexip(Request $request){
		$allip = Ip::
			join('emails', 'ips.ip_id', '=', 'emails.ip_id')
			->where('emails.public',1)
			->groupBy('ips.ip_id')
			->orderby('ips.time','desc')
			->paginate(20);
		$search = isset($request->search)?$request->search:"";
		if (!empty($search)) {
			$allip = Ip::
			join('emails','ips.ip_id','=','emails.ip_id')
			->where('emails.public','1')
			->where('ips.ip','like','%'.$search.'%')
			->groupBy('ips.ip')
			->orderby('ips.time','desc')
			->paginate(20);
		}
		return view('Email::indexip',['allip'=>$allip,'search'=>$search]);
	}
	public function indexsender(Request $request){
		$allsender = Sender::
		join('emails','senders.sender_id','=','emails.sender_id')
		->where('emails.public','1')
		->groupby('senders.sender_id')
		->orderby('senders.time','desc')
		->paginate(20);
		$search = isset($request->search)?$request->search:"";
		if (!empty($search)) {
			$allsender = Sender::
			join('emails','senders.sender_id','=','emails.sender_id')
			->where('emails.public','1')
			->groupby('senders.sender_id')
			->where('from','like','%'.$search.'%')
			-> orderby('senders.time','desc')
			->paginate(20);
		}
		return view('Email::indexsender',['allsender'=>$allsender,'search'=>$search]);
	}
	public function indexdomain(Request $request){
		$alldomain = Domain::
		join('emails','domains.domain_id','=','emails.domain_id')
		->where('emails.public','1')
		->groupby('domains.domain_id')
		->orderby('domains.time','desc')
		->paginate(20);
		$search = isset($request->search)?$request->search:"";
		if (!empty($search)) {
			$alldomain = Domain::
			join('emails','domains.domain_id','=','emails.domain_id')
			->where('emails.public','1')
			->where('domains.domain','like','%'.$search.'%')
			->groupby('domains.domain_id')
			->orderby('domains.time','desc')
			->paginate(20);
		}
		return view('Email::indexdomain',['alldomain'=>$alldomain,'search'=>$search]);
	}
// 	public function indextype(){
// 		return view('Email::indextype');
// 	}
	public function index(Request $request){
		$from 		= isset($request->from) ? $request->from : "";
		$to 		= isset($request->to) ? $request->to : "";
		$ip 		= isset($request->ip) ? $request->ip : "";
		$collect 	= isset($request->collect) ? $request->collect : "";
		$status 	= isset($request->status) ? $request->status : "";
		$datefrom 	= isset($request->datefrom) && !empty($request->datefrom) ? \Helper::convertToTime($request->datefrom) : "";
		$dateto 	= isset($request->dateto) && !empty($request->dateto) ? \Helper::convertToTime($request->dateto) : "";
	
		$Emails = self::getFilter($request)->paginate(20);
		$data = [
			'from' => $from,
			'to' => $to,
			'ip' => $ip,
			'collect' => $collect,
			'status' => $status,
			'datefrom' => isset($request->datefrom)?$request->datefrom:"",
			'dateto' => isset($request->dateto)?$request->dateto:"",
		];

		$origins = Origin::all();
		return view('Email::index',['allemail'=>$Emails,'data'=>$data,'origins'=>$origins]);
	}

	public function getFilter($request){
		$Emails = Email::where('public',1);

		$from 		= isset($request->from) ? $request->from : "";
		$to 		= isset($request->to) ? $request->to : "";
		$ip 		= isset($request->ip) ? $request->ip : "";
		$collect 	= isset($request->collect) ? $request->collect : "";
		$status 	= isset($request->status) ? $request->status : "";
		$datefrom 	= isset($request->datefrom) && !empty($request->datefrom) ? \Helper::convertToTime($request->datefrom) : "";
		$dateto 	= isset($request->dateto) && !empty($request->dateto) ? \Helper::convertToTime($request->dateto) : "";
		
		$search_type = $request->get('filter_type');
		if($search_type == 3){
			$from 		= $request->get('keyworld');
		}
		
		if($search_type == 2){
			$domain = $request->get('keyworld');
		}
		
		if($search_type == 1){
			$ip 		= $request->get('keyworld');
		}

		if(!empty($ip)){
			$Emails = $Emails->where('ip','like','%'.$ip.'%');
		}

		if(!empty($collect)){
			$Emails = $Emails->where('collect_id','=',$collect);
		}

		if(!empty($from)){
			$Emails = $Emails->where('email','like','%'.$from.'%');
		}

		if(!empty($to)){
			$Emails = $Emails->where('to','like','%'.$to.'%');
		}

		if(!empty($datefrom)){
			$Emails = $Emails->where('time_create','>',$datefrom);
		}

		if(!empty($dateto)){
			$Emails = $Emails->where('time_create','<',$dateto);
		}

		if(!empty($status)){
			$Emails = $Emails->where('status','=',$status);
		}
		
		if(!empty($domain)){
			$Emails = $Emails->where('domain','like','%'.$domain.'%');
		}

		$Emails = $Emails->select('*')->orderby('emails.id','desc');
		return $Emails;
	}

	//detail
	public function detailEmail($id){
		$detailemail = Email::where('id',$id)->first();
		// dd($detailemail);
		return view('Email::detailemail',['detailemail'=>$detailemail]);
	} 

	//add
	public function addSender($from){
		$firstOrCreate = Sender::firstOrCreate(['from' => $from]);
		if (isset($firstOrCreate->id)) {
			// dd($firstOrCreate->id);
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
	//add
	public function chooseCsv(){
		$origins = Origin::all();
		return view('Email::add',['origins'=>$origins]);
	}

	public function importCsv(Request $request){
		$this->validate($request,
			[
				'filecsv'=>'required',
				
			],
			[
				'filecsv.required'=>'File trống',
				
			]
		); 
		if ($request->hasFile('filecsv')) {
		$extension = $request->file('filecsv')->getClientOriginalExtension(); 
		$fileName = time().rand(2,999).'.'.$extension;
		$request->file('filecsv')->move(public_path(), $fileName);
		if (($handle = fopen(public_path() .'/'.$fileName,'r')) !== FALSE)
		{
		$db2 = "";
		 while (($data = fgets($handle)) !==FALSE){
			$db2 .= $data;
			if(!strpos($data,": ")){
				continue;
			}
			 $array = (explode(": ",$data));
			 $db[$array['0']] = $array['1'];
		}
		fclose($handle);
		unlink(public_path() .'/'.$fileName);
		if(!isset($db['From']) || !isset($db['To']) || !isset($db['Received'])){
			return redirect()->route('indexEmail')->with(['bag_message'=>'File nhập vào thiếu dữ liệu vui lòng xem lại','bag_level'=>'danger']);
		}
		$content = explode("Content-Type: ", $db2) ;
		$Bip = explode("client-ip=", $db2);
		$Bip = explode(";", $Bip[1]);
		$content = $content[1].(isset($content[2])?$content[2]:"");
		$domain = "";
		try{
		$fromemail = isset($db['From'])?\Helper::clear_email($db['From']):"";
		if(isset($fromemail))
		$domain = explode("@",$fromemail);
		$toemail = isset($db['To'])?\Helper::clear_email($db['To']):"";
			// dd($db);
			$data=[
			'email' => $fromemail,
			'to' => $toemail,
			'subject' => \Helper::sql_special(isset($db['Subject'])?$db['Subject']:""),
			'ip' => isset($Bip[0])?$Bip[0]:"",
			'domain' => empty($domain)?"":$domain[1],
			'status' => 1,
			'collect_id' => $request->collect_id ,
			'country_code' => "US",
			'description' => \Helper::sql_special($content),
			'time_create' => time(),
			];
		 $sender_id = self::addSender($data['email']);
		 $domain_id = self::addDomain($data['domain']);
		 $ip_id = self::addIp($data['ip']);
		 $data['sender_id'] = $sender_id;
			$data['ip_id'] = $ip_id;
			$data['domain_id'] = $domain_id;
		 Email::create($data);
		 return redirect()->route('indexEmail')->with(['bag_message'=>'Thêm mới thành công','bag_level'=>'success']);
		}
	 catch(\Exception $e){
		return redirect()->route('indexEmail')->with(['bag_message'=>'Thêm mới thất bại ','bag_level'=>'danger']);
	 }
}

		}
	}
	public function setPublicMail($id){
		Email::where('id',$id)->update(['public'=>1]);
		return redirect()->back()->with(["bag_message" => "Xác thực thành công","bag_level"=>"success"]);
	}	
	
	public function getMailImap(){
		$gmail = self::getGmail();
// 		dd($gmail);
		if(count($gmail) > 0){
			foreach ($gmail as $keyg => $valueg) {
				try{
					$sender_id 						= self::addSender($valueg['email']);
					$ip_id 								= self::addIp($valueg['ip']);
					$domain_id 						= self::addDomain($valueg['domain']);
					$valueg['sender_id'] 	= $sender_id;
					$valueg['ip_id'] 			= $ip_id;
					$valueg['domain_id'] 	= $domain_id;
					Email::create($valueg);
				}catch(\Exception $e){
					continue;
				}
			}
			return redirect()->back();
		} else{
			return  redirect()->back(); //->with(["bag_message"=>"Hòm mail spam trống","bag_level"=>"danger"]);
		}
	}
	public function getGmail(){
		$imap_user = "tiepnhanthurac@gmail.com";
			$imap_pass = "antispam123";
			$imap_server = "{imap.gmail.com:993/imap/ssl}INBOX";
			$mbox = imap_open ($imap_server, $imap_user,$imap_pass);
			$date = date("j-M-Y", strtotime("-30 day"));
			$mbox_search = imap_search($mbox, 'SINCE ' . $date);
			$email = [];
			$message_count = count($mbox_search);
			$startvalue = -1;
			if ($message_count > 0) {
				while ($startvalue < $message_count && $startvalue < 10){
					$startvalue++;
					try{
						$data = imap_fetchbody($mbox,$mbox_search[$startvalue],"");
						$emailParser = new \PlancakeEmailParser($data);
						$email[$startvalue] = [
							"to" => \Helper::clear_email($emailParser->getHeader('From')),
							"ip" => \Helper::clear_ip($emailParser->getHeader('X-Received'))[0],
							"email" => \Helper::clear_email($this->getHeader($emailParser->getPlainBody(),"From: ","Date")),
							"domain" =>  explode("@",\Helper::clear_email($this->getHeader($emailParser->getPlainBody(),"From: ","Date")))[1],
							"description" => $emailParser->getPlainBody(),
							"subject" => $emailParser->getSubject(),
							"public" => 0,
							'status' => 3,
							'collect_id' => 2,
							'country_code' => "US",
							'time_create' => time(),
						];
						imap_mail_move( $mbox,$mbox_search[$startvalue], '[Gmail]/Trash' );
						imap_expunge($mbox);
					} catch(\Exception $e){
						imap_mail_move( $mbox,$mbox_search[$startvalue], '[Gmail]/Trash' );
						imap_expunge($mbox);
						continue;
					}
				}
				imap_close($mbox);
			}
// 			dd($email);
			return $email;
	}
	public function getAdd(){
		return view('Email::add');
	}
	
	public function dellEmail($id){
		try{
			// echo $id;die;
			$email = Email::where('id',$id)->first();
			 Email::where('id',$id)->delete();
			$ip = Ip::where('ip_id',$email['ip_id'])->first();
			 Ip::where('ip_id',$email['ip_id'])->update(['time'=>$ip->time - 1 ]);
			$sender = Sender::where('sender_id',$email['sender_id'])->first();
			 Sender::where('sender_id',$email['sender_id'])->update(['time'=>$sender->time - 1 ]);
			$domain = Domain::where('domain_id',$email['domain_id'])->first();
			 Domain::where('domain_id',$email['domain_id'])->update(['time'=>$domain->time - 1 ]);
			return redirect()->back()->with(['bag_message'=>'Xóa Email thành công','bag_level'=>'success']);
		}
		catch(\Exception $e){
			return redirect()->back()->with(['bag_message'=>'Xóa Email thất bại','bag_level'=>'success']);
		}
	}
	
		//Types
	public function explodeString($string){
			if (strrpos($string, ',')) {
				$data = explode(',', $string);
				return $data;
			}
			return $string;
	}
				//show
	public function indextype(){
		$type = Type::orderBy('type_id','desc')->get();
		$data = [];
		foreach ($type as $key => $value) {
			$data[$key] = [
				'type_id' => $value->type_id,
				'name' => $value->name,
				'rude' => self::explodeString($value->rude),
				'public' => $value->public
			];
		}
		return view('Email::indextype',['types'=>$data]);
	}
				//add
	public function getAddType(){
		return view("Email::addtype");
	}
	public function postAddType(Request $request){
		$this->validate($request,
			[
				'name'=>'required',
				'rude'=>'required',
			],
			[
				'name.required'=>'Tên  phân loại trống',
				'rude.required'=>'Luật phân loại trống',
			]
		);
		$data = [
		'name'=>$request->name,
		'rude'=>$request->rude,
		'public'=>$request->public,
		];
		try{
		Type::create($data);
		return redirect()->route('indextype')->with(['bag_message'=>'Thêm mới thành công','bag_level'=>'success']);
		}
		catch(\Exception $e){
		return redirect()->route('indextype')->with(['bag_message'=>'Thêm mới thất bại','bag_level'=>'danger']);
		}
	}
				//editType
	public function getEditType($id){
		$type = Type::where('type_id',$id)->first();
		return view('Email::edittype',['type'=> $type]);
	}
	public function postEditType(Request $request,$id){
		$this->validate($request,
			[
				'name'=>'required',
				'rude'=>'required',
			],
			[
				'name.required'=>'Tên  phân loại trống',
				'rude.required'=>'Luật phân loại trống',
			]
		);
		$data = [
		'name'=>$request->name,
		'rude'=>$request->rude,
		'public'=>$request->public,
		];
		try{
		Type::where('type_id',$id)->update($data);
		return redirect()->route('indextype')->with(['bag_message'=>'Cập nhật thành công','bag_level'=>'success']);
		}
		catch(\Exception $e){
		return redirect()->route('indextype')->with(['bag_message'=>'Cập nhật thất bại','bag_level'=>'danger']);
		}
	}
				//setpublic
	public function setPublicType($id){
		Type::where('type_id',$id)->update(['public'=>1]);
		return redirect()->route('indextype');
	}

				//dell
	public function dellType($id){
		Type::where('type_id',$id)->delete();
		return redirect()->route('indextype')->with(['bag_message'=>'Xóa luật nhận dạng thành công','bag_level'=>'success']);
	}

	//end type
}