<?php
require_once 'core/init.php';




if(Input::exists()) {
    if(Token::check(Input::get('token'))) { //Token validation - This should be used anywhere information is subbmitted by the user.

        $validate = new Validate();
        $validation = $validate->check($_POST, array(
                'event' => array(
                    'required' => true,
                    'min' => 2,
                    'max' => 50,
                ),

                'details' => array(
                    'min' => 6,
                    'max' => 200
                ),

                'start' => array(
                   // 'required' => true,
                ),

                'end' => array(
                   // 'required' => true,
                )
        ));

        if($validation->passed()) {
            $event = new Event();
            try {

                $event->create(array(        //create (User class method in User.php
                    'event' => Input::get('event'),
                    'details' => Input::get('details'),
                    'start' => Input::get(date('start')),
                   // 'end' => Input::get(date('end'))
                ));

                Session::flash('home', 'Your event has been created!');
                Redirect::to('userpage.php');

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
        <label for="event">Event</label>
        <input type="text" name="event", id="event" value="<?php echo escape(Input::get('event')); ?>" autpcomplete="off">
    </div>

    <div class="field">
        <label for="details">Description</label>
        <input type="text" name="details" id="details" value="<?php echo escape(Input::get('details')); ?>" autpcomplete="off">
    </div>

    <div class="field">
        <label for="start">Start Date</label>
        <input type="datetime" name="start" id="start" value="<?php echo escape(Input::get('start')); ?>" >
    </div>

    <div class="field">
        <label for="end">End Date</label>
        <input type="datetime" name="end" id="end" value="<?php echo escape(Input::get('end')); ?>" >
    </div>

    <input type="hidden" name="token" value="<?php echo Token::generate(); ?>"> <!-- Calling the generate function for Session/Token. -->
    <input type="submit" value="Register">

</form>
