<?php

/**
 * Plugin Name:  WTF Order Widget
 * Plugin URI:   http://wtf.lv
 * Description:  Simple order form
 * Version:      0.1
 * Author:       WTF
 * Author URI:   http://wtf.lv
 * License:      commercial
 * Text Domain:  wtf-order-form
 */


require_once dirname(__FILE__).'/classes/widget.php';


function wtf_order_widget() 
{
	register_widget( 'WtfOrderWidget' );
}

	

function handleSubmitData()
{
	ob_clean();
	$mail = $_POST['mail'];
	$name = $_POST['name'];
	$admin_email = get_settings('admin_email');
    
    $content = [
    				'You have a new order from: '.$name,
    				'Email: '.$mail,
    				'Date: '.date('d/m/Y'),
    				'',
    				'Items: (code, quantity)'
       			];


    $rows = json_decode(stripslashes($_POST['rows']));
   
   	foreach($rows as $row)
   	{
   		$content[] = $row->code.chr(9).chr(9).$row->qty;
   	}

   	$content = implode(chr(10).chr(32), $content);
  // 	echo $content;
    wp_mail($admin_email,'New order: '.get_bloginfo('name'),  $content);
    die();
}


add_action( 'widgets_init', 'wtf_order_widget' );
add_action( 'wp_ajax_wtf_order_widget_submit', 'handleSubmitData');
add_action( 'wp_ajax_nopriv_mywtf_order_widget_submit', 'handleSubmitData' );