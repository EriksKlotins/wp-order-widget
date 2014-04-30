<?php


class WtfOrderWidget extends WP_Widget {

	private $settigns = null;


	function WtfOrderWidget() 
	{
		// Instantiate the parent object
		parent::__construct( false, 'Wtf Order Widget' );

		if ($option = get_option('wtf-order-widget-options'))
		{
			$this->settings = unserialize($option);
		}
		else
		{
			$this->settings = $this->getDefaultSettings();
		}

		add_action( 'wp_enqueue_scripts', array(&$this,'registerScripts') );

		
	}

	function registerScripts()
	{
		wp_enqueue_script('jquery');
		wp_register_script('wtf-order-widget-widget.js',content_url().'/plugins/wp-order-widget/assets/js/widget.js');
		wp_localize_script( 'wtf-order-widget-widget.js', 'ajaxurl',admin_url( 'admin-ajax.php' ) );
		wp_enqueue_script('wtf-order-widget-widget.js');
	}

	
	function widget( $args, $instance ) 
	{		

	
		echo $args['before_widget'];
		echo $args['before_title']. $this->settings->title.$args['after_title'];
		include dirname(__FILE__).'/../templates/widget.php';
		echo $args['after_widget'];
	}

	function update( $new_instance, $old_instance ) 
	{
	
	}

	private function getDefaultSettings()
	{

		$result = new stdClass();
		$result->title = 'Pasūtījumu forma';
		return $result;
	}


	function form( $instance ) 
	{

		$title_option_name = $_POST['sidebar'].'-'.'widget-pasakumi-title';
		//echo $title_option_name;
		if (isset($_POST['widget-pasakumi-title'] ))
		{		
			update_option($title_option_name, $_POST['widget-pasakumi-title']);
		}
		$title = get_option($title_option_name,"Pasūtījumi");

		?>
		<p>
			<label for="widget-pasakumi-title">Widget title</label>
			<input type="text" class="widefat" id="widget-pasakumi-title" name="widget-pasakumi-title" value="<?php echo $title;?>"/>
		</p>
		<?php
	}
}