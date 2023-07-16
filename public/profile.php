<?php 
 
$user_id = get_current_user_id();
$fname = get_usermeta($user_id, "first_name");
$imagePath = get_usermeta($user_id , "user_profile_img_path");
$imageUrl = get_usermeta($user_id , "user_profile_img_url");


if(isset($_POST["Update"])) {
    

        if($_FILES["user_img"]["error"] ==0){
            $file = $_FILES["user_img"];
            echo "<pre>";
            // print_r($file);
            $ext= explode("/" , $file["type"])[1];
            $file_name= "$user_id.$ext";

        $image = wp_upload_bits($file_name , null ,file_get_contents($file["tmp_name"]) );

            if(!metadata_exists('user' , $user_id , 'user_profile_img_url')){
                add_user_meta($user_id , "user_profile_img_url" , $image['url']);
                add_user_meta($user_id , "user_profile_img_path" , esc_sql($image['file']));
                // print_r($image);
            }else{
                update_user_meta($user_id , "user_profile_img_url" , $image['url']);
                update_user_meta($user_id , "user_profile_img_path" , esc_sql($image['file']));
                // print_r($image);
            }
        }
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
// echo $imagePath;
?>

<?php

if($imageUrl !=""){
    ?>
<img src=" <?php echo $imageUrl ; ?>" alt="<?php echo $imageUrl ; ?>" />
    <?php
}
?>

<h1>Hello: <?php echo" $fname $user_id"; ?></h1>

<div>
    <form action="" method="POST" enctype="multipart/form-data">
        
        Profile Image: <input type="file" name="user_img" id="user_img"  /> <br/>
        Name: <input type="text" name="user_fname" id="fname_input" value="<?php echo $fname; ?>" /> 
        <input type="hidden" value="<?php echo $user_id; ?>" name="user_id" />
        <input type="submit" id="submit_btn" name="Update" />
    </form>
    <p> <a href=" <?php echo wp_logout_url() ; ?>">Logout</a></p>
</div>
