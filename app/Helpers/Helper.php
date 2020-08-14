<?php

namespace App\Helpers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use App\Models\Person\User;


class Helper extends Controller
{

    public static $companie_email = 'gpad237@gmail.com';
    public static $companie_name = 'GEPAD ENTERPRISE';

    public static function getUsernameFromEmail($email) {
        $username = '';  $i = 0;
        while ($email[$i] != '@'){$username = $username.''.$email[$i]; $i++;}
        return $username;
    }
        
    public static function sendValidationEmail($toValidatemail) {
        return url('validateEmail');
    }
    
    public static function sendmail($receiverEmail,  $action){
        $data = array('name'=>"Prevent Corona virus");
        $title = "";
        if($action == "register"){
            $title = 'Veuillez confirmez votre adresse email';
            $content = '';
        }
        if($action == "validateProduct"){
            $title = 'S\'il vous plait Monsieur aidez nous a expertiser ce produit';
            $content = '';
        }
        Mail::send([], $data, function($message) use ($content, $receiverEmail, $title) {
            $message->to($receiverEmail, ''.getUsernameFromEmail($receiverEmail))->subject($title);
            $message->setBody($content, 'text/html');
            $message->from('no_reply2@store-germany.de', 'Prevent Covid-19');
        });
        return response()->json("The email has been sent successfully", 200);
    }

    

    public static function generate_password() {
        do {
            $random = str_shuffle('abcdefABCDEF0123456789#');
            $password = substr($random, 0, 8); 
            $userFound = User::wherePassword(bcrypt($password))->first(); 
        } while($userFound);
        return $password;
    }

    public static function generate_login() {
        do {
            $random = str_shuffle('abcdefABCDEF0123456789_-');
            $login = substr($random, 0, 8); 
            $userFound = User::whereLogin($login)->first(); 
        } while($userFound);
        return $login;
    }


    public static function send_connexion_data_to_employee($receiver, $service) {

        $to_name = $receiver['first_name'] . ' ' . $receiver['last_name'];
        $to_email = $receiver['email'];

        $data = array(
            'companie_name' => Helper::$companie_name,
            'receiver' => $receiver,
            'service' => $service
        );
            
        try {
            Mail::send('emails.body_connexion_data_employee', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                ->subject('Vous avez reçu vos identifiants pour la connexion à la platforme GEPAD');
                
                $message->from(Helper::$companie_email, Helper::$companie_name);
            });
        } catch(Exception $e) {
            return response()->json($e);
        }
    }

    public static function send_connexion_data_to_admin($receiver, $service) {

        $to_name = $receiver['first_name'] . ' ' . $receiver['last_name'];
        $to_email = $receiver['email'];

        $data = array(
            'companie_name' => Helper::$companie_name,
            'receiver' => $receiver,
            'service' => $service
        );
            
        try {
            Mail::send('emails.body_connexion_data_admin', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                ->subject('Vous avez reçu vos identifiants pour la connexion à la platforme GEPAD');
                
                $message->from(Helper::$companie_email, Helper::$companie_name);
            });
        } catch(Exception $e) {
            return response()->json($e);
        }
    }

    public static function send_password_to_user($receiver, $password) {

        $to_name = $receiver['first_name'] . ' ' . $receiver['last_name'];
        $to_email = $receiver['email'];

        $data = array(
            'companie_name'=> Helper::$companie_name,
            'receiver'=> $receiver,
            'password'=> $password,
        );
            
        try {
            Mail::send('emails.body_password', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)
                ->subject('Votre mot de passe a été réinitialisé avec succès');
                
                $message->from(Helper::$companie_email, Helper::$companie_name);
            });
        } catch(Exception $e) {
            return response()->json($e);
        }
    }

}
