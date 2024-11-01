<?php
/*
Plugin Name: Today-Yesterday Dates
Plugin URI: https://wordpress.org/plugins/today-yesterday-dates/
Description: This plugin changes the creation dates of posts to relative dates for posts that are dated today or yesterday (relative to the current time, i.e. <strong>Today at 11:45</strong> or <strong>Yesterday at 9:15</strong>).
Version: 1.01
Author: Flector
Author URI: https://profiles.wordpress.org/flector#content-plugins
Text Domain: today-yesterday-dates
*/ 

//проверка версии плагина (запуск функции установки новых опций) begin
function tyd_check_version() {
    $tyd_options = get_option('tyd_options');
    if (!isset($tyd_options['version'])) {$tyd_options['version']='';update_option('tyd_options',$tyd_options);}
    if ( $tyd_options['version'] != '1.01' ) {
        tyd_set_new_options();
    }    
}
add_action('plugins_loaded', 'tyd_check_version');
//проверка версии плагина (запуск функции установки новых опций) end 

//функция установки новых опций при обновлении плагина у пользователей begin
function tyd_set_new_options() { 
    $tyd_options = get_option('tyd_options');

    //если нет опции при обновлении плагина - записываем ее
    //if (!isset($tyd_options['new_option'])) {$tyd_options['new_option']='value';}
    
    //если необходимо переписать уже записанную опцию при обновлении плагина
    //$tyd_options['old_option'] = 'new_value';
    
    $tyd_options['version'] = '1.01';
    update_option('tyd_options', $tyd_options);
}
//функция установки новых опций при обновлении плагина у пользователей end

//функция установки значений по умолчанию при активации плагина begin
function tyd_init() {
    
    $tyd_options = array(); tyd_setup();
    
    $tyd_options['version'] = '1.01';
    $tyd_options['date'] = "enabled";
    $tyd_options['moddate'] = "disabled";
    $tyd_options['comdate'] = "disabled";
    $tyd_options['delimiterdate'] = __(' at ', 'today-yesterday-dates');
    $tyd_options['delimitermoddate'] = __(' at ', 'today-yesterday-dates');
    $tyd_options['addtimetodate'] = "enabled";
    $tyd_options['addtimetomoddate'] = "disabled";
   
    add_option('tyd_options', $tyd_options);
}
add_action('activate_today-yesterday-dates/today-yesterday-dates.php', 'tyd_init');
//функция установки значений по умолчанию при активации плагина end

//функция при деактивации плагина begin
function tyd_on_deactivation() {
	if ( ! current_user_can('activate_plugins') ) return;
}
register_deactivation_hook( __FILE__, 'tyd_on_deactivation' );
//функция при деактивации плагина end

//функция при удалении плагина begin
function tyd_on_uninstall() {
	if ( ! current_user_can('activate_plugins') ) return;
    delete_option('tyd_options');
}
register_uninstall_hook( __FILE__, 'tyd_on_uninstall' );
//функция при удалении плагина end

//загрузка файла локализации плагина begin
function tyd_setup(){
    load_plugin_textdomain('today-yesterday-dates');
}
add_action('init', 'tyd_setup');
//загрузка файла локализации плагина end

//добавление ссылки "Настройки" на странице со списком плагинов begin
function tyd_actions($links) {
	return array_merge(array('settings' => '<a href="options-general.php?page=today-yesterday-dates.php">' . __('Settings', 'today-yesterday-dates') . '</a>'), $links);
}
add_filter('plugin_action_links_' . plugin_basename( __FILE__ ),'tyd_actions');
//добавление ссылки "Настройки" на странице со списком плагинов end

//функция загрузки скриптов и стилей плагина только в админке и только на странице настроек плагина begin
function tyd_files_admin($hook_suffix) {
	$purl = plugins_url('', __FILE__);
    if ( $hook_suffix == 'settings_page_today-yesterday-dates' ) {
        if(!wp_script_is('jquery')) {wp_enqueue_script('jquery');}    
        wp_register_script('tyd-lettering', $purl . '/inc/jquery.lettering.js');  
        wp_enqueue_script('tyd-lettering');
        wp_register_script('tyd-textillate', $purl . '/inc/jquery.textillate.js');
        wp_enqueue_script('tyd-textillate');
        wp_register_style('tyd-animate', $purl . '/inc/animate.min.css');
        wp_enqueue_style('tyd-animate');
        wp_register_script('tyd-script', $purl . '/inc/tyd-script.js', array(), '1.01');  
        wp_enqueue_script('tyd-script');
        wp_register_style('tyd-css', $purl . '/inc/tyd-css.css', array(), '1.01');
        wp_enqueue_style('tyd-css');
    }
}
add_action('admin_enqueue_scripts', 'tyd_files_admin');
//функция загрузки скриптов и стилей плагина только в админке и только на странице настроек плагина end

//функция вывода страницы настроек плагина begin
function tyd_options_page() {
$purl = plugins_url('', __FILE__);

if (isset($_POST['submit'])) {
     
//проверка безопасности при сохранении настроек плагина begin       
if ( ! wp_verify_nonce( $_POST['tyd_nonce'], plugin_basename(__FILE__) ) || ! current_user_can('edit_posts') ) {
   wp_die(__( 'Cheatin&#8217; uh?', 'today-yesterday-dates' ));
}
//проверка безопасности при сохранении настроек плагина end
        
    //проверяем и сохраняем введенные пользователем данные begin    
    $tyd_options = get_option('tyd_options');
    
    $tyd_options['date'] = sanitize_text_field($_POST['date']);
    $tyd_options['moddate'] = sanitize_text_field($_POST['moddate']);
    $tyd_options['comdate'] = sanitize_text_field($_POST['comdate']);
    
    if(isset($_POST['addtimetodate'])){$tyd_options['addtimetodate'] = sanitize_text_field($_POST['addtimetodate']);}else{$tyd_options['addtimetodate'] = 'disable';}
    if(isset($_POST['addtimetomoddate'])){$tyd_options['addtimetomoddate'] = sanitize_text_field($_POST['addtimetomoddate']);}else{$tyd_options['addtimetomoddate'] = 'disable';}
    
    $tyd_options['delimiterdate'] = esc_attr($_POST['delimiterdate']);
    $tyd_options['delimitermoddate'] = esc_attr($_POST['delimitermoddate']);
    
    update_option('tyd_options', $tyd_options);
    //проверяем и сохраняем введенные пользователем данные end
}
$tyd_options = get_option('tyd_options');
?>
<?php   if (!empty($_POST) ) :
if ( ! wp_verify_nonce( $_POST['tyd_nonce'], plugin_basename(__FILE__) ) || ! current_user_can('edit_posts') ) {
   wp_die(__( 'Cheatin&#8217; uh?', 'today-yesterday-dates' ));
}
?>
<div id="message" class="updated fade"><p><strong><?php _e('Options saved.', 'today-yesterday-dates'); ?></strong></p></div>
<?php endif; ?>

<div class="wrap">
<h2><?php _e('&#171;Today-Yesterday Dates&#187; Settings', 'today-yesterday-dates'); ?></h2>

<div class="metabox-holder" id="poststuff">
<div class="meta-box-sortables">

<?php $lang = get_locale(); ?>
<?php if ($lang == 'ru_RU') { ?>
<div class="postbox">
    <h3 style="border-bottom: 1px solid #EEE;background: #f7f7f7;"><span class="tcode">Вам нравится этот плагин ?</span></h3>
    <div class="inside" style="display: block;margin-right: 12px;">
        <img src="<?php echo $purl . '/img/icon_coffee.png'; ?>" title="Купить мне чашку кофе :)" style=" margin: 5px; float:left;" />
        <p>Привет, меня зовут <strong>Flector</strong>.</p>
        <p>Я потратил много времени на разработку этого плагина.<br />
		Поэтому не откажусь от небольшого пожертвования :)</p>
        <a target="_blank" id="yadonate" href="https://money.yandex.ru/to/41001443750704/200">Подарить</a> 
        <p>Или вы можете заказать у меня услуги по WordPress, от мелких правок до создания полноценного сайта.<br />
        Быстро, качественно и дешево. Прайс-лист смотрите по адресу <a target="new" href="https://www.wpuslugi.ru/?from=tyd-plugin">https://www.wpuslugi.ru/</a>.</p>
        <div style="clear:both;"></div>
    </div>
</div>
<?php } else { ?>
<div class="postbox">
    <h3 style="border-bottom: 1px solid #EEE;background: #f7f7f7;"><span class="tcode"><?php _e('Do you like this plugin ?', 'today-yesterday-dates'); ?></span></h3>
    <div class="inside" style="display: block;margin-right: 12px;">
        <img src="<?php echo $purl . '/img/icon_coffee.png'; ?>" title="<?php _e('buy me a coffee', 'today-yesterday-dates'); ?>" style=" margin: 5px; float:left;" />
        <p><?php _e('Hi! I\'m <strong>Flector</strong>, developer of this plugin.', 'today-yesterday-dates'); ?></p>
        <p><?php _e('I\'ve been spending many hours to develop this plugin.', 'today-yesterday-dates'); ?> <br />
		<?php _e('If you like and use this plugin, you can <strong>buy me a cup of coffee</strong>.', 'today-yesterday-dates'); ?></p>
        <a target="new" href="https://www.paypal.me/flector"><img alt="" src="<?php echo $purl . '/img/donate.gif'; ?>" title="<?php _e('Donate with PayPal', 'today-yesterday-dates'); ?>" /></a>
        <div style="clear:both;"></div>
    </div>
</div>
<?php } ?>

<form action="" method="post">

<div class="postbox">

    <h3 style="border-bottom: 1px solid #EEE;background: #f7f7f7;"><span class="tcode"><?php _e('Options', 'today-yesterday-dates'); ?></span></h3>
    <div class="inside" style="display: block;">

        <table class="form-table">
        
        <p><?php _e('This plugin changes the creation dates of posts to relative dates <strong>only</strong> for posts that are dated today or yesterday.', 'today-yesterday-dates'); ?></p>
            
            <tr>
                <th><?php _e('Post creation date:', 'today-yesterday-dates') ?></th>
                <td>
                     <select name="date" id="jdate">
                        <option value="enabled" <?php if ($tyd_options['date'] == 'enabled') echo 'selected="selected"'; ?>><?php _e('Enabled', 'today-yesterday-dates'); ?></option>
                        <option value="disabled" <?php if ($tyd_options['date'] == 'disabled') echo 'selected="selected"'; ?>><?php _e('Disabled', 'today-yesterday-dates'); ?></option>
                    </select>
                    <br /><small><?php _e('Change the creation dates of posts (the_date and the_time functions).', 'today-yesterday-dates'); ?> </small>
                </td>
            </tr>
            
            <tr class="addtimetodatetr" style="display:none;">
                <th><?php _e('Post creation time:', 'today-yesterday-dates') ?></th>
                <td>
                    <label for="addtimetodate"><input type="checkbox" value="enabled" name="addtimetodate" id="addtimetodate" <?php if ($tyd_options['addtimetodate'] == 'enabled') echo "checked='checked'"; ?> /><?php _e('Add time to date', 'today-yesterday-dates'); ?></label>
                    <br /><small><?php _e('Add a post\'s creation time to its relative date (for example, <tt>Today at 11:45</tt>).', 'today-yesterday-dates'); ?> </small>
                </td>
            </tr>
            
            <tr class="delimiterdatetr" style="display:none;">
                <th><?php _e('Divider:', 'today-yesterday-dates') ?></th>
                <td>
                    <input type="text" name="delimiterdate" size="50" value="<?php echo stripslashes($tyd_options['delimiterdate']); ?>" />
                    <br /><small><?php _e('Divider between relative date and time (<tt>,&nbsp;</tt> for <tt>Today, 11:45</tt> or <tt>&nbsp;at&nbsp;</tt> for <tt>Today at 11:45</tt>).', 'today-yesterday-dates'); ?>
                    </small>
                    <div style="margin-bottom:30px;"></div>
                </td>
            </tr>
            
            <tr>
                <th><?php _e('Post modification date:', 'today-yesterday-dates') ?></th>
                <td>
                     <select name="moddate"  id="jmoddate">
                        <option value="enabled" <?php if ($tyd_options['moddate'] == 'enabled') echo 'selected="selected"'; ?>><?php _e('Enabled', 'today-yesterday-dates'); ?></option>
                        <option value="disabled" <?php if ($tyd_options['moddate'] == 'disabled') echo 'selected="selected"'; ?>><?php _e('Disabled', 'today-yesterday-dates'); ?></option>
                    </select>
                    <br /><small><?php _e('Change the date of a post\'s most recent change (the_modified_date and the_modified_time functions).', 'today-yesterday-dates'); ?> <br /><?php _e('(The date of a post\'s most recent change is used in very few themes for WordPress).', 'today-yesterday-dates'); ?></small>
                </td>
            </tr>
            
            <tr class="addtimetomoddatetr" style="display:none;">
                <th><?php _e('Post creation time:', 'today-yesterday-dates') ?></th>
                <td>
                    <label for="addtimetomoddate"><input type="checkbox" value="enabled" name="addtimetomoddate" id="addtimetomoddate" <?php if ($tyd_options['addtimetomoddate'] == 'enabled') echo "checked='checked'"; ?> /><?php _e("Add time to date", "today-yesterday-dates"); ?></label>
                    <br /><small><?php _e('Add a post\'s creation time to its relative date (for example, <tt>Today at 11:45</tt>).', 'today-yesterday-dates'); ?> </small>
                </td>
            </tr>
            
            <tr class="delimitermoddatetr" style="display:none;">
                <th><?php _e('Divider:', 'today-yesterday-dates') ?></th>
                <td>
                    <input type="text" name="delimitermoddate" size="50" value="<?php echo stripslashes($tyd_options['delimitermoddate']); ?>" />
                    <br /><small><?php _e('Divider between relative date and time (<tt>,&nbsp;</tt> for <tt>Today, 11:45</tt> or <tt>&nbsp;at&nbsp;</tt> for <tt>Today at 11:45</tt>).', 'today-yesterday-dates'); ?>
                    </small>
                    <div style="margin-bottom:30px;"></div>
                </td>
            </tr>
            
            <tr>
                <th><?php _e('Dates of comments:', 'today-yesterday-dates') ?></th>
                <td>
                     <select name="comdate">
                        <option value="enabled" <?php if ($tyd_options['comdate'] == 'enabled') echo 'selected="selected"'; ?>><?php _e('Enabled', 'today-yesterday-dates'); ?></option>
                        <option value="disabled" <?php if ($tyd_options['comdate'] == 'disabled') echo 'selected="selected"'; ?>><?php _e('Disabled', 'today-yesterday-dates'); ?></option>
                    </select>
                    <br /><small><?php _e('Change the creation dates of comments.', 'today-yesterday-dates'); ?> </small>
                </td>
            </tr>
            
            <tr>
                <th></th>
                <td>
                    <input type="submit" name="submit" class="button button-primary" value="<?php _e('Update options &raquo;', 'today-yesterday-dates'); ?>" />
                </td>
            </tr>
            
        </table>
    </div>
</div>

<div class="postbox" style="margin-bottom:0;">
    <h3 style="border-bottom: 1px solid #EEE;background: #f7f7f7;"><span class="tcode"><?php _e('About', 'today-yesterday-dates'); ?></span></h3>
	  <div class="inside" style="padding-bottom:15px;display: block;">
     
      <p><?php _e('If you liked my plugin, please <a target="new" href="https://wordpress.org/plugins/today-yesterday-dates/"><strong>rate</strong></a> it.', 'today-yesterday-dates'); ?></p>
      <p style="margin-top:20px;margin-bottom:10px;"><?php _e('You may also like my other plugins:', 'today-yesterday-dates'); ?></p>
      
      <div class="about">
        <ul>
            <?php if ($lang == 'ru_RU') : ?>
            <li><a target="new" href="https://ru.wordpress.org/plugins/rss-for-yandex-zen/">RSS for Yandex Zen</a> - создание RSS-ленты для сервиса Яндекс.Дзен.</li>
            <li><a target="new" href="https://ru.wordpress.org/plugins/rss-for-yandex-turbo/">RSS for Yandex Turbo</a> - создание RSS-ленты для сервиса Яндекс.Турбо.</li>
            <?php endif; ?>
            <li><a target="new" href="https://wordpress.org/plugins/bbspoiler/">BBSpoiler</a> - <?php _e('this plugin allows you to hide text under the tags [spoiler]your text[/spoiler].', 'today-yesterday-dates'); ?></li>
            <li><a target="new" href="https://wordpress.org/plugins/easy-textillate/">Easy Textillate</a> - <?php _e('very beautiful text animations (shortcodes in posts and widgets or PHP code in theme files).', 'today-yesterday-dates'); ?> </li>
            <li><a target="new" href="https://wordpress.org/plugins/cool-image-share/">Cool Image Share</a> - <?php _e('this plugin adds social sharing icons to each image in your posts.', 'today-yesterday-dates'); ?> </li>
            <li><a target="new" href="https://wordpress.org/plugins/truncate-comments/">Truncate Comments</a> - <?php _e('this plugin uses Javascript to hide long comments (Amazon-style comments).', 'today-yesterday-dates'); ?> </li>
            <li><a target="new" href="https://wordpress.org/plugins/easy-yandex-share/">Easy Yandex Share</a> - <?php _e('share buttons for WordPress from Yandex. ', 'today-yesterday-dates'); ?> </li>
            </ul>
      </div>     
    </div>
</div>
<?php wp_nonce_field( plugin_basename(__FILE__), 'tyd_nonce'); ?>
</form>
</div>
</div>
<?php 
}
//функция вывода страницы настроек плагина end

//функция добавления ссылки на страницу настроек плагина в раздел "Настройки" begin
function tyd_menu() {
	add_options_page('Today-Yesterday Dates', 'Today-Yesterday Dates', 'manage_options', 'today-yesterday-dates.php', 'tyd_options_page');
}
add_action('admin_menu', 'tyd_menu');
//функция добавления ссылки на страницу настроек плагина в раздел "Настройки" end

//преобразовываем даты создания записей begin
function today_yesterday_date($the_date) {
if ( !is_admin() ) {    
    global $post, $previous_day;
    $tyd_options = get_option('tyd_options'); tyd_setup();
    
    if ($tyd_options['date']!='enabled') {return $the_date;}

    $todaytext = __('Today', 'today-yesterday-dates');
    $yesterdaytext = __('Yesterday', 'today-yesterday-dates');
    
    if(gmdate('Y', current_time('timestamp')) != mysql2date('Y', $post->post_date, false) && !empty($the_date)) {
        return $the_date;
    }
    $day_diff = (gmdate('z', current_time('timestamp')) - mysql2date('z', $post->post_date, false));
    if($day_diff < 0) { $day_diff = 32; }
    if ($the_date != $previous_day) {
        if($day_diff == 0) {
            if ($tyd_options['addtimetodate']=='enabled') {$the_date = $todaytext.$tyd_options['delimiterdate'].get_post_time(get_option('time_format'));}
            if ($tyd_options['addtimetodate']!='enabled') {$the_date = $todaytext;}
        } elseif($day_diff == 1) {
            if ($tyd_options['addtimetodate']=='enabled') {$the_date = $yesterdaytext.$tyd_options['delimiterdate'].get_post_time(get_option('time_format'));}
            if ($tyd_options['addtimetodate']!='enabled') {$the_date = $yesterdaytext;}
        } else {
            $the_date = $the_date;
        }
        $previous_day = $the_date;
    }
}
 return $the_date;   
}
add_filter('get_the_date', 'today_yesterday_date');
add_filter('get_the_time', 'today_yesterday_date');
//преобразовываем даты создания записей end

//преобразовываем даты изменения записей begin
function today_yesterday_moddate($the_date) {
if ( !is_admin() ) {    
    global $post, $previous_day;
    $tyd_options = get_option('tyd_options'); tyd_setup();
    
    if ($tyd_options['moddate']!='enabled') {return $the_date;}

    $todaytext = __('Today', 'today-yesterday-dates');
    $yesterdaytext = __('Yesterday', 'today-yesterday-dates');
    
    if(gmdate('Y', current_time('timestamp')) != mysql2date('Y', $post->post_modified, false) && !empty($the_date)) {
        return $the_date;
    }
    $day_diff = (gmdate('z', current_time('timestamp')) - mysql2date('z', $post->post_modified, false));
    if($day_diff < 0) { $day_diff = 32; }
    if ($the_date != $previous_day) {
        if($day_diff == 0) {
            if ($tyd_options['addtimetomoddate']=='enabled') {$the_date = $todaytext.$tyd_options['delimitermoddate'].get_post_time(get_option('time_format'));}
            if ($tyd_options['addtimetomoddate']!='enabled') {$the_date = $todaytext;}
        } elseif($day_diff == 1) {
            if ($tyd_options['addtimetomoddate']=='enabled') {$the_date = $yesterdaytext.$tyd_options['delimitermoddate'].get_post_time(get_option('time_format'));}
            if ($tyd_options['addtimetomoddate']!='enabled') {$the_date = $yesterdaytext;}
        } else {
            $the_date = $the_date;
        }
        $previous_day = $the_date;
    }
}
 return $the_date;   
}
add_filter('get_the_modified_date', 'today_yesterday_moddate');
add_filter('get_the_the_modified_time', 'today_yesterday_moddate');
//преобразовываем даты изменения записей end

//преобразовываем даты создания комментариев begin
function today_yesterday_comdate($the_date) {
if ( !is_admin() ) {    
    global $comment, $previous_day;
    $tyd_options = get_option('tyd_options'); tyd_setup();
    
    if ($tyd_options['comdate']!='enabled') {return $the_date;}

    $todaytext = __('Today', 'today-yesterday-dates');
    $yesterdaytext = __('Yesterday', 'today-yesterday-dates');
    
    if(gmdate('Y', current_time('timestamp')) != mysql2date('Y', $comment->comment_date, false) && !empty($the_date)) {
        return $the_date;
    }
    $day_diff = (gmdate('z', current_time('timestamp')) - mysql2date('z', $comment->comment_date, false));
    if($day_diff < 0) { $day_diff = 32; }
    if ($the_date != $previous_day) {
        if($day_diff == 0) {
            $the_date = $todaytext;
        } elseif($day_diff == 1) {
            $the_date = $yesterdaytext;
        } else {
            $the_date = $the_date;
        }
        $previous_day = $the_date;
    }
}
 return $the_date;   
}
add_filter('get_comment_date', 'today_yesterday_comdate');
//преобразовываем даты создания комментариев end