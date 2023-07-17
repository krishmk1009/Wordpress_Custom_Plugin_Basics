<?php
if(!defined('ABSPATH')){
    header("Location: /mycontactformplugin");
    die("");
 }

global $wpdb , $table_prefix;
    $wp_emp = $table_prefix."emp";

    $q ="DROP TABLE `$wp_emp`";
    $wpdb->query($q);