<?php 
class Token {
    public static function generate() {
        return Session::put(Config::get('session/token_name'), Hash::unique());  
    }

    /* Pass in a token from a session and check if it is the same token that was defined in the register form.
        check($token): check if the token exists in the session, if (token = session) currently being applied - Delete 
        that session, return 'true'.
    */
    
    public static function check($token) {
        $tokenName = Config::get('session/token_name'); 
       
        if(Session::exists($tokenName) && $token === Session::get($tokenName)) {  //$tokenName defined in config.php(session/token_name).
            Session::delete($tokenName);
            return true;
        }
        return false;
    }
}





/* Cross Site Request Forgery (CSRF) Protection. register.php, Token.php, init.php,
        Token class: 1:allows us to generate a token. 
                     2: Check if a token exists and is valid.
                     3: Delete a token.

Steps:
1. Grabbing the token from register.php--   <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> 
    Which has been set in the session already [(the token in the scorce form) Also set as a session for the user]
2. User submits form... which uses Token::check in register.php--   var_dump(Token::check(Input::get('token')));
    To pass in the token thats supplied by the form... 
3. Token::check/ Token.php  then checks if a session exists with the set token and that the token that is supplied matches the session.
        If it matches we delete it because we dont need it anymore and return true. (CSRF)...FAILED
*/