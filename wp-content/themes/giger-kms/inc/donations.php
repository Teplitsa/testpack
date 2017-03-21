<?php
/**
 * Text PM Customisation Example
 **/

if(!class_exists('Leyka_Payment_Method'))
	return;

/** Custom donation functions */

add_filter('leyka_icons_text_text_box', 'tst_text_pm_icon');
function tst_text_pm_icon($icons){
	//size 155x80 px
	
	$icons = array(get_template_directory_uri().'/assets/images/text-box.png');
		
	return $icons;
}

//no icon for text gateway
add_filter('leyka_icons_text_text_box', 'tst_empty_icons');	
function tst_empty_icons($icons){
	return array();
}


/** Form template **/
function tst_amount_field(Leyka_Payment_Form $form){

	if( !defined('LEYKA_VERSION') ) {
		return;
	}

	$supported_curr = leyka_get_active_currencies();
	$current_curr = $form->get_current_currency(); 

	if(empty($supported_curr[$current_curr])) {
		return; // Current currency isn't supported
	}

	$curr_mark = esc_attr($supported_curr[$current_curr]['label']);
?>

<div class="leyka-field amount-selector amount mixed">
<div class="currency-selector-row">
	<div class="currency-variants">

<?php foreach($supported_curr as $currency => $data) {

	$variants = explode(',', $data['amount_settings']['fixed']);?>

	<div class="<?php echo $currency;?> amount-variants-container" <?php echo $currency == $current_curr ? '' : 'style="display:none;"';?> >
		<div class="amount-variants-row">
			<?php foreach($variants as $i => $amount) { $input_id = leyka_pf_get_form_id()."-$amount-$currency";?>
            <input type="radio" value="<?php echo (int)$amount;?>" name="leyka_donation_amount" id="<?php echo $input_id;?>" class="figure-radio__button" <?php checked($i, 0);?> <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?>>
            <label class="figure" for="<?php echo $input_id;?>">
                <?php echo (int)$amount;?>&nbsp;<?php echo $curr_mark;?>
            </label>
			<?php }?>

			<label class="figure-flex tst-textfield">
                <input type="text" title="<?php echo __('Specify the amount of your donation', 'leyka');?>" name="leyka_donation_amount" class="donate_amount_flex figure-flex-input__text tst-textfield__input" value="" placeholder="Другая сумма" maxlength="6" size="12" <?php echo $currency == $current_curr ? '' : 'disabled="disabled"';?>>
            </label>
            <div class="currency"><?php echo $form->get_currency_field();?></div>
		</div>
	</div>
	<?php } ?>
	</div>

</div>

<div style="display: none;" class="leyka_donation_amount-error field-error frm_error"></div>
	
</div>
<?php
}

function tst_donation_form($campaign_id = null) {

	global $leyka_current_pm; /** @var $leyka_current_pm Leyka_Payment_Form */

	if(!defined('LEYKA_VERSION')) {
		return;
    }

	if(!$campaign_id) {
		$campaign_id = get_queried_object_id();
    }

	$active_pm = apply_filters('leyka_form_pm_order', leyka_get_pm_list(true));
//	$agree_link = home_url('oferta'); // oferta page

	leyka_pf_submission_errors();?>

<div id="leyka-payment-form" class="leyka-tabs-template" data-template="tabs">
<?php
	$counter = 0;
	foreach($active_pm as $i => $pm) {

		leyka_setup_current_pm($pm);
		$counter++;?>

    <input type="radio" id="<?php echo esc_attr($pm->full_id);?>" class="leyka-payment-option tab" <?php echo $counter == 1 ? 'checked="checked"' : '';?> name="leyka_payment_method">
    <label for="<?php echo esc_attr($pm->full_id);?>" class="leyka-pm-label"><?php echo leyka_pf_get_pm_label();?></label>

    <div class="leyka-tab-area">
        <form class="leyka-pm-form" id="<?php echo leyka_pf_get_form_id();?>" action="<?php echo leyka_pf_get_form_action();?>" method="post">

	        <div class="leyka-pm-fields">

            <!-- amount -->
            <?php if($leyka_current_pm->is_field_supported('amount') ) {
                tst_amount_field($leyka_current_pm);
            }

		    echo leyka_pf_get_hidden_fields($campaign_id);?>

                <input name="leyka_payment_method" value="<?php echo esc_attr($pm->full_id);?>" type="hidden">
                <input name="leyka_ga_payment_method" value="<?php echo esc_attr($pm->label);?>" type="hidden">

	<?php if($leyka_current_pm->is_field_supported('name') ) { ?>
	<div class="tst-textfield leyka-field name">
		<input type="text" class="required tst-textfield__input" name="leyka_donor_name" id="leyka_donor_name" value="" placeholder="Ваше имя" autocomplete="on">
		<span id="leyka_donor_name-error" style="display: none;" class="leyka_donor_name-error field-error tst-textfield__error frm_error"></span>
	</div>
	<?php }?>

	<?php if($leyka_current_pm->is_field_supported('email') ) {?>
	<div class="tst-textfield leyka-field email">
		<input type="text" value="" id="leyka_donor_email" name="leyka_donor_email" class="required email tst-textfield__input" placeholder="Ваш email" autocomplete="on">
		<span style="display: none;" class="leyka_donor_email-error field-error tst-textfield__error frm_error" id="leyka_donor_email-error"></span>
	</div>
	<?php }?>

	<?php if($leyka_current_pm->full_id == 'cp-card') {

        $fields_html = $leyka_current_pm->get_pm_fields();

            $fields_html = str_replace(
                array('input', 'leyka-checkbox-label'),
                array('input class="tst-checkbox__input"', 'tst-checkbox__label'),
                $fields_html
            );?>

            <div class="leyka-field recurring">
                <label class="tst-checkbox checkbox" for="leyka_cp-card_recurring">
                    <?php echo $fields_html;?>
                </label>
            </div>

        <?php } else {

        $fields = str_replace(
            array('rdc-textfield', 'rdc-textfield__input'),
            array('tst-textfield', 'tst-textfield__input'),
            leyka_pf_get_pm_fields()
        );

        if(false !== strpos($fields, 'tst-textfield')) {

            preg_match("#<\s*?label\b[^>]*>(.*?)</label\b[^>]*>#s", $fields, $l);

            if(isset($l[1]) && !empty($l[1])){
                $fields = str_replace('<input', '<input placeholder="'.esc_attr($l[1]).'"', $fields);
            }

        }

        echo $fields;

    }?>

    <div class="agree-submit-wrapper">

    <?php if($leyka_current_pm->is_field_supported('submit') ) {?>

        <div class="leyka-field submit frm_submit">
            <input type="submit" class="tst-submit-button frm_button_submit" id="leyka_donation_submit" name="leyka_donation_submit" value="<?php echo leyka_options()->opt('donation_submit_text');?>">
        </div>

    <?php }

    if($leyka_current_pm->is_field_supported('agree') ) {?>

        <div class="agree-wrapper">
            <input type="hidden" name="leyka_agree" value="1">
            Нажимая кнопку «<?php echo leyka_options()->opt('donation_submit_text');?>», я соглашаюсь с <a class="leyka-custom-confirmation-trigger" href="<?php echo '#';//$agree_link;?>" data-lmodal="#leyka-agree-text">условиями сбора пожертвований</a>.
        </div>

	<?php }?>
    </div>

            </div> <!-- .leyka-pm-fields -->

        </form>
    </div>
    <?php }?>
</div>

<?php leyka_pf_footer();?>


<!-- agreement modal -->
<div id="leyka-agree-text" class="leyka-oferta-text leyka-custom-modal">
	<div class="leyka-modal-close"><?php tst_svg_icon('icon-close');?></div>
	<div class="leyka-oferta-text-frame">
		<div class="leyka-oferta-text-flow">
			<?php echo apply_filters('leyka_terms_of_service_text', leyka_options()->opt('terms_of_service_text'));?>
		</div>
	</div>
</div>
<?php
}


/** Comissions **/
add_action('leyka_admin_menu_setup', function(){
    add_submenu_page('leyka', 'Комиссии за платежи', 'Комиссии', 'leyka_manage_donations', 'leyka_donation_fees', 'kor_donation_fees_settings');
});

/** Donation fees settings page */
function kor_donation_fees_settings() {?>

    <form method="post">
    <?php foreach(leyka()->get_gateways() as $gateway) { /** @var Leyka_Gateway $gateway */

        $pm_list = $gateway->get_payment_methods(true, false);

        if( !$pm_list ) {
            continue;
        }?>

        <h3><?php echo $gateway->title;?></h3>
        <div class="pm-fees">
        <?php foreach($pm_list as $pm) {

            $fee = (float)get_option('leyka_pm_fee_'.$pm->full_id);
            $fee = $fee < 0.0 ? -$fee : $fee;?>

            <div>
                <label for="<?php echo $pm->full_id;?>"><?php echo $pm->title;?>:</label>
                <input type="text" id="<?php echo $pm->full_id;?>" name="leyka_pm_fee_<?php echo $pm->full_id;?>" value="<?php echo $fee == 0 ? '' : $fee;?>" placeholder="От 0.0 до 100.0" size="15" maxlength="5"> %
            </div>

        <?php }?>
        </div>
    <?php }?>
        <input type="submit" name="leyka-pm-fees" value="Отправить">
    </form>
<?php }

add_action('admin_init', function(){

    if(empty($_POST['leyka-pm-fees'])) {
        return;
    }

    foreach($_POST as $name => $value) {

        if(stristr($name, 'leyka_pm_fee_') === false) {
            continue;
        }

        $value = round(rtrim($value, '%'), 2);
        if($value >= 0.0 && $value <= 100.0) {
            update_option($name, $value);
        }
    }
});

add_filter('leyka_admin_donations_columns_names', function($columns_names){

    $columns_names['amount'] = 'Сумма (/ с комиссией)';

    return $columns_names;
});

add_filter('leyka_admin_donation_amount_column_content', function($content, Leyka_Donation $donation){

    $payment_fee = get_option('leyka_pm_fee_'.$donation->pm_full_id);

    if($payment_fee && $payment_fee > 0.0 && $payment_fee < 100.0) {
        $content = '<span class="leyka-donations-admin-column-content amount-original">'.
            $donation->amount.'</span> / <span class="leyka-donations-admin-column-content amount-minus-fee">'.
            round($donation->amount - ($donation->amount*$payment_fee/100.0), 2).
            '</span>&nbsp;'.$donation->currency_label;
    }

    return $content;

}, 10, 2);

add_filter('leyka_donations_list_meta_content', function($donation_meta_content, Leyka_Donation $donation) {

    $payment_fee = get_option('leyka_pm_fee_'.$donation->pm_full_id);

    if($payment_fee && $payment_fee > 0.0 && $payment_fee < 100.0) {

        $donation_meta_content .= '<div class="payment-fee">комиссия: '.number_format(
            round($donation->amount*$payment_fee/100.0, 2),
            2, '.', ' '
        ).' '.$donation->currency_label.'</div>';
    }

    return $donation_meta_content;

}, 10, 2);

add_filter('leyka_donor_phone_field_html', function($donor_phone_field_html, Leyka_Payment_Method $pm){

    return '<div class="rdc-textfield"><input id="leyka_'.$pm->full_id.'_phone" class="required rdc-textfield__input phone-num mixplat-phone" type="text" value="" name="leyka_donor_phone">
<label class="leyka-screen-reader-text rdc-textfield__label" for="leyka_'.$pm->full_id.'_phone">'.__('Your phone number in the 7xxxxxxxxxx format', 'leyka').'</label>
<span id="leyka_'.$pm->full_id.'_phone-error" class="mixplat-phone-error field-error leyka_donor_phone-error rdc-textfield__error"></span>
</div>';
}, 100, 2);