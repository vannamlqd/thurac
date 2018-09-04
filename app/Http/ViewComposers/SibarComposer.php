<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Email;
use App\Sender;
use App\Ip;
use App\Domain;

class SibarComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
        // $this->users = $users;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view )
    {
    	$count = [
        'email' => Email::where('public','1')->count(),
        'ip' => Ip::join('emails','ips.ip_id','=','emails.ip_id')->where('emails.public','1')->groupby('ips.ip_id')->get(),
        'domain' => Domain::join('emails','domains.domain_id','=','emails.domain_id')->where('emails.public','1')->groupby('domains.domain_id')->get(),
        'sender' => Sender::join('emails','senders.sender_id','=','emails.sender_id')->where('emails.public','1')->groupby('senders.sender_id')->get(),
        ];
        $view->with('count',$count);
    }
}