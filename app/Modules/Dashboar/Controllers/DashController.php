<?php
namespace App\Modules\Dashboar\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Session;
use Validator;

class DashController extends Controller{
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(){
        # parent::__construct();
    }
    public function index(){
    	// echo 1; die;
    	return view("Dashboar::index");
    }
}