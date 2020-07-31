<?php
require_once 'core/init.php';

$user = new User();

if(!$user->isLoggedIn()) {
    Redirect::to('index.php');
}   // If user is not logged in, redirect the to the home page.

if(Input::exists()) {
    if(Token::check(Input::get('token'))) {
        
        $validate = new Validate();
        $validation = $validate->check($_POST, array(
            'event' => array(
                'required' => true,
                'min' => 2,
                'max' => 50
            )
        ));

        if($validation->passed()) {
            
            try {   //Throw exception inside of the user method if DB operation fails.
                $user->update(array(
                    'name' => Input::get('name')
                ));

                Session::flash('home', 'Your details have been updated.');
                Redirect::to('index.php');

            } catch(Exception $e) {
                die($e->getMessage());  //NOTE: getMessage is part of the Exception object in php.
            }
        } else {
            foreach($validation->errors() as $error) {
                echo $error, '<br>';
            }
        }
    }
} //Check the token that is supplied is the same as the session

?>

<form action="" method="post">
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo escape($user->data()->name); ?>"> 
    
   
        <input type="submit" value="Update">
        <input type="hidden" name="token" value="<?php echo token::generate(); ?>">
    </div>
</form> 



<!-- 
,
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




'email' => Input::get('email'),
                    'phone' => Input::get('phone')



<div class="field">
        <label for="email">Email</label>
        <input type="text" name="email" value="<?php echo escape($user->data()->email); ?>"> </div>
    <div class="field">
        <label for="phone">Phone Number</label>
        <input type="text" name="phone" value="<?php echo escape($user->data()->phone); ?>"> </div>
-->