<?php
/**
 * Plugin Name: Tekijänoikeusaloitteen viimeinen keräysvuorokausi
 * Plugin URI: http://jarkea.fi
 * Description: Lisäosalla osallistut Tekijänoikeusaloitteen loppukiriin. Kannatusten viimeinen keräyspäivä on 23.7. ja tällä hetkellä olemme keränneet suurimman osan tarvittavista kannatuksista mutta kaipaamme loppujen keräämisessä apua.
 * Version: 1.8
 * Author: Timi Wahalahti
 * Author URI: http://wahalahti.fi
 */


add_action('admin_init', 'tokiri_opt_init_fn' );
add_action('admin_menu', 'tokiri_opt_add_page_fn');

function tokiri_opt_init_fn(){
	register_setting('tokiri_options', 'tokiri_options', 'tokiri_options_validate' );
	add_settings_section('main_section2', 'Asetukset', 'section2_text_fn', __FILE__);
	add_settings_field('only', 'Koodi on aktiivinen joka päivä:<br><small>Oletuksena koodi on aktiivinen vain 23.7.</small>', 'setting2_string_only', __FILE__, 'main_section2');
	add_settings_field('once', 'Näytä jokaisella sivlatauksella:<br><small>Oletuksena kävijä näkee viestin vain kerran</small>', 'setting2_string_once', __FILE__, 'main_section2');
	add_settings_field('title', 'Otsikko:', 'setting2_string_title', __FILE__, 'main_section2');
	add_settings_field('big_text', 'Iso teksti:', 'setting2_string_big_text', __FILE__, 'main_section2');
	add_settings_field('small_text', 'Pieni teksti:', 'setting2_string_small_text', __FILE__, 'main_section2');
	add_settings_field('count_text', 'Allekirjoitusten määrä<br><small>Teksti ennen allekirjoitsten määrää</small>', 'setting2_string_count_text', __FILE__, 'main_section2');
	add_settings_field('close_text', 'Sulje-teksti', 'setting2_string_close_text', __FILE__, 'main_section2');
	add_settings_field('coder', 'Näytä tekijät:<br><small>Olisi kiva jos olisit kiva ja näyttäisit koodin tekijät</small>', 'setting2_string_coder', __FILE__, 'main_section2');
}

function tokiri_opt_add_page_fn() {
	add_options_page('Tekijänoikeusaloitteen viimeinen keräysvuorokausi', 'Tekijänoikeusaloitteen viimeinen keräysvuorokausi', 'administrator', __FILE__, 'tokiri_opt_page_fn');
}

function  section2_text_fn() {
	echo "<p>Oletuksena koodi on aktiivinen vain viimeisenä keräyspäivänä, tiisttaina 23.7. ja jokainen kävijä näkee viestin vain kerran.</p>";
	echo '<p>Jos haluat, voit muokata näkyvää viestiä ja käyttää HTML-muotoilua.</p>';
	echo "<p>Jos muokkaat tekstejä, tarkista että muutokset toimivat.</p>";
}
function setting2_string_only() {
	$options = get_option('tokiri_options');
	echo "<input type='checkbox' name='tokiri_options[only]'' value='1'" .checked( isset( $options['only'] ), true, false ). "/>";
}
function setting2_string_once() {
	$options = get_option('tokiri_options');
	echo "<input type='checkbox' name='tokiri_options[once]'' value='1'" .checked( isset( $options['once'] ), true, false ). "/>";
}
function setting2_string_title() {
	$options = get_option('tokiri_options');
	echo "<input id='title' name='tokiri_options[title]' size='40' type='text' value='{$options['title']}' />";
}
function setting2_string_big_text() {
	$options = get_option('tokiri_options');
	echo "<input id='big_text' name='tokiri_options[big_text]' size='40' type='text' value='{$options['big_text']}' />";
}
function setting2_string_small_text() {
	$options = get_option('tokiri_options');
	echo "<input id='small_text' name='tokiri_options[small_text]' size='40' type='text' value='{$options['small_text']}' />";
}
function setting2_string_count_text() {
	$options = get_option('tokiri_options');
	echo "<input id='count_text' name='tokiri_options[count_text]' size='40' type='text' value='{$options['count_text']}' />";
}
function setting2_string_close_text() {
	$options = get_option('tokiri_options');
	echo "<input id='close_text' name='tokiri_options[close_text]' size='40' type='text' value='{$options['close_text']}' />";
}
function setting2_string_coder() {
	$options = get_option('tokiri_options');
	echo "<input type='checkbox' name='tokiri_options[coder]'' value='1'" .checked( isset( $options['coder'] ), true, false ). "/>";
}

function tokiri_opt_page_fn() {
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>Tekijänoikeusaloitteen viimeinen keräysvuorokausi</h2>
		<form action="options.php" method="post">
		<?php settings_fields('tokiri_options'); ?>
		<?php do_settings_sections(__FILE__); ?>
		<p class="submit">
			<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Tallenna muutokset'); ?>" />
		</p>
		</form>
	</div>
<?php
}

function tokiri_options_validate($input) {
	$input['title'] =  wp_kses_post($input['title']);
	$input['big_text'] =  wp_kses_post($input['big_text']);
	$input['small_text'] =  wp_kses_post($input['small_text']);
	$input['count_text'] =  wp_kses_post($input['count_text']);
	$input['close_text'] =  wp_kses_post($input['close_text']);	
	return $input;
}

function tokiri_add() {
$options = get_option('tokiri_options');
$jsopt = "";
echo "<script type='text/javascript' src='" .plugins_url( 'tokiri.js' , __FILE__ ). "' charset='UTF-8'></script>";

if (isset($options['only'] )) {
	$jsopt = "onCampaignDayOnly: false, ";
}
if (isset( $options['once'] )) {
	$jsopt .= "showOnlyOnce: false, ";
}
if (isset( $options['coder'] )) {
	$jsopt .= "showCoders: true, ";
}
if (!empty($options['title'])) {
	$jsopt .= "title: '". $options['title']. "', ";
}
if (!empty($options['big_text'])) {
	$jsopt .= "bigText: '". $options['big_text']. "', ";
}
if (!empty($options['small_text'])) {
	$jsopt .= "smallText: '". $options['small_text']. "', ";
}if (!empty($options['count_text'])) {
	$jsopt .= "countText: '". $options['count_text']. "', ";
}
if (!empty($options['close_text'])) {
	$jsopt .= "closeText: '". $options['close_text']. "'";
}

?>
<script>
copyrightCampaign({ <?php echo $jsopt; ?> });
</script>
<?php
}
add_action('wp_head', 'tokiri_add');
?>
