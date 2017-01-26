<?php
/** Sharing **/

/** Sharing on mobile and small screens **/
function tst_social_share(WP_Post $cpost) {

	$title = get_the_title($cpost);
	$link = get_permalink($cpost);
	$text = $title.' '.$link;

	$data = array(
		'whatsapp' => array(
			'url' => 'whatsapp://send?text='.$text,
			'icon' => 'icon-whatsapp',
			'show_desktop' => false
		),
		'viber' => array(
			'url' => 'viber://forward?text='.$text,
			'icon' => 'icon-viber'
		),
		'telegram' => array(
			'url' => 'tg://msg?text='.$text,
			'icon' => 'icon-telegram'
		),
		'facebook' => array(
			'url' => 'https://www.facebook.com/sharer/sharer.php?u='.$link,
			'icon' => 'icon-facebook'
		),
		'twitter' => array(
			'url' => 'https://twitter.com/intent/tweet?url='.$link.'&text='.$title,
			'icon' => 'icon-twitter'
		),
		'vk' => array(
			'url' => 'https://vk.com/share.php?url='.$link.'&title='.$title,
			'icon' => 'icon-vk'
		),
		'ok' => array(
			'url' => 'http://connect.ok.ru/dk?st.cmd=WidgetSharePreview&service=odnoklassniki&st.shareUrl='.$link,
			'icon' => 'icon-ok'
		),
		'mail' => array(
			'url' => "mailto:?subject=Хорошая статья&body=$title $link",
			'icon' => 'icon-mail'
		)
	);


	$css = (tst_is_mobile_user_agent()) ? 'mobile-agent' : 'regular';
?>
	<div class="sharing-up"><a href="#top" class="up-link local-scroll"><?php tst_svg_icon('icon-up');?></a></div>
	<ul class="sharing-list <?php echo esc_attr($css);?>">
	<?php foreach ($data as $id => $item){ ?>
		<li class="<?php echo $id;?>"><a href="<?php echo $item['url'];?>" target="_blank"><?php tst_svg_icon($item['icon']);?></a></li>
	<?php } ?>
	</ul>
<?php
}


function tst_is_mobile_user_agent(){
	//may be need some more sophisticated testing
	$test = false;

	if(!isset($_SERVER['HTTP_USER_AGENT']))
		return $test;

	if(stristr($_SERVER['HTTP_USER_AGENT'],'ipad') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'iphone') || strstr($_SERVER['HTTP_USER_AGENT'],'iphone') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'blackberry') ) {
		$test = true;
	} else if( stristr($_SERVER['HTTP_USER_AGENT'],'android') ) {
		$test = true;
	}

	return $test;
}
