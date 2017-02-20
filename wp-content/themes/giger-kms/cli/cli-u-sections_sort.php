<?php
/**
 * Sort out posts ander new sections
 *
 **/
set_time_limit (0);
ini_set('memory_limit','256M');

try {
	$time_start = microtime(true);
	include('cli_common.php');
	include( get_template_directory() . '/inc/class-import.php' );
	echo 'Memory before anything: '.memory_get_usage(true).chr(10).chr(10);

	global $wpdb;

	//upload folder
	$uploads = wp_upload_dir();

	//sections
	$sections_raw = get_terms(array('taxonomy' => 'section', 'hide_empty' => 0));
	$sections = array();
	if(!empty($sections_raw)) { foreach($sections_raw as $s) {
		$sections[$s->slug] = $s;
	}}

	//landings
	$move_to_items = array();

	$move_to_items[] = array(
		'ID' =>  false,
		'slug' => 'dront-events',
		'post_title' => 'Мероприятия',
		'post_content' => 'Профильные конференции и мероприятия', //add some text
		'section' => 'work',
		'parent' => 0,
		//'meta_input' => array('has_sidebar' => 'on', 'icon_id' => 'date_range'),
		'thumb' => 'events.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-law',
		'post_title' => 'Юридическая защита',
		'post_content' => 'Общественная приемная, ссылки на доп.сайты, задать вопрос',
		'section' => 'work',
		'parent' => 0,
		//'thumb' => 'landing-law.jpg',
		'tags' => 'защита прав,правовой центр,консультации',
		'docs' => array(
			'http://dront.ru/files/publications/14.07.14 eco_prava.pdf',
			'http://dront.ru/files/publications/kuda-obratitsya-esli.zip',
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-education',
		'post_title' => 'Экопросвещение',
		'post_content' => 'Эколагеря, творчество, публикации, пособия',
		'section' => 'work',
		'parent' => 0,
		'thumb' => 'education.jpg',
		'tags' => 'экопросвещение,эколагерь,центр Оберег,образование,школникам,студентам',
		'docs' => array(
			'http://dront.ru/files/publications/2015/Issledovatelskaya-deyatelnost.pdf',
			'http://dront.ru/files/publications/2015/Vospominanya.doc',
			'http://dront.ru/files/publications/2015/Issledovatelskaya-deyatelnost.pdf',
			'http://dront.ru/files/publications/2015/Kak zashitit derevo.pdf',
			'http://dront.ru/files/publications/2014/Ekologicheskiye-skazki.pdf',
			'http://dront.ru/files/publications/2014/Kak-vyliechit-derevo.pdf',
			'http://dront.ru/files/publications/13.12.28 Ornitologicheskaya igroteka.pdf',
			'http://dront.ru/files/publications/Detskiy Sad Zelenyy sad.jpg',
			'http://dront.ru/files/publications/ornitologicheskaya-azbuka.pdf',
			'http://dront.ru/files/publications/risunki_k_azbuke.zip',
			'http://dront.ru/files/publications/kak-posadit-derevo.zip',
			'http://dront.ru/files/publications/domiki-dlya-ptits.zip',
			'http://dront.ru/files/publications/2011-raskraska.pdf',
			'http://dront.ru/files/publications/eco-igroteka.pdf',
			'http://dront.ru/files/publications/ecological-education.pdf',
			'http://dront.ru/files/publications/ecological-tales.pdf',
			'http://dront.ru/files/publications/NK_2002_1.pdf',

		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-researches',
		'post_title' => 'Исследования',
		'post_content' => 'Публикации, статистика',
		'section' => 'work',
		'parent' => 0,
		'tags' => 'наука,исследования,статистика,научная работа',
		'thumb' => 'researches.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/2016/Flora_NN_X.doc',
			'http://dront.ru/files/publications/2016/Katalog gerbaria Botsada-3.doc',
			'http://dront.ru/files/publications/2016/Stepi.pdf',
			'http://dront.ru/files/publications/2015/Katalog gerbaria Botsada.doc',
			'http://dront.ru/files/publications/po_stranitsam_ecologicheskogo_kalendarya.pdf',
			'http://dront.ru/files/publications/pokormite_ptits.zip',
			'http://dront.ru/files/publications/Flora_NN_2012.doc',
			'http://dront.ru/files/publications/2011-raznoobrazie.zip',
			'http://dront.ru/files/publications/Cherepaha_Abhaziya_2009_1-2_41-51.pdfhttp://dront.ru/files/publications/ecological-education.pdf',

		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-activist',
		'post_title' => 'Активизм',
		'post_content' => 'Кейсы, действующие и прошлые акции',
		'section' => 'work',
		'parent' => 0,
		'tags' => 'акции,конкурсы,мониторинг,экопроблемы,контроль,активизм',
		'thumb' => 'activist.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/springalive.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-publications',
		'post_title' => 'Публикации',
		'post_content' => 'Публикации из раздела "публикации", Газета Берегиня, публикации фотостудии',
		'section' => 'work',
		'parent' => 0,
		'tags' => 'Берегиня,газета,публикации,фотоработы,фотовыставка,фото природы',
		'thumb' => 'reptiles3.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/chto-takoe-prirodoohrannoe-dvizhenie.zip',
			'http://dront.ru/files/publications/mir-mezhdu-dvuh-ekologiy.rar',
			'http://dront.ru/files/publications/Factor_Four.doc',
			'http://dront.ru/files/publications/1915.pdf',
			'http://dront.ru/files/publications/2016/Stepi.pdf',
			'http://dront.ru/files/publications/2015/Vospominanya.doc',
			'http://dront.ru/files/publications/2015/Flora_NN_IX.doc',
			'http://dront.ru/files/publications/Methodical-recomendations.pdf',
			'http://dront.ru/files/publications/13.12.28 Trava u doma.pdf',
			'http://dront.ru/files/publications/13.12.28 Buklet Ozenka vliyaniya.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-ecomap',
		'post_title' => 'Экокарта',
		'post_content' => 'Карта с маркерами экологических проблем, сервис "сообщить о проблеме"',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'ecomap.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-cheboksarges',
		'post_title' => 'Чебоксарская ГЭС',
		'post_content' => 'cheboksarges.txt',
		'section' => 'ecoproblems',
		'parent' => 0,
		'tags' => 'Чебоксарская ГЭС',
		'thumb' => 'cheboksarges.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/Apple.pdf',
			'http://dront.ru/files/publications/cheboksarskaya-ges.zip',
			'http://dront.ru/files/news/2013-09-23/zakluchenie-chebges.doc',
			'http://dront.ru/files/cheboksarskaya/Gosexpertiza_1.rar',
			'http://dront.ru/files/cheboksarskaya/Gosexpertiza_2.rar',
			'http://dront.ru/files/cheboksarskaya/v_orehov_2.pdf',
			'http://dront.ru/files/cheboksarskaya/Prichiny_usyhaniya.doc',
			'http://dront.ru/files/cheboksarskaya/orehov.doc',
			'http://dront.ru/files/cheboksarskaya/romanychev.doc',
			'http://dront.ru/files/cheboksarskaya/monich.doc',
			'http://dront.ru/files/cheboksarskaya/lipshits14-06.doc',
			'http://dront.ru/files/cheboksarskaya/churazov14-06.doc',
			'http://dront.ru/files/cheboksarskaya/polyakova14-06.doc',
			'http://dront.ru/files/cheboksarskaya/ivanov-ovos-nn.doc',
			'http://dront.ru/files/cheboksarskaya/balakhna-soldatov.doc',
			'http://dront.ru/files/cheboksarskaya/balakhna-chikovitova.doc',
			'http://dront.ru/files/cheboksarskaya/balakhna-orehov.doc',
			'http://dront.ru/files/cheboksarskaya/rasskazova.doc',
			'http://dront.ru/files/cheboksarskaya/ovos-na-avos.doc',
			'http://dront.ru/files/cheboksarskaya/doklad-postnova.doc',
			'http://dront.ru/files/cheboksarskaya/medvedeva-26-09-2012.ppt',
			'http://dront.ru/files/cheboksarskaya/as_shein.doc',
			'http://dront.ru/files/cheboksarskaya/v_orehov.doc',
			'http://dront.ru/files/cheboksarskaya/d_levashov.ppt',
			'http://dront.ru/files/cheboksarskaya/obrashenie_maslova.zip',
			'http://dront.ru/files/cheboksarskaya/vpechatleniya_firsovoi.doc',
			'http://dront.ru/files/cheboksarskaya/presentation_v_orehov.ppt',
			'http://dront.ru/files/cheboksarskaya/ivanov_kachestvo_vod.doc',
			'http://dront.ru/files/cheboksarskaya/ivanov_sotsialnie_posledstviya.doc',
			'http://dront.ru/files/cheboksarskaya/l_polyakova.doc',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part1.rar',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part2.rar',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part3.rar',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part4.rar',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part5.rar',
			'http://dront.ru/files/cheboksarskaya/questions/1-217-part6.rar',
			'http://dront.ru/files/cheboksarskaya/questions/ovs-dzerzhinsk.doc',
			'http://dront.ru/files/cheboksarskaya/questions/ovos-dzerzhinsk-1-121.doc',
			'http://dront.ru/files/cheboksarskaya/questions/ovos-dzerzhinsk-122-145.doc',
			'http://dront.ru/files/cheboksarskaya/questions/voprosy_po_ovos_churazov.doc',
			'http://dront.ru/files/cheboksarskaya/censor/2013-07-01.doc',
			'http://dront.ru/files/cheboksarskaya/censor/11.03.2013.doc',
			'http://dront.ru/files/cheboksarskaya/censor/04-12-12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/01.12.12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/30-11-12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/28-11-12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/19-11-12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/28-06-12.doc',
			'http://dront.ru/files/cheboksarskaya/censor/23-06-12.doc',
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-nizhaes',
		'post_title' => 'Нижегородская АЭС',
		'post_content' => 'Кейс угрозы Нижегородской АЭС',
		'section' => 'ecoproblems',
		'parent' => 0,
		'tags' => 'Нижегородская АЭС',
		'thumb' => 'nizhaes.jpg'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-rares',
		'post_title' => 'Охрана редких видов',
		'post_content' => 'Красная книга, публикации',
		'section' => 'ecoproblems',
		'parent' => 0,
		'tags' => 'общество охраны амфибий и рептилий,охрана редких видов,разнообразие видов,исчезающие виды,красная книга',
		'thumb' => 'rares.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/2015/Ch-kn-IV.doc',
			'http://dront.ru/files/publications/2015/Katalog gerbaria Botsada.doc',
			'http://dront.ru/files/publications/2014/Redkie vidy-4.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-1.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-2.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-3.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-4.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-5.pdf',
			'http://dront.ru/files/publications/2015/Albom-stikerov.pdf',
			'http://dront.ru/files/publications/2014.02.11-Black_Book-NN-III.doc',
			'http://dront.ru/files/publications/2011-raznoobrazie.zip',
			'http://dront.ru/files/publications/2011-redkie-vidy.rar',
			'http://dront.ru/files/publications/redbook2010.pdf',
			'http://dront.ru/files/publications/birds-monitoring-3.pdf',
			'http://dront.ru/files/publications/rare-2008.pdf',
			'http://dront.ru/files/publications/NK_2008_3.pdf',
			'http://dront.ru/files/publications/international-conference.pdf',
			'http://dront.ru/files/publications/2011-raznoobrazie.zip',
			'http://dront.ru/files/publications/NK_2008_4.pdf',
			'http://dront.ru/files/publications/info-analitic-materials-2008.pdf',
			'http://dront.ru/files/publications/2016/bolota.pdf',
			'http://dront.ru/files/publications/NK_2004_1.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-oopt',
		'post_title' => 'ООПТ',
		'post_content' => 'Информация о территориях, что мы делаем, документы',
		'section' => 'ecoproblems',
		'parent' => 0,
		'tags' => 'оопт,заповедники,охраняемые территории',
		'thumb' => 'oopt.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/2015/Pticy.pdf',
			'http://dront.ru/files/publications/2014/Zmei zapodednika.pdf',
			'http://dront.ru/files/publications/2014/KOTR-NO.pdf',
			'http://dront.ru/files/publications/Buklet_OOPT_2011_Forest.rar',
			'http://dront.ru/files/publications/oopt.rar',
			'http://dront.ru/files/publications/NK_2005_3.pdf',
			'http://dront.ru/files/publications/NK_2004_1.pdf',
			'http://dront.ru/files/publications/NK_2004_3.pdf',
			'http://dront.ru/files/publications/NK_2004_5.pdf',
			'http://dront.ru/files/publications/2016/Stepi.pdf',
			'http://dront.ru/files/publications/Buklet_OOPT_2011_Forest.rar',
			'http://dront.ru/files/publications/info-analitic-materials-2008.pdf',
			'http://dront.ru/files/publications/international-conference.pdf',
			'http://dront.ru/files/publications/NK_2005_3.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-birds',
		'post_title' => 'Птицы',
		'post_content' => 'Выдержки из проектов, фото, акции, публикации',
		'section' => 'ecoproblems',
		'parent' => 0,
		'tags' => 'СОПР,орнитологическая лаборатория,птицы,охрана птиц,орнитология',
		'thumb' => 'birds.jpg',
		'docs' => array(
			'http://dront.ru/files/publications/zhitkov_buturlin.pdf',
			'http://dront.ru/files/publications/serebrovsky-1918.pdf',
			'http://dront.ru/files/publications/2015/Udod-1str.jpg',
			'http://dront.ru/files/publications/2015/Udod-2str.jpg',
			'http://dront.ru/files/publications/2015/Pticy.pdf',
			'http://dront.ru/files/publications/2014/Red-book-NO/Krasnay kniga-2014-2.pdf',
			'http://dront.ru/files/publications/2015/Vesna-Ptitsy-buklet-2014.pdf',
			'http://dront.ru/files/publications/2014/Gorihvostka.zip',
			'http://dront.ru/files/publications/2015/Albom-stikerov.pdf',
			'http://dront.ru/files/publications/2014/KOTR-NO.pdf',
			'http://dront.ru/files/publications/2014/Vesna-vremia-razmnozheniya-ptits.pdf',
			'http://dront.ru/files/publications/13.12.31 Guseobraznyye ptitsy.pdf',
			'http://dront.ru/files/publications/13.12.30 Hischnyye Ptitsy.pdf',
			'http://dront.ru/files/publications/13.12.28 Ornitologicheskaya igroteka.pdf',
			'http://dront.ru/files/publications/Buklet Strizh ptitsa 2014.jpg',
			'http://dront.ru/files/publications/orlan.zip',
			'http://dront.ru/files/publications/pokormite_ptits.zip',
			'http://dront.ru/files/publications/dni-nabludeniy-ptits.zip',
			'http://dront.ru/files/publications/varakushka.zip',
			'http://dront.ru/files/publications/domiki-dlya-ptits.zip',
			'http://dront.ru/files/publications/Matsyna.pdf',
			'http://dront.ru/files/publications/buklet-2011.rar',
			'http://dront.ru/files/publications/pernatye-hishniki-21-2011.pdf',
			'http://dront.ru/files/publications/pernatye-hishniki-21-2011-vl.pdf',
			'http://dront.ru/files/publications/lep-killers.pdf',
			'http://dront.ru/files/publications/plakat-2011.jpg',
			'http://dront.ru/files/publications/tryasoguzka-ptitsa-goda.zip',
			'http://dront.ru/files/publications/belaya_tryasoguska.rar',
			'http://dront.ru/files/publications/chibis2010.pdf',
			'http://dront.ru/files/publications/build_bird_houses_2010.pdf',
			'http://dront.ru/files/publications/prolet_birds2010.pdf',
			'http://dront.ru/files/publications/people_and_birds_2.pdf',
			'http://dront.ru/files/publications/people_and_birds.pdf',
			'http://dront.ru/files/publications/birds-monitoring-3.pdf',
			'http://dront.ru/files/publications/NK_2008_3.pdf',
			'http://dront.ru/files/publications/NK_2008_4.pdf',
			'http://dront.ru/files/publications/2015/Ornitofauna.doc',
			'http://dront.ru/files/publications/international-conference.pdf',
			'http://dront.ru/files/publications/NK_2006_1.pdf',
			'http://dront.ru/files/publications/NK_2005_2.pdf',
			'http://dront.ru/files/publications/nest-monitoring.pdf',
			'http://dront.ru/files/publications/NK_2005_1.pdf',
			'http://dront.ru/files/publications/NK_2004_2.pdf',
			'http://dront.ru/files/publications/NK_2004_3.pdf',
			'http://dront.ru/files/publications/NK_2004_5.pdf',
			'http://dront.ru/files/publications/electrocution-russisch.pdf',
			'http://dront.ru/files/publications/NK_2003_1.pdf',
			'http://dront.ru/files/publications/NK_2003_2.pdf',
			'http://dront.ru/files/publications/NK_2003_4.pdf',
			'http://dront.ru/files/publications/NK_2003_9.pdf',
			'http://dront.ru/files/publications/NK_2003_15.pdf',
			'http://dront.ru/files/publications/NK_2003_16.pdf',
			'http://dront.ru/files/publications/NK_2003_17.pdf',
			'http://dront.ru/files/publications/pustelga_2002.pdf',
			'http://dront.ru/files/publications/NK_2002_2.pdf',
			'http://dront.ru/files/publications/ecosled.rar',
			'http://dront.ru/files/publications/2015/Ornitofauna.doc'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-urban',
		'post_title' => 'Город',
		'post_content' => 'Ссылка на лонгрид по зеленым территориям, публикации и высказываиня',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'urban.jpg',
		'tags' => 'город,городское озеленение,экология города',
		'docs' => array(
			'http://dront.ru/files/publications/spasaem-ot-svalok.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-locals',
		'post_title' => 'Проблемные территории',
		'post_content' => 'Информация о территориях, что мы делаем, документы',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'locals.jpg',
		'tags' => 'проблемные территории,опасность,экологическая катастрофа,акции',
		'docs' => array(
			'http://dront.ru/files/publications/Methodical-recomendations.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-health',
		'post_title' => 'Здоровье',
		'post_content' => 'О влиянии экологии на здоровье',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'health.jpg',
		'tags' => 'здоровье',
		'docs' => array(
			'http://dront.ru/files/publications/2016/Rastenia-detyam.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-resources',
		'post_title' => 'Ресурсы',
		'post_content' => 'Ресурсы, энергосбережение, климат',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'resources.jpg',
		'tags' => 'сбережение ресурсов,экономия энергии,климат,акции',
		'docs' => array(
			'http://dront.ru/files/publications/zakladki_energosberezhenie.zip'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-garbage',
		'post_title' => 'Отходы',
		'post_content' => 'Разделльный сбор, проблема свалок, свалки и пункты на карте',
		'section' => 'ecoproblems',
		'parent' => 0,
		'thumb' => 'garbage.jpg',
		'tags' => 'отходы,мусор,свалки,раздельный сбор',
		'docs' => array(
			'http://dront.ru/files/publications/spasaem-ot-svalok.pdf',
			'http://dront.ru/files/publications/razvitie-sistemy-obrasheniya-s-othodami.doc'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-bereginya',
		'post_title' => 'Ежемесячная газета "Берегиня"',
		'post_content' => 'bereginya.txt',
		'section' => 'departments',
		'parent' => 0,
		//'thumb' => 'garbage.jpg',
		'tags' => 'Берегиня,газета,публикации,редакция'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-sbereg-center',
		'post_title' => 'Центр природосберегающих технологий',
		'post_content' => 'centerpt.txt',
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'sbereg-center2.jpg',
		'tags' => 'Центр природосберегающих технологий,активизм,сбережение ресурсов,экономия,акции',
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-sopr',
		'post_title' => 'Нижегородское отделение Союза охраны птиц России',
		'post_content' => 'sopr.txt',
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'sopr.jpg',
		'tags' => 'СОПР,птицы,птицы,охрана птиц,орнитология',
		'docs' => array(
			'http://dront.ru/files/publications/2015/Udod-1str.jpg',
			'http://dront.ru/files/publications/2015/Udod-2str.jpg',
			'http://dront.ru/files/publications/2014/Gorihvostka.zip',
			'http://dront.ru/files/publications/Buklet Strizh ptitsa 2014.jpg',
			'http://dront.ru/files/publications/orlan.zip',
			'http://dront.ru/files/publications/dni-nabludeniy-ptits.zip',
			'http://dront.ru/files/publications/varakushka.zip',
			'http://dront.ru/files/publications/domiki-dlya-ptits.zip',
			'http://dront.ru/files/publications/buklet-2011.rar',
			'http://dront.ru/files/publications/tryasoguzka-ptitsa-goda.zip',
			'http://dront.ru/files/publications/chibis2010.pdf',
			'http://dront.ru/files/publications/build_bird_houses_2010.pdf',
			'http://dront.ru/files/publications/prolet_birds2010.pdf',
			'http://dront.ru/files/publications/people_and_birds_2.pdf',
			'http://dront.ru/files/publications/people_and_birds.pdf',
			'http://dront.ru/files/publications/NK_2006_1.pdf',
			'http://dront.ru/files/publications/owls-2005.pdf',
			'http://dront.ru/files/publications/NK_2005_2.pdf',
			'http://dront.ru/files/publications/NK_2004_2.pdf',
			'http://dront.ru/files/publications/birds-recomendations.pdf',
			'http://dront.ru/files/publications/NK_2003_15.pdf',
			'http://dront.ru/files/publications/pustelga_2002.pdf',
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-ornotologlab',
		'post_title' => 'Орнитологическая лаборатория',
		'post_content' => 'Лаборатория реализует проекты связанные с изучением и защитой птиц в Нижегородской области.', //add some text
		'section' => 'departments',
		'parent' => 0,
		'tags' => 'орнитологическая лаборатория,птицы,охрана птиц,орнитология',
		'thumb' => 'ornotologlab.jpg',
		'docs' => array(
			'http://dront.ru/files/orni-lab/publications/raptors_conservation_2005.pdf',
			'http://dront.ru/files/orni-lab/publications/raptors_conservation_2008_1.pdf',
			'http://dront.ru/files/orni-lab/publications/raptors_conservation_2008_2.pdf',
			'http://dront.ru/files/orni-lab/publications/raptors_conservation_2009.pdf',
			'http://dront.ru/files/orni-lab/publications/raptors_conservation_2010.pdf',
			'http://dront.ru/files/orni-lab/publications/LEP-2008.pdf',
			'http://dront.ru/files/orni-lab/publications/LEP-2009.pdf',
			'http://dront.ru/files/orni-lab/ornitofauna-ilovyh-polei.pdf',
			'http://dront.ru/files/orni-lab/Ptizy-i-LEP/4. Results-negative.pdf',
			'http://dront.ru/files/orni-lab/Ptizy-i-LEP/4.1. Nizhegorodskaya-obl.pdf',
			'http://dront.ru/files/orni-lab/Ptizy-i-LEP/2015-04-23/Krasnodarsky-kray.pdf',
			'http://dront.ru/files/orni-lab/Ptizy-i-LEP/2015-04-23/Republic-of-Kalmykiya.pdf',
			'http://dront.ru/files/orni-lab/Migrations/Results-2014.pdf',
			'http://dront.ru/files/orni-lab/Ptizy-i-LEP/2015-V-zaschitu-krylatyh.pdf',
			'http://dront.ru/files/publications/2015/Pticy.pdf',
			'http://dront.ru/files/publications/Matsyna.pdf',
			'http://dront.ru/files/publications/pernatye-hishniki-21-2011.pdf',
			'http://dront.ru/files/publications/pernatye-hishniki-21-2011-vl.pdf',
			'http://dront.ru/files/publications/lep-killers.pdf',
			'http://dront.ru/files/publications/plakat-2011.jpg',
			'http://dront.ru/files/publications/electrocution-russisch.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-lawcenter',
		'post_title' => 'Нижегородский эколого-правовой центр',
		'post_content' => 'lawcenter.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'lawcenter.jpg',
		'tags' => 'защита прав,правовой центр,консультации'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-striks',
		'post_title' => 'Информационно-консультационный центр "Стрикс"',
		'post_content' => 'striks.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'striks.jpg',
		'tags' => 'информационный центр,центр Стрикс,консультации,образование,школьникам',
		'docs' => array(
			'http://dront.ru/files/strix/Annotations.doc'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-reptiles',
		'post_title' => 'Нижегородское общество охраны амфибий и рептилий',
		'post_content' => 'reptiles.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'reptiles.jpg',
		'tags' => 'общество охраны амфибий и рептилий,охрана редких видов,разнообразие видов,исчезающие виды,красная книга',
		'docs' => array(
			'http://dront.ru/files/publications/Pestov_Cherepahi.pdf',
			'http://dront.ru/files/publications/Cherepaha_Abhaziya_2009_1-2_41-51.pdf',
			'http://dront.ru/files/nooar/CurStudHerp_2012_3-4_158-159.pdf',
			'http://dront.ru/files/nooar/CurStudHerp_2012_3-4_155-157.pdf',
			'http://dront.ru/files/nooar/varan.pdf',
			'http://dront.ru/files/nooar/Turtles.pdf',
			'http://dront.ru/files/nooar/Т.graeca_2012.pdf',
			'http://dront.ru/files/nooar/aspects-2011 197-201.pdf',
			'http://dront.ru/files/nooar/RU12_TURTLES_01_11.pdf',
			'http://dront.ru/files/nooar/aspects-2011%20197-201.pdf',
			'http://dront.ru/files/nooar/RU12_TURTLES_01_11.pdf',
			'http://dront.ru/files/nooar/RC24_205-207_ShortReports_Pestov_Nurmuhambetov.pdf',
			'http://dront.ru/files/nooar/RC24_104-117_Proceedings_Pestov_etal.pdf',
			'http://dront.ru/files/nooar/RC24_98-103_Proceedings_Pestov_Sadykulin.pdf',
			'http://dront.ru/files/nooar/Pestov4.pdf',
			'http://dront.ru/files/nooar/NG_RU_NEXT_VARAN.pdf',
			'http://dront.ru/files/publications/pernatye-hishniki-21-2011.pdf',
			'http://dront.ru/files/publications/lep-killers.pdf',
			'http://dront.ru/files/nooar/Sarayev_Pestov.pdf',
			'http://dront.ru/files/nooar/Ostrovskih_et_al_2010.pdf',
			'http://dront.ru/files/nooar/Pestov_Cherepahi1.pdf',
			'http://dront.ru/files/nooar/national-geographic-04-2010.pdf',
			'http://dront.ru/files/nooar/national-geographic-07-2009.pdf',
			'http://dront.ru/files/nooar/cherepaha-2009.pdf',
			'http://dront.ru/files/nooar/national-geographic-09-2008.rar',
			'http://dront.ru/files/nooar/national-geographic-06-2008.pdf',
			'http://dront.ru/files/nooar/national-geographic-05-2008.pdf',
			'http://dront.ru/files/nooar/national-geographic-03-2008.pdf',
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-jungle',
		'post_title' => 'Научно-просветительская организация "Джунгли"',
		'post_content' => 'jungle.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'jungle.jpg',
		'tags' => 'образовательный центр,центр Джунгли,консультации,образование,школьникам,студентам'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-folks',
		'post_title' => 'Нижегородский фольклорный клуб',
		'post_content' => 'folks.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'folks.jpg',
		'tags' => 'фольклорный клуб,культура,исследования,образование'
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-obereg',
		'post_title' => 'Эколого-просветительский центр "Оберег"',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'obereg2.jpg',
		'tags' => 'образовательный центр,центр Оберег,конкурсы,образование,экодом',
		'docs' => array(
			'http://dront.ru/files/obereg/eco-gorod-eco-dom-books.rar',
			'http://dront.ru/files/competition/ecogorod-ecodom/2015/Polozheniye-Ekogorod.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2015/Pustynskaya-biostantsiya.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2013/Polozheniye_Ekogorod_2013-14.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2013/Metodich_posobiye_uchastnikam.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2013/Polozheniye_Ekogorod_2013-14.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2013/Metodich_posobiye_uchastnikam.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/2013/itogi.doc',
			'http://dront.ru/files/competition/ecogorod-ecodom/polozhenie-2013.doc',
			'http://dront.ru/files/obereg/lesnaya-olimpiada-2004.doc',
			'http://dront.ru/files/obereg/lesnaya-olimpiada-2005.doc',
			'http://dront.ru/files/obereg/lesnaya-olimpiada-2006.doc',
			'http://dront.ru/files/obereg/lesnaya-olimpiada-2007.doc'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-zavojzhje',
		'post_title' => 'Фонд "Нижегородское Заволжье"',
		'post_content' => 'Содействие устойчивому развитию международной биосферной территории «Нижегородское Заволжье», центром которой является государственный природный биосферный заповедник "Керженский"', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'zavojzhje2.jpg',
		'tags' => 'Нижегородское Заволжье,оопт,заповедники,охраняемые территории,Керженский заповедник',
		'docs' => array(
			'http://dront.ru/images/zavolzhye/14.01.17%20Zavolzhye_Kerzhenskiy.rar',
			'http://dront.ru/files/publications/2015/Pticy.pdf',
			'http://dront.ru/files/publications/2014/Zmei zapodednika.pdf',
			'http://dront.ru/files/publications/NK_2001_8.pdf'
		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-realworld',
		'post_title' => 'Фотовидеостудия "Реальный мир"',
		'post_content' => 'realworld.txt', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'realworld.jpg',
		'tags' => 'фотостудия,студия Реальный мир,фото,фото природы,фотовыставки,фотоконкурсы',
		'docs' => array(
			'http://dront.ru/files/nooar/RU12_TURTLES_01_11.pdf',
			'http://dront.ru/files/nooar/NG_RU_NEXT_VARAN.pdf',
			'http://dront.ru/files/nooar/Pestov_Cherepahi1.pdf',
			'http://dront.ru/files/nooar/national-geographic-04-2010.pdf',
			'http://dront.ru/files/nooar/national-geographic-07-2009.pdf',
			'http://dront.ru/files/nooar/national-geographic-09-2008.rar',
			'http://dront.ru/files/nooar/national-geographic-06-2008.pdf',
			'http://dront.ru/files/nooar/national-geographic-05-2008.pdf',

		)
	);

	$move_to_items[] = array(
		'ID' => false,
		'slug' => 'dront-rivercleaners',
		'post_title' => 'Движение "Чистильщики рек"',
		'post_content' => 'Текст, отчеты, фото', //add some text
		'section' => 'departments',
		'parent' => 0,
		'thumb' => 'rivercleaners.jpg',
		'tags' => 'Чистильщики рек,реки,охрана рек'
	);

	$count = 0;
	foreach($move_to_items as $i_obj) {

		$page_data = array();

		//thumbnail id
		$thumb_id = false;
		if(isset($i_obj['thumb'])){
			$path = WP_CONTENT_DIR.'/themes/giger-kms/cli/sideload/'.$i_obj['thumb'];
			var_dump($path);

			$test_path = $uploads['path'].'/'.$i_obj['thumb'];
			if(!file_exists($test_path)) {
				$thumb_id = tst_upload_img_from_path($path);
				echo 'Uploaded thumbnail '.$thumb_id.chr(10);
			}
			else {
				$a_url = $uploads['url'].'/'.$i_obj['thumb'];
				$thumb_id = attachment_url_to_postid($a_url);
				if(!$thumb_id) {
					$thumb_id = tst_register_uploaded_file($test_path);
				}
			}
		}

		$old_page = get_posts(array(
			'post_type' => 'landing',
			'post_status' => 'publish',
			'posts_per_page' => 1,
			'name' => $i_obj['slug'],
			'cache_results' => false,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
			'no_found_rows' => false,
			'suppress_filters' => true,
			'fields' => 'ids',
		));

		$page_data['ID'] = ($old_page) ? (int)$old_page[0] : 0;
		$page_data['post_type'] = 'landing';
		$page_data['post_status'] = 'publish';
		$page_data['post_title'] = ($i_obj['post_title']) ? $i_obj['post_title'] : $old_page->post_title;
		$page_data['post_parent'] = 0;

		//post name
		if(!empty($i_obj['slug'])){
			$page_data['post_name'] = $i_obj['slug'];
		}

		//content
		if(empty($i_obj['post_content']) && $old_page) {
			$page_data['post_content'] = $old_page->post_content;

		}
		elseif(false !== strpos($i_obj['post_content'], '.txt')) {
			echo 'Get content from file: '.$i_obj['post_content'].chr(10);

			$content = file_get_contents($i_obj['post_content']);
			if($content){

				//correct urls
				//$home = home_url('/');
				//$content_test = str_replace('http://asi_dev.dev/', $home, $content_test);
				$page_data['post_content'] = $content;
			}

			unset($content);
		}
		elseif(false !== strpos($i_obj['post_content'], 'import | ')) {
			echo "Import fromt URL ".$i_obj['post_content'].chr(10);

			$old_url = str_replace('import | ', '', $i_obj['post_content']);
			$old_post = TST_Import::get_instance()->get_post_by_old_url($old_url);

			if($old_post) {
				$page_data['post_content'] = $old_post->post_content;
			}
		}
		else {
			$page_data['post_content'] = $i_obj['post_content'];
		}

		//thumbnail
		if(isset($i_obj['meta_input'])){
			$page_data['meta_input'] = $i_obj['meta_input'];
		}

		if($thumb_id)
			$page_data['meta_input']['_thumbnail_id'] = (int)$thumb_id;

		if(isset($i_obj['menu_order'])) {
			$page_data['menu_order'] = (int)$i_obj['menu_order'];
		}

		$uid = wp_insert_post($page_data);

		$key = $i_obj['section'];

		if($uid && isset($sections[$key])) {
			wp_set_object_terms((int)$uid, $sections[$key]->term_id, 'section');
			wp_cache_flush();
		}

		if($uid && isset($i_obj['tags'])) {
			wp_set_post_terms((int)$uid, $i_obj['tags'], 'post_tag', false);
			wp_cache_flush();
		}

		//documents
		if(isset($i_obj['docs']) && !empty($i_obj['docs'])) {
			$doc = array_map('trim', $i_obj['docs']);
			if($doc) {
				$d_count = 0;
				foreach($doc as $d) {
					$d = str_replace('^', ',', $d); //yes, we have commas in URLs
					$d_doc = TST_Import::get_instance()->get_attachment_by_old_url($d);
					if($d_doc) {

						$c = p2p_type('connected_attachments')->connect((int)$uid, (int)$d_doc->ID, array('date' => current_time('mysql')));
						if(!is_wp_error($c)){
							echo 'Connection '.$c.' for '.$d.chr(10);
							$d_count++;
						}
					}
				}

				echo 'Added '.$d_count.' document for '.$page_data['post_title'].chr(10);
			}
		}
		elseif($i_obj['slug'] == 'dront-bereginya') {
			$doc = get_posts(array(
				'post_type' => 'attachment',
				'post_status' => 'inherit',
				'posts_per_page' => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'attachment_tag',
						'field' => 'slug',
						'terms' => 'bereginya'
					)
				)
			));

			echo 'Bereginya files: '.count($doc).chr(10);
			$d_count = 0;
			if($doc) { foreach($doc as $d_doc) {
				$c = p2p_type('connected_attachments')->connect((int)$uid, (int)$d_doc->ID, array('date' => current_time('mysql')));
				if(!is_wp_error($c)){
					$d_count++;
				}
			}}

			echo 'Added '.$d_count.' document for '.$page_data['post_title'].chr(10);
		}



		echo "Update landing ".$i_obj['slug'].' new ID '.$uid.chr(10);


		$count++;
	}

	echo 'Updated pages: '.$count.'. Time in sec: '.(microtime(true) - $time_start).chr(10);
	//echo 'Memory '.memory_get_usage(true).chr(10).chr(10);


	//Final
	echo 'Memory '.memory_get_usage(true).chr(10);
	echo 'Total execution time in seconds: ' . (microtime(true) - $time_start).chr(10).chr(10);
}
catch (TstNotCLIRunException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (TstCLIHostNotSetException $ex) {
	echo $ex->getMessage() . "\n";
}
catch (Exception $ex) {
	echo $ex;
}