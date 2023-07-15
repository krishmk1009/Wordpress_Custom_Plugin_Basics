<?php

if(isset($_POST["Regsiter"])){

    global $wpdb;

    $fname = $wpdb->escape($_POST["user_fname"]);
    $fusername = $wpdb->escape($_POST["user_username"]);
    $email = $wpdb->escape($_POST["user_email"]);
    $password = $wpdb->escape($_POST["user_password"]);
    $confPass = $wpdb->escape($_POST["user_password_conf"]);


    if($password ==$confPass ){
        // wp_insert_user()
        // wp_create_user()

        $result = wp_create_user( $fusername,$password ,$email);

        if(!is_wp_error($result)){
            echo "user created succesfully with Id:".$result;
        }
        else{
            echo $result->get_error_message();
        }
    }
    else{
        echo " Password must match";
    }
}

?>

<div>
    <h1>Login</h1>
    <form action="" method="POST">
    Username: <input type="text" name="user_username" id="username_input" /> <br/>
    Password: <input type="password" name="user_password" id="password_input" /> <br/>
    <input type="submit" id="submit_button"  name="Login"/>

    </form>
</div>
<br/>

<h1>Register</h1>
<form action="<?php echo get_the_permalink(); ?>" method="POST">
Name: <input type="text" name="user_fname" id="fname_input" /> <br/>
Username: <input type="text" name="user_username" id="username_input" /> <br/>
Email: <input type="email" name="user_email" id="email_input" /> <br/>
Password: <input type="password" name="user_password" id="password_input" /> <br/>
Confirm Pass: <input type="password" name="user_password_conf" id="password_conf_input" /> <br/>
<input type="submit" id="submit_button"  name="Regsiter"/>
</form>