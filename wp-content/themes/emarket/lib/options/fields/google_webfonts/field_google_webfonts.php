<?php
class Emarket_Options_google_webfonts extends Emarket_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since Emarket_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		$this->field['fonts'] = array();
		
		$fonts = get_transient('emarket-opts-google-webfonts');
		if(!is_array(json_decode($fonts))){
			
			$fonts = wp_remote_retrieve_body(wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?key='. apply_filters( 'sw_googlefont_api_key_filter', $this->args['google_api_key'] ) ) );
			set_transient('emarket-opts-google-webfonts', $fonts, 60 * 60 * 24 * 30);
				
		}
		$this->field['fonts'] = json_decode($fonts);
		
	}//function
	


	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since Emarket_Options 1.0
	*/
	function render(){

		$class = (isset($this->field['class']))?'class="'.esc_attr( $this->field['class'] ).'" ':'';
		
		
		echo '<select id="'.esc_attr( $this->field['id'] ).'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" '.$class.'>';
		echo '<option value="" selected>'.esc_html__( 'Select Font', 'emarket' ).'</option>';
		
		foreach($this->field['fonts']->items as $cut){
			echo '<option value="'. esc_attr( $cut->family ).'" '.selected($this->value, $cut->family, false).'>'. $cut->family .'</option>';
		}
		echo '</select>';
		echo '<p class="description">'.sprintf( __('Please <a href="%s" target="_blank">browse the directory</a> to preview a font, then select your choice below.', 'emarket') , esc_url( 'http://www.google.com/webfonts' ) ).'</p>';
		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <div><span class="description">'.$this->field['desc'].'</span></div>':'';
	}//function
	
}//class
?>