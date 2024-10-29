<?php
   /**
   * Plugin Name: AdnKronos Feed Importer
   * Plugin URI: https://adnkronos.com
   * Description: Import news from Feed AdnKronos
   * Version: 1.1.0
   * Author: AdnKronos
   * Author URI: https://adnkronos.com
   **/


  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* register style and script */
/* add_action( 'wp_enqueue_scripts', 'adnk_register_script');

function adnk_register_script(){
   wp_register_style( 'slider-style', plugin_dir_url( __FILE__ ) . '/css/slider-style.css');

   wp_register_script( 'functionjs', plugin_dir_url( __FILE__ ) . '/js/adnk-function.js', array ( 'jquery' ), 1.1, true);
} */



 // includo file di utitlity
 include_once('inc/utility.php');
 include_once('admin/option-panel.php');
 

function adnk_import_feed() {
   /* get option from option page */
	$log_file = dirname(__FILE__) . '/logs/' . date('Ymd') . '_import.log';
	$orario = (new DateTime())->format('Y-m-d H:i:s');
	file_put_contents($log_file, $orario." Inizio importazione\n", FILE_APPEND);
	sleep(random_int(0,9));
	$transient = get_transient( 'adnk_import_running' );
	if (!(false === $transient) ) {
		file_put_contents($log_file, $orario." Importazione non lanciata perchè entro i 180s dalla precedente\n", FILE_APPEND);
        $url = wp_get_raw_referer();
        wp_redirect( $url );
        return;
    } else {
        set_transient( 'adnk_import_running', 'running', 180 );
        file_put_contents($log_file, $orario." Avvio effettivo\n", FILE_APPEND);
    }
	$post_ids_array = [];

   $selected_post_owner_email = get_adnk_importer_option('selected_post_owner');
   $selected_image_owner_email = get_adnk_importer_option('selected_image_owner');
   $check_no_image_import = get_adnk_importer_option( 'check_no_image_import' );

   $consent_send_statistical_data = get_adnk_settings_option('consent_send_statistical_data');

   $ultimora_feed_checked = get_adnk_importer_option('url_ultimora_feed');
   $ultimora_feed_category = esc_html(get_adnk_importer_option( 'selected_ultimora_feed_category' ));
   $ultimora_post_status = esc_html(get_adnk_importer_option( 'selected_ultimora_post_status' ));

   $primapagina_feed_checked = get_adnk_importer_option('url_primapagina_feed');
   $primapagina_feed_category = esc_html(get_adnk_importer_option( 'selected_primapagina_feed_category' ));
   $primapagina_post_status = esc_html(get_adnk_importer_option( 'selected_primapagina_post_status' ));

   $newsregionali_feed_checked = get_adnk_importer_option('url_newsregionali_feed');
   $newsregionali_feed_category = esc_html(get_adnk_importer_option( 'selected_newsregionali_feed_category' ));
   $newsregionali_post_status = esc_html(get_adnk_importer_option( 'selected_newsregionali_post_status' ));

   $salute_feed_checked = get_adnk_importer_option('url_salute_feed');
   $salute_feed_category = esc_html(get_adnk_importer_option( 'selected_salute_feed_category' ));
   $salute_post_status = esc_html(get_adnk_importer_option( 'selected_salute_post_status' ));

   $lavoro_feed_checked = get_adnk_importer_option('url_lavoro_feed');
   $lavoro_feed_category = esc_html(get_adnk_importer_option( 'selected_lavoro_feed_category' ));
   $lavoro_post_status = esc_html(get_adnk_importer_option( 'selected_lavoro_post_status' ));

   $sostenibilita_feed_checked = get_adnk_importer_option('url_sostenibilita_feed');
   $sostenibilita_feed_category = esc_html(get_adnk_importer_option( 'selected_sostenibilita_feed_category' ));
   $sostenibilita_post_status = esc_html(get_adnk_importer_option( 'selected_sostenibilita_post_status' ));

   $comunicati_feed_checked = get_adnk_importer_option('url_comunicati_feed');
   $comunicati_feed_category = esc_html(get_adnk_importer_option( 'selected_comunicati_feed_category' ));
   $comunicati_post_status = esc_html(get_adnk_importer_option( 'selected_comunicati_post_status' ));

   $motori_feed_checked = get_adnk_importer_option('url_motori_feed');
   $motori_feed_category = esc_html(get_adnk_importer_option( 'selected_motori_feed_category' ));
   $motori_post_status = esc_html(get_adnk_importer_option( 'selected_motori_post_status' ));

   $fintech_feed_checked = get_adnk_importer_option('url_fintech_feed');
   $fintech_feed_category = esc_html(get_adnk_importer_option( 'selected_fintech_feed_category' ));
   $fintech_post_status = esc_html(get_adnk_importer_option( 'selected_fintech_post_status' ));

   $tecnologia_feed_checked = get_adnk_importer_option('url_tecnologia_feed');
   $tecnologia_feed_category = esc_html(get_adnk_importer_option( 'selected_tecnologia_feed_category' ));
   $tecnologia_post_status = esc_html(get_adnk_importer_option( 'selected_tecnologia_post_status' ));

   $wine_feed_checked = get_adnk_importer_option('url_wine_feed');
   $wine_feed_category = esc_html(get_adnk_importer_option( 'selected_wine_feed_category' ));
   $wine_post_status = esc_html(get_adnk_importer_option( 'selected_wine_post_status' ));

   $video_feed_checked = get_adnk_importer_option('url_video_feed');
   $video_feed_category = esc_html(get_adnk_importer_option( 'selected_video_feed_category' ));
   $video_post_status = esc_html(get_adnk_importer_option( 'selected_video_post_status' ));


   $feed_url_array = array();

   if($ultimora_feed_checked == 'on' ){
      $feed_url_array['ultimora']['url'] = 'https://www.adnkronos.com/NewsFeed/Ultimora.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['ultimora']['status'] = $ultimora_post_status;
      $feed_url_array['ultimora']['category'] = $ultimora_feed_category;
   }
   if($primapagina_feed_checked == 'on' ){
      $feed_url_array['primapagina']['url'] = 'https://www.adnkronos.com/NewsFeed/PrimaPagina.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['primapagina']['status'] = $primapagina_post_status;
      $feed_url_array['primapagina']['category'] = $primapagina_feed_category;
   }
   if($newsregionali_feed_checked == 'on' ){
      $feed_url_array['newsregionali']['url'] = 'https://www.adnkronos.com/NewsFeed/Regioni.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['newsregionali']['status'] = $newsregionali_post_status;
      $feed_url_array['newsregionali']['category'] = $newsregionali_feed_category;
   }
   if($salute_feed_checked == 'on' ){
      $feed_url_array['salute']['url'] = 'https://www.adnkronos.com/NewsFeed/Salute.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['salute']['status'] = $salute_post_status;
      $feed_url_array['salute']['category'] = $salute_feed_category;
   }
   if($lavoro_feed_checked == 'on' ){
      $feed_url_array['lavoro']['url'] = 'https://www.adnkronos.com/NewsFeed/Labitalia.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['lavoro']['status'] = $lavoro_post_status;
      $feed_url_array['lavoro']['category'] = $lavoro_feed_category;
   }
   if($sostenibilita_feed_checked == 'on' ){
      $feed_url_array['sostenibilita']['url'] = 'https://www.adnkronos.com/NewsFeed/Sostenibilita.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['sostenibilita']['status'] = $sostenibilita_post_status;
      $feed_url_array['sostenibilita']['category'] = $sostenibilita_feed_category;
   }
   if($comunicati_feed_checked == 'on' ){
      $feed_url_array['comunicati']['url'] = 'https://www.adnkronos.com/NewsFeed/Immediapress.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['comunicati']['status'] = $comunicati_post_status;
      $feed_url_array['comunicati']['category'] = $comunicati_feed_category;
   }
   if($motori_feed_checked == 'on' ){
      $feed_url_array['motori']['url'] = 'https://www.adnkronos.com/NewsFeed/Motori.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['motori']['status'] = $motori_post_status;
      $feed_url_array['motori']['category'] = $motori_feed_category;
   }
   if($fintech_feed_checked == 'on' ){
      $feed_url_array['fintech']['url'] = 'https://www.adnkronos.com/NewsFeed/Fintech.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['fintech']['status'] = $fintech_post_status;
      $feed_url_array['fintech']['category'] = $fintech_feed_category;
   }
   if($tecnologia_feed_checked == 'on' ){
      $feed_url_array['tecnologia']['url'] = 'https://www.adnkronos.com/NewsFeed/Tech&Games.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['tecnologia']['status'] = $tecnologia_post_status;
      $feed_url_array['tecnologia']['category'] = $tecnologia_feed_category;
   }
   if($wine_feed_checked == 'on' ){
      $feed_url_array['wine']['url'] = 'https://www.adnkronos.com/NewsFeed/Wine.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['wine']['status'] = $wine_post_status;
      $feed_url_array['wine']['category'] = $wine_feed_category;
   }
   if($video_feed_checked == 'on' ){
      $feed_url_array['video']['url'] = 'https://www.adnkronos.com/NewsFeed/VideoWp.xml?username=pluginadnk&password=pl8g1n45tg';
      $feed_url_array['video']['status'] = $video_post_status;
      $feed_url_array['video']['category'] = $video_feed_category;
   }

   $feedJson = json_encode($feed_url_array);

   foreach( $feed_url_array as $key => $feed_argument){

      $post_feed_result_array = adnki_xml2array(adnki_feed2xml($feed_argument['url']));
      /* creare i post della categoria giusta e nello stato selezionato */
      //file_put_contents($log_file, $orario." Ricevuto feed:\n ".json_encode($post_feed_result_array)."\n", FILE_APPEND);

      foreach ( $post_feed_result_array['channel']['item'] as $post){

         // guid creare custom field e controllare se già esiste prima di importarlo
         $new_post_id = adnk_create_post_from_result(
             $post,
             $feed_argument['category'],
             $feed_argument['status'],
             $selected_post_owner_email,
             $key,
             $check_no_image_import,
             $selected_image_owner_email
         );

         if(!is_null($new_post_id)) {
             $post_ids_array[] = $new_post_id;
         }

         //error_log($new_post_id);
      }
   }

   //adnk_eventual_consistency($post_ids_array);

   //wp_redirect( site_url() . '/wp-admin/edit.php' );
   return $post_ids_array;
}
 

/* set cron job */
add_filter( 'cron_schedules', 'adnk_import_cron_schedule' );

function adnk_import_cron_schedule( $schedules ) {
	$schedules[ 'halfhourly' ] = array(
		'interval' => 1800,
		'display' => __( 'halfhourly' ) );

   $schedules[ 'twohourly' ] = array( 
       'interval' => 7200,
       'display' => __( 'twohourly' ) );

   $schedules[ 'fourhourly' ] = array( 
       'interval' => 14400,
       'display' => __( 'fourhourly' ) );

   $schedules[ 'sixhourly' ] = array( 
       'interval' => 21600,
       'display' => __( 'sixhourly' ) );
   return $schedules;
}

register_activation_hook(__FILE__, 'adnk_import_activation');
 
function adnk_import_activation($selected_freq_import = 'daily') {

   wp_clear_scheduled_hook( 'adnk_import_event' );

    if (! wp_next_scheduled ( 'adnk_import_event' )) {
      $date = date("h:i:sa",strtotime('+5 minutes' ));
      wp_schedule_event(strtotime($date), $selected_freq_import, 'adnk_import_event');
    }


    /* cron invio dati statistici */
    // Schedule an action if it's not already scheduled
    if ( ! wp_next_scheduled( 'adki_add_cron_onceaday' ) ) {
        wp_schedule_event( time(), 'daily', 'adki_add_cron_onceaday' );
    }

}
 
add_action('adnk_import_event', 'adnk_import_feed');