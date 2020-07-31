<?php
require_once 'core/init.php';

$user = new User();



?>




<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.btn {
  border: none;
  color: white;
  padding: 14px 28px;
  font-size: 16px;
  cursor: pointer;
}

.success {background-color: #4CAF50;} /* Green */
.success:hover {background-color: #46a049;}

.info {background-color: #2196F3;} /* Blue */
.info:hover {background: #0b7dda;}

.warning {background-color: #ff9800;} /* Orange */
.warning:hover {background: #e68a00;}

.danger {background-color: #f44336;} /* Red */ 
.danger:hover {background: #da190b;}

.default {background-color: #e7e7e7; color: black;} /* Gray */ 
.default:hover {background: #ddd;}
</style>
</head>
<body>

<h1>Web Reminder Alerts</h1>

<form action="" method="post">
    <div class="button">
      <button> <a href="index.php">Home</a></button>
      <button> <a href="btn Calender">Calender</a></button>
      <button> <a href="createevent.php">Events</a></button>
      <button> <a href="btn Group">Group</a></button>
      <button> <a href="btn User Login">User Login</a></button>
      <button> <a href="createevent.php">Events<a/></button>
    </div>
</form> 




</body>
</html>
