<?php

class Event extends User {

   
        

    public function create($fields = array()) { // Ability to creat a user.

        if(!$this->db()->insert('events', $fields)) {
            throw new Exception('There was a problem creating event.');
        }
    }


}

