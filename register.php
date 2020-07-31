<?php
require_once 'core/init.php';

/*Check if the token is good
 var_dump(Token::check(Input::get('token'))); This passes in the token supplied by the form.
*/
if(Input::exists()) {
    if(Token::check(Input::get('token'))) { //Token validation - This should be used anywhere information is subbmitted by the user.


        $validate = new Validate();
        $validation = $validate->check($_POST, array(
                'username' => array(
                    // 'name' => 'Username' , (to keep $item from displaying the actual DB field name)
                    'required' => true,
                    'min' => 2,
                    'max' => 20,
                    'unique' => 'users' // Can be changed to not reveal field names in the DB.
                ),
                'password' => array(
                    'required' => true,
                    'min' => 6
                ),
                'password_again' => array(
                    'required' => true,
                    'matches' => 'password'
                ),
                'name' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50
                ),
                'email' => array(
                    'min' => 9,
                    'max' => 50,
                    'unique' => 'users'
                ),
                'phone' => array(
                    'min' => 10,
                    'max' => 10,
                    'unique' => 'users'
                ) 
        ));             
        /* 
            The double arrow operator, => is used as an access mechanism for arrays. 
                Means that what is on the left side of it will have a corresponding value of what is on the right side of it in array context.
        
            The object operator, -> is used in an object scope to access methods and properties of an object. 
                It says that what is on the right of the operator is a member of the object instantiated into the variable on the left side of the operator.
                     
                -> Call a method on the object of a class. 
                => Assign values to the keys of an array.
        */


        if($validation->passed()) { // Test for 'validation' pass.
            $user = new User(); //Instantiate the user, give access to the DB if already connected.

           
            
            try {
             
                $user->create(array(        //create method in User.php
                    'username' => Input::get('username'),                
                    'password' => password_hash(Input::get('password'), PASSWORD_DEFAULT),  //This hash method includes a password salt by default.   
                    'name' => Input::get('name'),    
                    'joined' => date('Y-m-d H:i:s'), //enters the current date and time according to the computer.
                    'groupid' => 1,
                    'email' => Input::get('email'),
                    'phone' => Input::get('phone')
                ));    

                Session::flash('home', 'You have been registered and can now log in!');
                Redirect::to('index.php');

            } catch(Exception $e) {     
                die($e->getMessage());
            }

        } else {
                foreach($validation->errors() as $error) {
                    echo $error, '<br>';    // output errors
                }
            
        }
    }
}
?>

<form action="" method="post">
    <div class="field">
        <label for="username">Username</label>
        <input type="text" name="username", id="username" value="<?php echo escape(Input::get('username')); ?>" autpcomplete="off">
    </div>

    <div class="field">
        <label for="password">Choose a password</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="password_again">Enter your password again</label>
        <input type="password" name="password_again" id="password_again">
    </div>

    <div class="field">
        <label for="name">Your name</label>
        <input type="text" name="name" value="<?php echo escape(Input::get('name')); ?>" id="name">
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input type="text" name="email", id="email" value="<?php echo escape(Input::get('email')); ?>" autpcomplete="off">
    </div>

    <div class="field">
        <label for="phone">Phone</label>
        <input type="text" name="phone", id="phone" value="<?php echo escape(Input::get('phone')); ?>" autpcomplete="off">
    </div>

    <!-- Cross Site Request Forgery (CSRF) Protection. register.php, Token.php -->
    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> <!-- Calling the generate function for Session/Token. -->
    <input type="submit" value="Register">
</form>