<?php

/***************************************************** */
// Function for get XML from feed and convert in array
/**************************************************** */

function adnki_feed2xml( $url ) {
    $log_file = dirname( __FILE__ ) . '/../logs/' . date( 'Ymd' ) . '_import.log';
    $response_xml_data = false;
    $clientHttp = new WP_Http();
    $response = $clientHttp->request( $url );

    if($response instanceof WP_Error){
        file_put_contents($log_file, " Response: ".json_encode($response)."\n ", FILE_APPEND);
        return;
    }

    $response_xml_data = $response['body'];

    if (is_string($response_xml_data)) {
        libxml_use_internal_errors(true);
        $data = simplexml_load_string($response_xml_data, null, LIBXML_NOCDATA);
        if ( ! $data) {
            foreach (libxml_get_errors() as $error) {
                file_put_contents($log_file, " Errore: ".$error->message."\n", FILE_APPEND);
            }
        } else {
            return ($data);
        }
    }

}

function adnki_xml2array( $xmlObject, $out = array() ) {
	foreach ( (array) $xmlObject as $index => $node ) {
		$out[ $index ] = ( is_object( $node ) ) ? adnki_xml2array( $node ) : $node;
	}

	return $out;
}

/* create post from Feed */

function adnk_create_post_from_result(
    $import_post,
    $post_category,
    $post_status = 'publish',
    $post_owner_email,
    $feed_name,
    $check_no_import_image,
    $image_owner_email)
{
	/* Create post object */
	$log_file = dirname( __FILE__ ) . '/../logs/' . date( 'Ymd' ) . '_import.log';
	file_put_contents( $log_file, " Valuto {$import_post->guid} \n", FILE_APPEND );

	$category = array( $post_category ); // category id

	$post_owner = get_user_by_email( $post_owner_email );

	$adkn_guid_post_feed = trim(substr( (string) $import_post->guid, strpos( (string) $import_post->guid, "_" ) + 1 ));

	$fullstring   = $import_post->pubDate;
	$date_article = adnk_import_tdm_get_string_between( $fullstring, ',', '+' );
	$date_post    = date( 'Y-m-d H:i:s', strtotime( $date_article ) );

	$new_post_id = '';

    global $wpdb;
    $hasdata = $wpdb->get_results("select * from $wpdb->postmeta where meta_key='adkn_guid_post_feed' and meta_value='$adkn_guid_post_feed'");
    $count= count($hasdata);
    file_put_contents( $log_file, " Contati $count \n", FILE_APPEND );
    if ($count == 0) {
        file_put_contents( $log_file, " Importo {$import_post->guid} \n", FILE_APPEND );
        $enclosure    = adnki_xml2array( $import_post->enclosure )['@attributes'];

        $post_content = '<p>' .$import_post->description.'</p>';
        if ( $feed_name == 'video'){
            @set_time_limit(120);
            $video_url = $enclosure['url'];
            file_put_contents( $log_file, "\nVideo enclosure: ".json_encode($enclosure)."\n", FILE_APPEND );
            $tmp = download_url( $video_url );
            $file_array = array(
                'name' => basename( $video_url ),
                'tmp_name' => $tmp
            );
            if ( is_wp_error( $tmp ) ) {
                file_put_contents( $log_file, "\nErrore: ".__LINE__, FILE_APPEND );
                @unlink( $file_array[ 'tmp_name' ] );
                return;
            }
            $id = media_handle_sideload( $file_array, 0 ); //download the file (0 represents the Post ID. (At this point in time I don't want it to be attached to a post so I've left it as 0)
            // Check for handle sideload errors.
            if ( is_wp_error( $id ) ) {
                file_put_contents( $log_file, "\nErrore: ".__LINE__, FILE_APPEND );
                @unlink( $file_array['tmp_name'] );
                return;
            } else {
                $attachment_url = wp_get_attachment_url( $id );
                $post_content .= '[video src="' . $attachment_url . '" width="512" height="360" autoplay="true"]';
            }
        }
        $post_content .= '<p>---</p>';
        $post_content .= '<p>' . $import_post->category.'</p>';
        $post_content .= '<p>' . $import_post->author.'</p>';
		$new_post = array(
			'post_title'    => $import_post->title,
			'post_content'  => esc_html( wp_strip_all_tags( $post_content ) ),
			'post_date'     => $date_post,
			'post_status'   => $post_status,
			'post_author'   => $post_owner->ID,
			'post_category' => $category
		);
		// Insert the post into the database
		$new_post_id = wp_insert_post( $new_post );
		$adkn_guid_post_feed = sanitize_text_field( $adkn_guid_post_feed );
		update_post_meta( $new_post_id, 'adkn_guid_post_feed', $adkn_guid_post_feed );
		$adkn_feed_category = sanitize_text_field( $import_post->category->__toString() );
		update_post_meta( $new_post_id, 'adkn_feed_category', $adkn_feed_category );

		// assign feautures image from url

		$image_url = $enclosure['url'];

		$photo_name = $import_post->title;

		if (substr($image_url,-3) != 'mp4' && $feed_name != 'video') {
            if(!$check_no_import_image == 'on') {
                adnk_upload_assign_image($new_post_id, $image_url, $photo_name, $image_owner_email);
            }
		}

		$tags = array( 'adnkronos', $feed_name ); // Array of Tags to add
		wp_set_post_tags( $new_post_id, $tags ); // Set tags to Post

        return $new_post_id;
	}

	return null;

}

/*
function adnk_eventual_consistency($ids){

}
*/

/********************************************************** */
/* upload image from URL and assign as post feauture image */
/********************************************************* */

function adnk_upload_assign_image( $post_id, $image_url, $photo_name, $owner_image_email ) {

	$title = get_the_title( $post_id );

    $image_owner = get_user_by_email( $owner_image_email );

    $photo = new WP_Http();
	$photo = $photo->request( $image_url );
	if ( is_array( $photo ) ) {
		$attachment = wp_upload_bits( $photo_name . '.jpg', null, $photo['body'], date( "Y-m", strtotime( $photo['headers']['last-modified'] ) ) );

		$filetype = wp_check_filetype( basename( $attachment['file'] ), null );

		$postinfo  = array(
			'post_mime_type' => $filetype['type'],
			'post_title'     => $title . ' ',
			'post_content'   => '',
			'post_status'    => 'inherit',
            'post_author'   => $image_owner->ID,
		);
		$filename  = $attachment['file'];
		$attach_id = wp_insert_attachment( $postinfo, $filename, $post_id );

		if ( ! function_exists( 'wp_generate_attachment_data' ) ) {
			require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
		}

		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );
		set_post_thumbnail( $post_id, $attach_id );
	}

}


add_action( 'adki_add_cron_onceaday', 'adnk_import_invio_dati_statistici' );
add_action( 'admin_action_adnkinvio', 'adnk_import_invio_dati_statistici' );
/* invio dati statistici */


function adnk_import_invio_dati_statistici() {

    $consent_send_statistical_data = get_adnk_settings_option('consent_send_statistical_data');

    if($consent_send_statistical_data != 'on'){
        return;
    }
    if(!adnk_verify_site()){
        return;
    }

	$array_dati = adnk_import_dati_statistici();
	$yesterday = getdate( time() - 86400 );
	$yesterdaStr = $yesterday['year'].'-'.$yesterday['mon'].'-'.$yesterday['mday'];
	$site_url = get_home_url();
	$url      = 'https://plugin.adnkronos.com/api/v1/stat/send';

	foreach ( $array_dati as $category => $value ) {

		$dati_ieri = array( 'domain'   => $site_url,
		                    'category' => $category,
							'date'     => $yesterdaStr,
		                    'published'  => $value['publish'],
							'imported'    => $value['publish']+$value['draft']
		);
		/* chiamata per invio dati */
		$response = wp_remote_post( $url, array(
				'method'      => 'POST',
				'timeout'     => 45,
				'redirection' => 5,
				'httpversion' => '1.1',
                'sslverify'   => false,
				'blocking'    => true,
				'headers'     => array( 'Authorization' => 'adn83jhfk823rh2r' ),
				'body'        => json_encode( $dati_ieri ),
				'cookies'     => array()
			) );
	}

}

/*{
    "domain": "http://www.provadomanio.it",
    "date": "2022-06-30",
    "category": "sport",
    "imported": 36,
    "published": 12
} */
function adnk_import_dati_statistici() {

	$yesterday = getdate( time() - 86400 );
	$y_posts_ids = array();
	$agrs        = array(
		'post_type'      => 'post',
		'posts_per_page' => - 1,
		'date_query'     => array(
			array(
				'year'  => $yesterday['year'],
				'month' => $yesterday['mon'],
				'day'   => $yesterday['mday'],
			),
		),
	);
	$loop        = new WP_Query( $agrs );

	if ( $loop->have_posts() ) :
		while ( $loop->have_posts() ) : $loop->the_post();

			$categoria = get_post_meta( get_the_ID(), 'adkn_feed_category', true );

			if ( ! empty( $categoria ) ) {
				if ( ! array_key_exists( $categoria, $y_posts_ids ) ) {
					$y_posts_ids[ $categoria ] = array();
				}

				if ( get_post_status() == 'publish' ) {
					$y_posts_ids[ $categoria ]['publish'] ++;
				} else {
					$y_posts_ids[ $categoria ]['draft'] ++;
				}
			}

		endwhile;
	endif;

	return $y_posts_ids;
}


function adnk_verify_site() {
	$site_url = get_home_url();
    $url      = 'https://plugin.adnkronos.com/api/v1/site/verify';
	$response = wp_remote_post( $url, array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
            //'sslverify'   => false,
			'blocking'    => true,
			'headers'     => array(),
			'body'        => array( 'domain' => $site_url ),
			'cookies'     => array()
		) );
    //var_dump($response);
    if ( is_wp_error( $response ) ) {
		$error_message = $response->get_error_message();
		update_option( 'adn_site_active', 0, true );

		return false;
	} else {
		$body  = $response['body'];
		$esito = json_decode( $body );
		if ( $esito->response == 'OK' ) {
			update_option( 'adn_site_active', 1, true );

			return true;
		}
        if ( $esito->response == 'KO' ) {
            update_option( 'adn_site_active', 0, true );

            return true;
        }

        update_option( 'adn_site_active', 1, true );
		return false;

	}

}

add_action( 'admin_action_adnkverify', 'adnk_verify_site' );


if ( is_admin() ) {
	remove_action('admin_action_adnk_import_now', 'adnk_import_feed');
	add_action( 'admin_action_adnk_import_now', 'adnk_import_feed' );
}

function adnk_import_now() {
	$log_file = dirname( __FILE__ ) . '/../logs/' . date( 'Ymd' ) . '_import.log';
	$orario   = ( new DateTime() )->format( 'Y-m-d H:i:s' );
	file_put_contents( $log_file, $orario . " Inizio importazione in adnk_import_now()\n", FILE_APPEND );

	adnk_import_feed();

	//torno alla pagina chiamante
	//$url = wp_get_raw_referer();
	//wp_redirect( site_url() . '/wp-admin/edit.php' );
}


function adnk_import_tdm_get_string_between( $string, $start, $end ) {
	$string = ' ' . $string;
	$ini    = strpos( $string, $start );
	if ( $ini == 0 ) {
		return '';
	}
	$ini += strlen( $start );
	$len = strpos( $string, $end, $ini ) - $ini;

	return substr( $string, $ini, $len );
}

