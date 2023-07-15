<?php 
 
$user_id = get_current_user_id();
$fname = get_usermeta($user_id, "first_name");
if(isset($_POST["Update"])) {
    $fname = esc_sql($_POST["user_fname"]);
    $userdata = array(
        "ID" => $user_id,
        "first_name" => $fname
    );
    $result = wp_update_user($userdata);

    if(is_wp_error($result)){
        echo "Cannot update the user: " . $result->get_error_message();
    }

    $user = get_userdata($user_id);
    if($user != false){
        $fname = get_usermeta($user_id, "first_name");
    }
}
?>

<h1>Hello: <?php echo" $fname $user_id"; ?></h1>

<div>
    <form action="" method="POST">
        Name: <input type="text" name="user_fname" id="fname_input" value="<?php echo $fname; ?>" /> 
        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" />
        <input type="submit" id="submit_btn" name="Update" />
    </form>
    <p> <a href=" <?php echo wp_logout_url() ; ?>">Logout</a></p>
</div>
