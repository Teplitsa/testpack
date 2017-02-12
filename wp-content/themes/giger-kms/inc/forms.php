<?php
/**
 * Forms related functions and tags
 ***/

/** Formidable fields styling **/
add_filter('frm_submit_button_class', 'tst_formidable_submit_classes', 2, 2);
function tst_formidable_submit_classes($class, $form){


	$class[] = 'tst-submit-button';

	return $class;
}


add_filter('frm_field_classes', 'tst_formidable_field_classes', 2, 2);
function tst_formidable_field_classes($class, $field){

	if(in_array($field['type'], array('text', 'email', 'textarea', 'url', 'number'))) {
		$class = 'tst-textfield__input';
	}
	elseif($field['type'] == 'checkbox'){

		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){
			$class .= " tst-checkbox__input";
		}
		else {
			$class = "tst-checkbox__input";
		}
	}
	elseif($field['type'] == 'radio'){
		$class = "tst-radio__button";
	}
	elseif($field['type'] == 'file'){
		$class = "tst-file__input";
	}
	elseif($field['type'] == 'date'){
		$class = "tst-date__input";
	}


	return $class;
}

add_filter('frm_replace_shortcodes', 'tst_formidable_default_html', 2, 3);
function tst_formidable_default_html($html, $field, $params) {

	if(in_array($field['type'], array('text', 'email', 'number', 'url', 'textarea')))  {

		$html = str_replace(array('frm_form_field', 'form-field'), 'tst-textfield frm_form_field', $html);

		$html = str_replace('frm_primary_label', 'tst-textfield__label frm_primary_label', $html);
		$html = str_replace('frm_error', 'tst-textfield__error frm_error', $html);

		if(isset($field['read_only']) && (int)$field['read_only'] == 1){
			$html = str_replace('<input', '<input disabled="disabled" ', $html);
		}

		//$html = str_replace('<input', '<input autocomplete="on" ', $html);

		preg_match('/<div class=\"frm_description\">(.*?)<\/div>/s',$html, $m);

		if(isset($m[1]) && !empty($m[1])){
			$html = str_replace('<div class="frm_description">'.$m[1].'</div>', '', $html);
		}
	}
	elseif($field['type'] == 'checkbox'){
		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){
			$html = str_replace('<label for=', '<label class="tst-checkbox" for=', $html);
		}
		else {
			$html = str_replace('<label for=', '<label class="tst-checkbox" for=', $html);
		}

		$html = str_replace('frm_form_field', 'tst-inputfix frm_form_field', $html);
		$html = str_replace('frm_primary_label', 'tst-inputfix__label frm_primary_label', $html);

	}
	elseif($field['type'] == 'radio'){

		$html = str_replace('<label for=', '<label class="tst-radio" for=', $html);
		$html = str_replace('frm_primary_label', 'tst-inputfix__label frm_primary_label', $html);
		$html = str_replace('frm_form_field', 'tst-inputfix frm_form_field', $html);
	}
	elseif($field['type'] == 'select'){

		$html = str_replace('frm_form_field', 'tst-select frm_form_field', $html);
		$html = str_replace('frm_primary_label', 'tst-inputfix__label frm_primary_label', $html);

		preg_match("#<\s*?select\b[^>]*>(.*?)</select\b[^>]*>#s", $html, $l);
		if(!empty($l)){
			$html = str_replace($l[0], '<label class="tst-select-wrap">'.$l[0].'</label>', $html); //delete label
		}
	}
	elseif($field['type'] == 'file'){
		$html = str_replace('frm_form_field', 'tst-file-upload frm_form_field', $html);
	}
	elseif($field['type'] == 'date'){
		$html = str_replace('frm_form_field', 'tst-date frm_form_field', $html);
		preg_match('/<div class=\"frm_description\">(.*?)<\/div>/s',$html, $m);

		if(isset($m[1]) && !empty($m[1])){
			$html = str_replace('<div class="frm_description">'.$m[1].'</div>', '', $html);
		}
	}

	return $html;
}


add_filter('frm_field_label_seen', 'tst_formidable_input_options_html', 2, 3);
function tst_formidable_input_options_html($opt, $opt_key, $field) {

	if(is_admin())
		return $opt;

	if($field['type'] == 'checkbox') {
		if(isset($field['classes']) && false !== strpos($field['classes'], 'switch')){
			$opt = "<span class='tst-checkbox__label'>{$opt}</span>";
		}
		else {
			$opt = "<span class='tst-checkbox__label'>{$opt}</span>";
		}

	}
	elseif($field['type'] == 'radio') {
		$opt = "<span class='tst-radio__label'>{$opt}</span>";
	}

	return $opt;
}

add_filter('frm_filter_final_form', 'tst_formidable_submit_button_html', 50);
function tst_formidable_submit_button_html($html){


	$html = preg_replace ('#<\s*?fieldset\b[^>]*>#s' , '' , $html);
	$html = preg_replace ('#</fieldset\b[^>]*>#s' , '' , $html);

	return $html;
}

