<?php

/**
 * --------------------------------------------------------------------
 * CODEIGNITER 4 - SimpleAuth
 * --------------------------------------------------------------------
 *
 * This content is released under the MIT License (MIT)
 *
 * @package    SimpleAuth
 * @author     GeekLabs - Lee Skelding 
 * @license    https://opensource.org/licenses/MIT	MIT License
 * @link       https://github.com/GeekLabsUK/SimpleAuth
 * @since      Version 1.0
 * 
 */

namespace App\Libraries;

use CodeIgniter\I18n\Time;
use App\Models\AuthModel;
use App\Libraries\SendEmail;
use Config\Auth;
use Config\Email;
use Config\App;
use \Config\Services;
    


/**
 * AuthLibrary
 */
class AuthLibrary
{
    public function __construct()
    {
        $this->AuthModel =    new AuthModel();
        $this->SendEmail = new SendEmail;        
        $this->config = new Auth;
        $this->emailconfig = new Email;
        $this->AppConfig = new App;
        $this->Session = session();
        $this->request = Services::request();
        
    }

    /*
	 *--------------------------------------------------------------------------
	 * Generate Token
   	 *--------------------------------------------------------------------------
	 *
     * Generates a random token encodes it then hashes it.
     * Sets the expiry time for the token
     * 
     * @param  int $user
     * @param  int $tokentype
     * @return int $encodedtoken
     * 
     */
    public function GenerateToken($user, $tokentype)
    {
        // GENERATE A TOKEN
        $token = random_string('crypto', 20);

        // ENCODE THE TOKEN
        $encodedtoken = base64_encode($token);

        // HASH THE ENCODED TOKEN FOR EXTRA SECURITY 
        $authtoken = password_hash($token, $this->config->hashAlgorithm);

        // CHECK WHAT TYPE OF TOKEN WE ARE SETTING SO WE CAN SET THE EXPIRY TIME
        if ($tokentype == 'reset_token') {
            $tokenexpire = 'reset_expire';
            $expireTime = $this->config->resetTokenExpire;
        }
        if ($tokentype == 'activate_token') {
            $tokenexpire = 'activate_expire';
            $expireTime = $this->config->activateTokenExpire;
        }

        // SET THE EXPIRY TIME FOR THE RESET TOKEN
        $TokenExpireTime = new Time('+' . $expireTime . 'hours');

        // DEFINE AN ARRAY WITH VARIABLES WE NEED TO PASS
        $user = [
            'id' => $user['id'],
            'email' => $user['email'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            $tokentype => $authtoken,
            $tokenexpire => $TokenExpireTime,
        ];

        // UPDATE DB WITH HASHED TOKEN
        $this->AuthModel->save($user);

        // RETURN THE TOKEN
        return $encodedtoken;
    }

    /**
     *--------------------------------------------------------------------------
     * LOGIN USER
     *--------------------------------------------------------------------------
     *
     * Form validation done in controller
     * Gets the user from DB
     * Checks if their account is activated
     * Sets the user session and logs them in
     * 
     * @param  string $email
     * @return true
     */
    public function LoginUser($email, $rememberMe)
    {
       
        // GET USER DETAILS FROM DB
        $user = $this->AuthModel->where('email', $email)
            ->first();

        // CHECK TO SEE IF ACCOUNT IS ACTIVATED
        if ($user['activated'] == false) {

            // ACCOUNT NOT ACTIVATED SO SET LINK TO RESEND ACTIVATION EMAIL
            $this->Session->setFlashData('danger', lang('Auth.notActivated'));
            $this->Session->setFlashData('resetlink', '<a href="/auth/resendactivation/' . $user['id'] . '">Resend Activation Email</a>');
            return false;
        }

        // SET USER ID AS A VARIABLE
        $userID = $user['id'];

        // IF REMEMBER ME FUNCTION IS SET TO TRUE IN CONFIG 
        if ($this->config->rememberMe && $rememberMe == '1') {

            $this->rememberMe($userID);
            $this->Session->set('rememberme', $rememberMe);
        } 
        
        $this->Session->set('lockscreen', false);

        //SET USER SESSION 
        $this->setUserSession($user);       
    }

    /**
     *--------------------------------------------------------------------------
     * REGISTER USER
     *--------------------------------------------------------------------------
     *
     * Form validation done in controller
     * Save user details to DB
     * Send activation email if config is set to true
     * If config is false manually activate account
     * 
     * @param  array $userData
     * @return true
     */
    public function RegisterUser($userData)
    {
        // ADD USER TO DEFAULT ROLE
        $userData['role'] = $this->config->defaultRole;

        // SAVE USER DETAILS TO DB
        $this->AuthModel->save($userData);

        // FIND OUR NEW USER BY EMAIL SO WE CAN GRAB NEW DETAILS
        $user = $this->AuthModel->where('email', $userData['email'])
            ->first();

        // SHOULD WE SEND AN ACTIVATION EMAIL?
        if ($this->config->sendActivationEmail) {

            // GENERATE A NEW TOKEN
            // SET THE TOKEN TYPE AS SECOND PARAMETER. Reset password token = 'reset_token'
            $encodedtoken  = $this->GenerateToken($user, 'activate_token');

            // GENERATE AND SEND ACTIVATION EMAIL
            $result = $this->ActivateEmail($user, $encodedtoken);

            if ($result) {
                $this->Session->setFlashData('success', lang('Auth.accountCreated'));
                return true;
            } else {
                $this->Session->setFlashData('danger', lang('Auth.errorOccured'));
                return false;
            }
        }

        // IF WERE NOT SENDING AN ACTIVATION EMAIL LETS SET THE USER TO ACTIVATED NOW
        else {

            $data = [
                'id' => $user['id'],
                'activated' => '1',
            ];

            // UPDATE DB
            $this->AuthModel->save($data);

            $this->Session->setFlashData('success', lang('Auth.accountCreatedNoAuth'));
            return true;
        }
    }

    /**
     *--------------------------------------------------------------------------
     * EDIT PROFILE
     *--------------------------------------------------------------------------
     *
     * Process edit profile
     * 
     * @param  array $user
     * @return void
     */
    public function editProfile($user)
    {
        // SAVE TO DB
        $this->AuthModel->save($user);

        // SAVE USER DATA IN SESSION
        $this->setUserSession($user);

        // SET FLASH DATA
        $this->Session->setFlashData('success', lang('Auth.successUpdate'));
        
        return;
    }

    /**
     *--------------------------------------------------------------------------
     * ACTIVATE EMAIL
     *--------------------------------------------------------------------------
     *
     * Set up the activation email if config is set to true
     * Send Email
     * 
     * @param  int $user
     * @param  int $encodedtoken
     * @return boolean
     */
    public function ActivateEmail($user, $encodedtoken)
    {
        // RESET LINK TO INCLUDE IN EMAIL TEMPLATE
        $activatelink = base_url() . '/activate/' . $user['id'] . '/' . $encodedtoken;

        // SET DATA TO SEND TO EMAIL CONTENT
        $data = [
            'userid' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'resetlink' => $activatelink,
        ];

        // SET DATA FOR EMAIL HEADERS
        $emaildata = [
            'to' => $user['email'],
            'subject' => $this->config->activateEmailSubject,
            'fromEmail' => $this->emailconfig->fromEmail,
            'fromName' => $this->emailconfig->fromName,
            'message_view' => 'activateaccount.php',
            'messagedata' => $data,
        ];

        // SEND DATA TO SEND EMAIL LIBRARY
        $result = $this->SendEmail->build($emaildata);

        return $result;
    }

    /**
     *--------------------------------------------------------------------------
     * RESEND ACTIVATION EMAIL
     *--------------------------------------------------------------------------
     *
     * Resends the user activation email
     * 
     * @param  int $id
     * @return boolean
     */
    public function ResendActivation($id)
    {
        // FIND USER BY ID SO WE CAN GRAB DETAILS
        $user = $this->AuthModel->where('id', $id)
            ->first();

        // GENERATE A NEW TOKEN
        // SET THE TOKEN TYPE AS SECOND PARAMETER. Reset password token = 'reset_token'	
        $encodedtoken  = $this->GenerateToken($user, 'activate_token');

        // GENERATE AND SEND ACTIVATION EMAIL
        $result = $this->ActivateEmail($user, $encodedtoken);

        if ($result) {
            $this->Session->setFlashData('success', lang('Auth.acitvationEmailReSent'));
            return true;
        } else {
            $this->Session->setFlashData('danger', lang('Auth.errorOccured'));
            return false;
        }
    }

    /**
     *--------------------------------------------------------------------------
     * ACTIVATE USER
     *--------------------------------------------------------------------------
     *
     * Incoming request from email link to activate the user
     * Decode the token and get user details from DB
     * Check if token is valid and hasnt expired
     * Update user to activated
     * 
     * @param  int $id
     * @param  int $token
     * @return void
     */
    public function activateuser($id, $token)
    {
        // DECODE THE TOKEN
        $decodedtoken = base64_decode($token);

        // GET USERS DETAILS FROM DB
        $user = $this->AuthModel->find($id);

        // FETCH THE EXPIRY TIME FOR TOKEN
        $resetexpiry = $user['activate_expire'];
        $timenow = new Time('now');

        // CHECK TO SEE IF TOKEN HAS EXPIRED
        if (!$timenow->isBefore($resetexpiry)) {

            // IT HAS SO SET SOME FLASH DATA          
            $this->Session->setFlashData('danger', lang('Auth.linkExpired'));

            return;
        }

        // CHECK TOKEN AGAINST TOKEN IN DB
        if (!password_verify($decodedtoken, $user['activate_token'])) {

            // DOES NOT MATCH SO SET SOME FLASH DATA
            $this->Session->setFlashData('danger', lang('Auth.noAuth'));

            return;
        } else {

            // DID MATCH SO SET DATA TO PASS TO SESSION
            $data = [
                'id' => $user['id'],
                'activated' => '1',
                'activate_expire' => null, // RESET EXPIRY TIME
                'activate_token' => null, // CLEAR OLD TOKEN FROM DB
            ];

            // SAVE DATA TO DB
            $this->AuthModel->save($data);

            // SET SOME FLASH DATA
            $this->Session->setFlashData('success', lang('Auth.activateSuccess'));

            return;
        }
    }

    /**
     *--------------------------------------------------------------------------
     * RESET EMAIL
     *--------------------------------------------------------------------------
     *
     * Sends the user a password reset link email
     * 
     * @param  array $user
     * @param  int $encodedtoken
     * @return boolean
     */
    public function ResetEmail($user, $encodedtoken)
    {
        // RESET LINK TO INCLUDE IN EMAIL TEMPLATE
        $resetlink = base_url() . '/resetpassword/' . $user['id'] . '/' . $encodedtoken;

        // SET DATA TO SEND TO EMAIL CONTENT
        $data = [
            'userid' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'resetlink' => $resetlink,
        ];

        // SET DATA FOR EMAIL HEADERS
        $emaildata = [
            'to' => $user['email'],
            'subject' => $this->config->resetEmailSubject,
            'fromEmail' => $this->emailconfig->fromEmail,
            'fromName' => $this->emailconfig->fromName,
            'message_view' => 'forgotpassword.php',
            'messagedata' => $data,
        ];

        // SEND DATA TO SEND EMAIL LIBRARY
        $result = $this->SendEmail->build($emaildata);

        if ($result) {
            $this->Session->setFlashData('success', lang('Auth.resetSent'));
            return true;
        } else {
            $this->Session->setFlashData('danger', lang('Auth.errorOccured'));
            return false;
        }
    }

    /**
     *--------------------------------------------------------------------------
     * RESET PASSWORD
     *--------------------------------------------------------------------------
     *
     * Incoming request to reset password
     * Decode the token and get user details from DB
     * Check if token is valid and hasnt expired
     * Return user id to use on password reset form
     * 
     * @param  int $id
     * @param  int $token
     * @return true $id
     */
    public function ResetPassword($id, $token)
    {
        // DECODE THE TOKEN
        $decodedtoken = base64_decode($token);

        // GET USERS DETAILS FROM DB
        $user = $this->AuthModel->find($id);

        // FETCH THE EXPIRY TIME FOR TOKEN
        $resetexpiry = $user['reset_expire'];
        $timenow = new Time('now');

        // CHECK TO SEE IF TOKEN HAS EXPIRED
        if (!$timenow->isBefore($resetexpiry)) {

            // IT HAS SO SET SOME FLASH DATA     
            $this->Session->setFlashData('danger', lang('Auth.linkExpired'));

            return true;
        }

        // CHECK TOKEN AGAINST TOKEN IN DB
        if (!password_verify($decodedtoken, $user['reset_token'])) {

            // DOES NOT MATCH SO SET SOME FLASH DATA          
            $this->Session->setFlashData('danger', lang('Auth.noAuth'));
            $this->Session->setFlashData('danger', lang('Auth.noAuth'));

            return true;
        } else {

            // RETURN USER ID TO USE ON PASSWORD RESET FORM
            $this->Session->setFlashData('success', lang('Auth.passwordAuthorised'));
            return $id;
        }
    }

    /**
     *--------------------------------------------------------------------------
     * FORGOT PASSWORD
     *--------------------------------------------------------------------------
     *
     * @param  int $email
     * @return void
     */
    public function Forgotpassword($email)
    {

        // FIND USER BY EMAIL
        $user = $this->AuthModel->where('email', $email)
            ->first();

        // GENERATE A NEW TOKEN
        // SET THE TOKEN TYPE AS SECOND PARAMETER. Reset password token = 'reset_token'	
        $encodedtoken  = $this->GenerateToken($user, 'reset_token');

        // GENERATE AND SEND RESET EMAIL
        $this->ResetEmail($user, $encodedtoken);

        return;
    }

    /**
     *--------------------------------------------------------------------------
     * UPDATE PASSWORD
     *--------------------------------------------------------------------------
     *
     * @param  array $user
     * @return void
     */
    public function updatePassword($user)
    {
        // UPDATE DB
        $this->AuthModel->save($user);

        // SET SOME FLASH DATA
        $this->Session->setFlashData('success', lang('Auth.resetSuccess'));
    }


    /**
     *--------------------------------------------------------------------------
     * SET USER SESSION
     *--------------------------------------------------------------------------
     *
     * Saves user details to session
     * 
     * @param  array $user
     * @return void
     */
    public function setUserSession($user)
    {   
        $data = [
            'id' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'email' => $user['email'],
            'role' => $user['role'],
            'isLoggedIn' => true,
            'ipaddress' => $this->request->getIPAddress(),
        ];

        $this->Session->set($data);
        $this->loginlog();

        return true;
    }

    /**
     *--------------------------------------------------------------------------
     * lOG LOGIN 
     *--------------------------------------------------------------------------
     *
     * Logs users login session to DB
     * 
     * @return void
     */
    public function loginlog(){

        // LOG THE LOGIN IN DB
        if ($this->Session->get('isLoggedIn')) {

            // BUILD DATA TO ADD TO auth_logins TABLE
            $logdata = [
                'user_id' => $this->Session->get('id'),
                'firstname' => $this->Session->get('firstname'),
                'lastname' => $this->Session->get('lastname'),
                'role' => $this->Session->get('role'),
                'ip_address' => $this->request->getIPAddress(),
                'date' => new Time('now'),
                'successfull' => '1',
            ];

            // SAVE LOG DATA TO DB
            $this->AuthModel->LogLogin($logdata);
        }

    }

    /**
     *--------------------------------------------------------------------------
     * lOG LOGIN FAILURE
     *--------------------------------------------------------------------------
     *
     * If user login / verification failed log an unsuccesfull login attempt
     * 
     * @param  mixed $email
     * @return void
     */
    public function loginlogFail($email)
    {
        // FIND USER BY EMAIL
        $user = $this->AuthModel->where('email', $email)
            ->first();
        
        if (!empty($user)) {

        // BUILD DATA TO ADD TO auth_logins TABLE
            $logdata = [
            'user_id' => $user['id'],
            'firstname' => $user['firstname'],
            'lastname' => $user['lastname'],
            'role' => $user['role'],
            'ip_address' => $this->request->getIPAddress(),
            'date' => new Time('now'),
            'successfull' => '0',
        ];

            // SAVE LOG DATA TO DB
            $this->AuthModel->LogLogin($logdata);
        }          
    }

    /**
     *--------------------------------------------------------------------------
     * REMEMBER ME
     *--------------------------------------------------------------------------
     *
     * if the remember me function is set to true in the config file
     * we set up a cookie using a secure selector|validator
     * 
     * @param  int $userID
     * @return void
     */
    public function rememberMe($userID)
    {


        // SET UP OUR SELECTOR, VALIDATOR AND EXPIRY 
        //
        // The selector acts as unique id so we dont have to save a user id in our cookie
        // the validator is saved in plain text in the cookie but hashed in the db
        // if a selector (id) is found in the auth_tokens table we then match the validators
        //
        $selector = random_string('crypto', 12);
        $validator = random_string('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $this->config->rememberMeExpire;


        // SET OUR TOKEN
        $token = $selector . ':' . $validator;

        // SET DATA ARRAY
        $data = [
            'user_id' => $userID,
            'selector' => $selector,
            'hashedvalidator' => hash('sha256', $validator),
            'expires' => date('Y-m-d H:i:s', $expires),
        ];        

        // CHECK IF A USER ID ALREADY HAS A TOKEN SET
        //
        // We dont really want to have multiple tokens and selectors for the
        // same user id. there is no need as the validator gets updated on each login
        // so check if there is a token already and overwrite if there is.
        // should keep DB maintenance down a bit and remove the need to do sporadic purges.
        //

        $result = $this->AuthModel->GetAuthTokenByUserId($userID);

        // IF NOT INSERT
        if (empty($result)) {
            $this->AuthModel->insertToken($data);
        } 
        // IF HAS UPDATE
        else {
            $this->AuthModel->updateToken($data);
        }

        // set_Cookie
        setcookie(
            "remember",
            $token,
            $expires,
            $this->AppConfig->cookiePath,
            $this->AppConfig->cookieDomain,
            $this->AppConfig->cookieSecure,
            $this->AppConfig->cookieHTTPOnly,
        );
    }

    public function IsLoggedIn()
    {
        if (session()->get('isLoggedIn')) {
            return true;
        }
        
    }
    /**
     *--------------------------------------------------------------------------
     * CHECK REMEMBER ME COOKIE
     *--------------------------------------------------------------------------
     *
     * checks to see if a remember me cookie has ever been set
     * if we find one w echeck it against our auth_tokens table and see
     * if we find a match and its still valid.
     * 
     * @return void
     */
    public function checkCookie()
    {
        if ($this->Session->get('lockscreen') == true){
           
           
            return;
        }
        // IS THERE A COOKIE SET?
        $remember = get_cookie('remember');

        // NO COOKIE FOUND
        if (empty($remember)) {
            return;
        }

        // GET OUR SELECTOR|VALIDATOR VALUE
        [$selector, $validator] = explode(':', $remember);
        $validator = hash('sha256', $validator);

        $token = $this->AuthModel->GetAuthTokenBySelector($selector);

        // NO ENTRY FOUND
        if (empty($token)) {

            return false;
        }

        // HASH DOESNT MATCH
        if (!hash_equals($token->hashedvalidator, $validator)) {

            return false;
        }

        // WE FOUND A MATCH SO GET USER ID
        $user = $this->AuthModel->find($token->user_id);

        // NO USER FOUND
        if (empty($user)) {

            return false;
        }

        // JUST BEFORE WE SET SESSION DATA AND LOG USER IN
        // LETS CHECK IF THEY NEED A FORCED LOGIN

        if ($this->config->forceLogin > 1) {

            // GENERATES A RANDOM NUMBER FROM 1 - 100
            // IF THIS NUMBER IS LESS THAN THE NUMBER IN THE FORCE LOGIN SETTING
            // DELETE THE TOKEN FROM THE DB

            if (rand(1, 100) < $this->config->forceLogin) {

                $this->AuthModel->DeleteTokenByUserId($token->user_id);               

                return;
            }
        }

        // SET USER SESSION
        $this->setUserSession($user, '1');

        $userID = $token->user_id;

        $this->rememberMeReset($userID, $selector);

        return;
    }

    /**
     *--------------------------------------------------------------------------
     * REMEMBER ME RESET
     *--------------------------------------------------------------------------
     *
     * each time a user is logged in using their remember me cookie
     * reset the validator and update the db
     * 
     * @param  int $userID
     * @param  int $selector
     * @return void
     */
    public function rememberMeReset($userID, $selector)
    {
        // DB QUERY        
        $existingToken = $this->AuthModel->GetAuthTokenBySelector($selector);

        if (empty($existingToken)) {

            return $this->rememberMe($userID);
        }

        $validator = random_string('crypto', 20);
        $expires = time() + 60 * 60 * 24 * $this->config->rememberMeExpire;

        // SET OUR TOKEN
        $token = $selector . ':' . $validator;

        if ($this->config->rememberMeRenew) {

            // SET DATA ARRAY
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
                'expires' => date('Y-m-d H:i:s', $expires),
            ];
        } else {
            // SET DATA ARRAY
            $data = [
                'hashedvalidator' => hash('sha256', $validator),
            ];
        }

       $this->AuthModel->UpdateSelector($data, $selector);

        // SET COOKIE        
        setcookie(
            "remember",
            $token,
            $expires,
            $this->AppConfig->cookiePath,
            $this->AppConfig->cookieDomain,
            $this->AppConfig->cookieSecure,
            $this->AppConfig->cookieHTTPOnly,
        );
    }

    public function lockScreen()
    {
        if ($this->config->lockScreen) {

            $this->Session->set('isLoggedIn', false);
            $this->Session->set('lockscreen', true);

            return true;
        }

        return false;
    }

    /**
     *--------------------------------------------------------------------------
     * LOGOUT
     *--------------------------------------------------------------------------
     *
     * @return void
     */
    public function logout()
    {
        // REMOVE REMEMBER ME TOKEN FROM DB 
        $this->AuthModel->DeleteTokenByUserId($this->Session->get('id'));

        //DESTROY SESSION
        $this->Session->destroy();

        return;
    }

    public function autoredirect()
    {

        // AUTO REDIRECTS BASED ON ROLE 
        $redirect = $this->config->assignRedirect;

        return  $redirect[$this->Session->get('role')];

    }
}
