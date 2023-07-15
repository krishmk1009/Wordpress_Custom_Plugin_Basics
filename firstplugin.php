<?php

/**
 * Plugin Name: testplugin
 * Description: This is just a plugin for testing purpose
 * Author: Krushna
 * Version: 1.0
 */

 if(!defined('ABSPATH')){
    header("Location: /firstproject");
    die("");
 }

 echo "hi this is test plguin";

 function my_plugin_activation_function() {
    global $wpdb , $table_prefix;
    $wp_emp = $table_prefix."emp";

    $q="CREATE TABLE IF NOT EXISTS `$wp_emp` (`Id` INT NOT NULL , `Name` TEXT NOT NULL , `Age` INT NOT NULL ) ENGINE = InnoDB;";
    $wpdb->query($q);

    // $q="INSERT INTO `$wp_emp` (`Id`, `Name`, `Age`) VALUES ('1', ABS('Krushna'), '12')";
    // $wpdb->query($q);
$data = array(
    "Id"=>2,
    "Name"=>"kishu",
    "Age"=>14
);

    $wpdb->insert($wp_emp, $data);
}

 register_activation_hook( __FILE__, 'my_plugin_activation_function' );


 function my_plugin_deactivation_function() {
    global $wpdb , $table_prefix;
    $wp_emp = $table_prefix."emp";

    // $q ="DROP TABLE `$wp_emp`";
    $q ="TRUNCATE TABLE `$wp_emp`";

    $wpdb->query($q);
}

 register_deactivation_hook( __FILE__, 'my_plugin_deactivation_function' );

//  -----------------------------------
//  basic shortcode: 

// function sc_fun(){
//     return "Hello how are you";
// }
// add_shortcode("sc-code" , "sc_fun");


// -----------------------------------------------------
// custom shortcode with single attributes,multi attributes and default values
// function my_sc_fun($atts){
//     $atts = shortcode_atts(array(
//         "msg1"=>"default1",
//         "msg2"=>"default2"
//     ),$atts);

//     return "result: ".$atts["msg1"].$atts['msg2'];
// }
// add_shortcode("sc-code" ,'my_sc_fun');

// -----------------------------------------------------------------------------------------

/*
//custom shortcode for including html and conditional rendering
function my_sc_fun($atts){
    $atts = shortcode_atts(array(
        "type"=>"img1",
        
    ),$atts);

    include $atts['type'].".php";
}
add_shortcode("sc-code" ,'my_sc_fun');


*/
// --------------------------------------------------------------------------------------
// include scripts and stylesheets

function my_custom_script_fun(){
    $path= plugins_url("/js/main.js", __FILE__);
    $dep = array("jquery");
    $ver=fileatime(plugin_dir_path(__FILE__)."js/main.js");
    
    $log = is_user_logged_in() ?1 :0;
    wp_add_inline_script("my_custom_js", "user logged in: ".$log , "after");

    if(is_page("sample-page")){
        wp_enqueue_script("my_custom_js" , $path, $dep , $ver , true);
    }
}

add_action("wp_enqueue_scripts", "my_custom_script_fun");

// / -----------------------------------------------------------------------------------------


//get results from databas and show them on page
function my_sc_fun(){
    global $wpdb , $table_prefix;
    $wp_emp = $table_prefix."emp";
    $q= "SELECT * FROM `$wp_emp`";
    $results = $wpdb ->get_results($q);
   
    
    // print_r($results);

    ob_start();
    ?>
    <table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Age</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result): ?>
            <tr>
                <td><?php echo $result->Id; ?></td>
                <td><?php echo $result->Name; ?></td>
                <td><?php echo $result->Age; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    <?php
    $html = ob_get_clean();
    echo $html;

}
add_shortcode("sc-code" ,'my_sc_fun');



// --------------------------------------------------------------------------------------

function get_data_using_wp_query(){
    $arrgs = array(
        "post_type"=>"post",
        // "s"=>"Sample Page"
    );

    $query = new WP_Query($arrgs);

    ob_start();
    if($query->have_posts()):
    ?>

<ul>

<?php
while($query->have_posts()){
    $query->the_post();
    echo "<a href=".get_permalink()." ><li>".get_the_title()."</li></a>";
    // echo "<li>".get_the_content()."</li>";
}

?>

</ul>

<?php
endif;
wp_reset_postdata();
$html = ob_get_clean();
return $html;

}
add_shortcode("get_data" , "get_data_using_wp_query");


// -----------------------------------------------------------------------------------
// Create the view count and increment as the user visits the blog
function wp_head_fun(){
    if(is_single()){
        global $post;

        $views = get_post_meta($post->ID, "views",true);
    
        if($views ==""){
            add_post_meta($post->ID,"views",1);
        }
        else{
            $views++;
            update_post_meta($post->ID,"views", $views);
        }
    
    }
}


add_action("wp_head" , "wp_head_fun");

function my_get_views_fun(){
    global $post;

    $views = get_post_meta($post->ID, "views",true);

    return "total views: ". $views;
}
add_shortcode("view_count" , "my_get_views_fun");

// --------------------------------------------------------------------------------
//Create a admin page
function my_page_menu_fun(){
    echo "Hiii";
}

function submenu_fun(){
    include "admin/submenu.php";
}
function custom_menu(){
    add_menu_page("my_plugin_page" , "my_plugin_title" , "manage_options" , "my-page-plugin" ,"my_page_menu_fun" ,"dashicons-media-spreadsheet" );

    add_submenu_page("my-page-plugin", "my_submennu1", "my_submennu1","manage_options","my-submenu1", "submenu_fun");
}
add_action("admin_menu","custom_menu");


// -----------------------------------------------------------
// custom post types
function my_cpt(){
    $lables=array(
        'name'=>"Cars",
        'singular_name'=>"Car"
    );
    $supports=array(
        "title" , "editor","thumbnail","comments","excerpts"
    );
    $options=array(
        'labels'=>$lables,
        "public"=>true,
        "has_archive"=>true,
        "rewrite"=>array("slug"=>"cars"),
        "show_in_rest"=>true,
        "supports"=>$supports,
        "taxonomies"=>array(
            "car_types"
        ),
        "publicaly_queryable"=>true
    );
    register_post_type("cars" , $options);
}


add_action("init", "my_cpt");


function register_car_types(){
    $lables=array(
        'name'=>"Car Type",
        'singular_name'=>"Car Types"
    );
    $options=array(
        'labels'=>$lables,
        "hierarchical"=>true,
        "rewrite"=>array("slug"=>"car-type"),
        "show_in_rest"=>true
        
    );

    register_taxonomy("car-type" , array("cars"),$options);
}

add_action("init", "register_car_types");




// ---------------------------------------------------------------------------

// create user page
function my_create_user_fun(){
    ob_start();
    include "public/register.php";
    return ob_get_clean();
}
add_shortcode("ct_user_code" , "my_create_user_fun");

// --------------------------------------------------------------------------------------

// Login functionality and redirection
function my_user_login_fun(){
    if(isset($_POST["user_username"])){
        $username = esc_sql($_POST["user_username"]);
        $password = esc_sql($_POST["user_password"]);

        $credentials=array(
            "user_login"=>$username,
            "user_password"=>$password

        );
        $user = wp_signon($credentials);


        if(!is_wp_error($user)){
            echo "Login Succesfully";
            if($user->roles[0]=="administrator"){
                wp_redirect(admin_url());
                 exit();
            }
            else{
                wp_redirect("/firstproject");
                exit();
            }
            
           
        }
        else{
            echo  $user->get_error_message();
        }
    }
}
add_action("template_redirect" , "my_user_login_fun");


// --------------------------------------------------------------------------------------