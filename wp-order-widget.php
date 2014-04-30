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
	$mail        = $_POST['mail'];
	$name        = $_POST['name'];
  $phone       = $_POST['phone'];
  $company     = $_POST['company'];
  $delivery    = $_POST['delivery'];
  $comment     = $_POST['comment'];
	$admin_email = get_settings('admin_email');
    
    $content = array(
    				'You have a new order!',
            'Name: '.$name,
    				'Email: '.$mail,
            'Phone: '.$phone,
            'Company: '.$company,
            'Delivery date: '.$delivery,
            'Comment: '.$comment,
    				'Date: '.date('d/m/Y'),
    				'',
    				'Items: (code, quantity)'
       		);


    $rows = json_decode(stripslashes($_POST['rows']));
   
   	foreach($rows as $row)
   	{
   		$content[] = $row->code.chr(9).chr(9).$row->qty;
   	}

   	$content = implode(chr(10).chr(32), $content);
  // 	echo $content;
    $a =  wp_mail($admin_email,'Jauns pasūtījums: '.get_bloginfo('name'),  $content);
    $b = wp_mail($mail,'Jūsu pasūtījums: '.get_bloginfo('name'),  $content);
    var_dump($a, $b);
    ob_flush();
    die();
}

add_action( 'widgets_init', 'wtf_order_widget' );
add_action( 'wp_ajax_wtf_order_widget_submit', 'handleSubmitData');
add_action( 'wp_ajax_nopriv_wtf_order_widget_submit', 'handleSubmitData' );