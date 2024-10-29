<?php
/*
Admin Panel Option
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'adnk_importer_Options' ) ) {

	class adnk_importer_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'adnk_importer_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'adnk_importer_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all adnk_importer options
		 *
		 * @since 1.0.0
		 */
		public static function get_adnk_importer_options() {
			return get_option( 'adnk_importer_options' );
		}

		/**
		 * Returns single adnk_importer option
		 *
		 * @since 1.0.0
		 */
		public static function get_adnk_importer_option( $id ) {
			$options = self::get_adnk_importer_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_menu_page(
				esc_html__( 'AdnKronos', 'adnk_importer' ),
				esc_html__( 'AdnKronos', 'adnk_importer' ),
				'manage_options',
				'adnk-plugin-settings',
				array( 'adnk_importer_Options', 'create_admin_page' )
			);
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'adnk_importer_options', 'adnk_importer_options', array( 'adnk_importer_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			//Get the active tab from the $_GET param
			$default_tab = null;
			$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $default_tab;
			

			// If we have options lets sanitize them
			if ( $options ) {

				if($tab === null){
					// Checkbox consent_send_statistical_data
					if ( ! empty( $options['consent_send_statistical_data'] ) ) {
						$options['consent_send_statistical_data'] = 'on';
					} else {
						unset( $options['consent_send_statistical_data'] ); // Remove from options if not checked
					} 
				}elseif($tab==='settings' ){
					// Select selected_post_owner
					if ( ! empty( $options['selected_post_owner'] ) ) {
						$options['selected_post_owner'] = sanitize_text_field( $options['selected_post_owner'] );
					}

					// frenquenza import  
					if ( ! empty( $options['selected_freq_import'] ) ) {
						$options['selected_freq_import'] = sanitize_text_field( $options['selected_freq_import'] );
					}
					// Checkbox ultimora Feed
					if ( ! empty( $options['url_ultimora_feed'] ) ) {
						$options['url_ultimora_feed'] = 'on';
					} else {
						unset( $options['url_ultimora_feed'] ); // Remove from options if not checked
					} 

					// Select ultimora Feed Category
					if ( ! empty( $options['selected_ultimora_feed_category'] ) ) {
						$options['selected_ultimora_feed_category'] = sanitize_text_field( $options['selected_ultimora_feed_category'] );
					}

					// Select ultimora posts status
					if ( ! empty( $options['selected_ultimora_post_status'] ) ) {
						$options['selected_ultimora_post_status'] = sanitize_text_field( $options['selected_ultimora_post_status'] );
					}

					// Checkbox Prima Pagina Feed
					if ( ! empty( $options['url_primapagina_feed'] ) ) {
						$options['url_ultimora_feed'] = 'on';
					} else {
						unset( $options['url_primapagina_feed'] ); // Remove from options if not checked
					} 

					// Select primapagina Feed Category
					if ( ! empty( $options['selected_primapagina_feed_category'] ) ) {
						$options['selected_primapagina_feed_category'] = sanitize_text_field( $options['selected_primapagina_feed_category'] );
					}

					// Select primapagina posts status
					if ( ! empty( $options['selected_primapagina_post_status'] ) ) {
						$options['selected_primapagina_post_status'] = sanitize_text_field( $options['selected_primapagina_post_status'] );
					}

					// Checkbox News Regionali Feed
					if ( ! empty( $options['url_newsregionali_feed'] ) ) {
						$options['url_newsregionali_feed'] = 'on';
					} else {
						unset( $options['url_newsregionali_feed'] ); // Remove from options if not checked
					} 

					// Select newsregionali Feed Category
					if ( ! empty( $options['selected_newsregionali_feed_category'] ) ) {
						$options['selected_newsregionali_feed_category'] = sanitize_text_field( $options['selected_newsregionali_feed_category'] );
					}

					// Select newsregionali posts status
					if ( ! empty( $options['selected_newsregionali_post_status'] ) ) {
						$options['selected_newsregionali_post_status'] = sanitize_text_field( $options['selected_newsregionali_post_status'] );
					}

					// Checkbox Salute Feed
					if ( ! empty( $options['url_salute_feed'] ) ) {
						$options['url_salute_feed'] = 'on';
					} else {
						unset( $options['url_salute_feed'] ); // Remove from options if not checked
					} 

					// Select salute Feed Category
					if ( ! empty( $options['selected_salute_feed_category'] ) ) {
						$options['selected_salute_feed_category'] = sanitize_text_field( $options['selected_salute_feed_category'] );
					}

					// Select salute posts status
					if ( ! empty( $options['selected_salute_post_status'] ) ) {
						$options['selected_salute_post_status'] = sanitize_text_field( $options['selected_salute_post_status'] );
					}

					// Checkbox Lavoro Feed
					if ( ! empty( $options['url_lavoro_feed'] ) ) {
						$options['url_lavoro_feed'] = 'on';
					} else {
						unset( $options['url_lavoro_feed'] ); // Remove from options if not checked
					} 

					// Select lavoro Feed Category
					if ( ! empty( $options['selected_lavoro_feed_category'] ) ) {
						$options['selected_lavoro_feed_category'] = sanitize_text_field( $options['selected_lavoro_feed_category'] );
					}

					// Select lavoro posts status
					if ( ! empty( $options['selected_lavoro_post_status'] ) ) {
						$options['selected_lavoro_post_status'] = sanitize_text_field( $options['selected_lavoro_post_status'] );
					}

					// Checkbox sostenibilita Feed
					if ( ! empty( $options['url_sostenibilita_feed'] ) ) {
						$options['url_sostenibilita_feed'] = 'on';
					} else {
						unset( $options['url_sostenibilita_feed'] ); // Remove from options if not checked
					} 

					// Select sostenibilita Feed Category
					if ( ! empty( $options['selected_sostenibilita_feed_category'] ) ) {
						$options['selected_sostenibilita_feed_category'] = sanitize_text_field( $options['selected_sostenibilita_feed_category'] );
					}

					// Select sostenibilita posts status
					if ( ! empty( $options['selected_sostenibilita_post_status'] ) ) {
						$options['selected_sostenibilita_post_status'] = sanitize_text_field( $options['selected_sostenibilita_post_status'] );
					}

					// Checkbox comunicati Feed
					if ( ! empty( $options['url_comunicati_feed'] ) ) {
						$options['url_comunicati_feed'] = 'on';
					} else {
						unset( $options['url_comunicati_feed'] ); // Remove from options if not checked
					} 

					// Select comunicati Feed Category
					if ( ! empty( $options['selected_comunicati_feed_category'] ) ) {
						$options['selected_comunicati_feed_category'] = sanitize_text_field( $options['selected_comunicati_feed_category'] );
					}

					// Select comunicati posts status
					if ( ! empty( $options['selected_comunicati_post_status'] ) ) {
						$options['selected_comunicati_post_status'] = sanitize_text_field( $options['selected_comunicati_post_status'] );
					}

					// Checkbox motori Feed
					if ( ! empty( $options['url_motori_feed'] ) ) {
						$options['url_motori_feed'] = 'on';
					} else {
						unset( $options['url_motori_feed'] ); // Remove from options if not checked
					} 

					// Select motori Feed Category
					if ( ! empty( $options['selected_motori_feed_category'] ) ) {
						$options['selected_motori_feed_category'] = sanitize_text_field( $options['selected_motori_feed_category'] );
					}

					// Select motori posts status
					if ( ! empty( $options['selected_motori_post_status'] ) ) {
						$options['selected_motori_post_status'] = sanitize_text_field( $options['selected_motori_post_status'] );
					}

					// Checkbox fintech Feed
					if ( ! empty( $options['url_fintech_feed'] ) ) {
						$options['url_fintech_feed'] = 'on';
					} else {
						unset( $options['url_fintech_feed'] ); // Remove from options if not checked
					} 

					// Select fintech Feed Category
					if ( ! empty( $options['selected_fintech_feed_category'] ) ) {
						$options['selected_fintech_feed_category'] = sanitize_text_field( $options['selected_fintech_feed_category'] );
					}

					// Select fintech posts status
					if ( ! empty( $options['selected_fintech_post_status'] ) ) {
						$options['selected_fintech_post_status'] = sanitize_text_field( $options['selected_fintech_post_status'] );
					}
				}
				

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>

		<?php 
        /* call function for register user  */

		//$site_url = get_home_url();
		$regist_user = adnk_verify_site();
		

		if(get_adnk_settings_option( 'consent_send_statistical_data' ) == 'on' || $regist_user == true){
			$field_disabled = '';
		}else{
			$field_disabled = 'disabled';
		}

		/* set cron event */
		$selected_freq_import = get_adnk_importer_option('selected_freq_import');
		adnk_import_activation($selected_freq_import);
		?>
        <div id="adk">
           <div class="container wrap">
                <?php $logo_tdm_url = plugin_dir_url(__FILE__)."img/logoadnkronos.jpg"?>
				<h1><img src="<?php echo esc_url($logo_tdm_url) ?>"><?php esc_html_e( 'AdnKronos Feed Importer Options', 'adnk_importer' ); ?></h1>
				<p></p>

				<nav class="nav-tab-wrapper">
					<a href="?page=adnk-plugin-settings" class="nav-tab nav-tab-active"><?php esc_html_e('Impostazioni di Importazione','adnk_importer');?></a>
					<a href="?page=adnk-plugin-account" class="nav-tab "><?php echo esc_html_e('Abilitazione dominio','adnk_importer');?></a>
				</nav>

				<div class="tab-content">
				<div class="row">
                    <div class="col-9">

                    <form method="post" action="options.php" novalidate>

                    <?php settings_fields( 'adnk_importer_options' ); ?>

                    <div class="card card-static mt-4">
                        <div class="card-header"><strong><?php esc_html_e( 'Utente di destinazione di articoli e foto', 'adnk_importer' ); ?></strong></div>
                        <div class="card-body">
                            <p>
                                Selezionare l'utente che risulterà proprietario degli articoli dopo l'importazione.
                            </p>
	                        <?php $users = get_users( array( 'fields' => array( 'user_email','user_login' ) ) );?>
	                        <?php $value = self::get_adnk_importer_option( 'selected_post_owner' ); ?>
                            <select required id="selected_post_owner" name="adnk_importer_options[selected_post_owner]">
                                <option value=""><?php esc_html_e('Seleziona proprietario del post','adnk_importer') ?></option>
                                <?php  foreach ($users as $user_email){?>
                                    <option value="<?php echo esc_attr($user_email->user_email) ; ?>" <?php selected( $user_email->user_email, $value ); ?>><?php echo esc_html($user_email->user_login).' - '.esc_html($user_email->user_email) ; ?>
                                    </option>
		                        <?php } ?>
                            </select>
                        </div>
                        <div class="card-body">
                            <p>
                                Selezionare l'utente che risulterà proprietario delle immagini dopo l'importazione.<br />
                                Può essere diverso dal proprietario del post.
                            </p>
                            <p>
                            <?php $value = self::get_adnk_importer_option( 'check_no_image_import' ); ?>

                            <input type="checkbox" name="adnk_importer_options[check_no_image_import]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Non importare le immagini', 'adnk_importer' ); ?>
                            </p>
                            <?php $value = self::get_adnk_importer_option( 'selected_image_owner' ); ?>
                            <select required id="selected_image_owner" name="adnk_importer_options[selected_image_owner]">
                                <option value=""><?php esc_html_e('Seleziona proprietario delle immagini','adnk_importer') ?></option>
                                <?php  foreach ($users as $user_email){?>
                                    <option value="<?php echo esc_attr($user_email->user_email) ; ?>" <?php selected( $user_email->user_email, $value ); ?>><?php echo esc_html($user_email->user_login).' - '.esc_html($user_email->user_email) ; ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="card card-static mt-4">
                        <div class="card-header"><strong><?php esc_html_e( 'Intervallo di Import articoli', 'adnk_importer' ); ?></strong></div>
	                    <div class="card-body">
                            <p>
                                Selezionare l'intervallo temporale di importazione automatica degli articoli. E' possibile avviare una importazione
                                immediata con il pulsante "Importa Ora".
                            </p>
                            <div class="row">
                                <div class="col-9">
                                    <?php $value = self::get_adnk_importer_option( 'selected_freq_import' ); ?>
                                    <select id="selected_freq_import" name="adnk_importer_options[selected_freq_import]">
                                        <option value="halfhourly" <?php selected( 'halfhourly', $value ); ?>><?php esc_html_e('Una volta ogni 30 minuti','adnk_importer') ?></option>
                                        <option value="hourly" <?php selected( 'hourly', $value ); ?>><?php esc_html_e('Una volta ogni ora','adnk_importer') ?></option>
                                        <option value="twohourly" <?php selected( 'twohourly', $value ); ?>><?php esc_html_e('Una volta ogni due ore','adnk_importer') ?></option>
                                        <option value="fourhourly" <?php selected( 'fourhourly', $value ); ?>><?php esc_html_e('Una volta ogni quattro ore','adnk_importer') ?></option>
                                        <option value="sixhourly" <?php selected( 'sixhourly', $value ); ?>><?php esc_html_e('Una volta ogni sei ore','adnk_importer') ?></option>
                                        <option value="twicedaily" <?php selected( 'twicedaily', $value ); ?>><?php esc_html_e('Ogni 12 ore','adnk_importer') ?></option>
                                        <option value="daily" <?php selected( 'daily', $value ); ?>><?php esc_html_e('Una volta al giorno','adnk_importer') ?></option>
		                            </select>
                                    <span class="message"></span>
                                </div>
                                <div class="col-3">
                                    <a href="<?php echo esc_url(site_url()) ?>/wp-admin/admin.php?action=adnk_import_now" style="margin-bottom: 5px;" class="button button-primary">Importa Ora</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php
                        $args = array("hide_empty" => 0,
                        "type"      => "post",
                        "orderby"   => "name",
                        "order"     => "ASC" );
                        $categories = get_categories($args);
                    ?>
                    <div class="card card-static">
                        <div class="card-header">
                            Feed disponibili
                        </div>
                        <div class="card-body">
                            <p>
                                E' possibile per ogni feed rilasciato da AdnKronos selezionare la categoria di
                                destinazione ed il relativo stato di pubblicazione.<br />
                                Per abilitare l'accesso a tutti i feed è necessario registrare il tuo dominio.
                            </p>
                            <table class="form-table wpex-custom-admin-login-table">

                    <tr valign="top">
                        <td><strong><?php esc_html_e( 'Feed da importare', 'adnk_importer' ); ?></strong></td>
                        <td><strong><?php esc_html_e( 'Categoria di destinazione', 'adnk_importer' ); ?></strong></td>
                        <td><strong><?php esc_html_e( 'Stato di destinazione', 'adnk_importer' ); ?></strong></td>
                    </tr>

                        <?php // Feed URL selection Ultima Ora?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_ultimora_feed' ); ?>
                                <input type="checkbox" name="adnk_importer_options[url_ultimora_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Ultim\'ora', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import ultimora Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_ultimora_feed_category' ); ?>
                                <select required id="selected_ultimora_feed_category" name="adnk_importer_options[selected_ultimora_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import Ultim'ora ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_ultimora_post_status' ); ?>
                                <select id="selected_ultimora_post_status" name="adnk_importer_options[selected_ultimora_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection Prima Pagina ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_primapagina_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_primapagina_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Prima Pagina', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import primapagina Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_primapagina_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_primapagina_feed_category" name="adnk_importer_options[selected_primapagina_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import Prima Pagina ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_primapagina_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_primapagina_post_status" name="adnk_importer_options[selected_primapagina_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection News Regionali ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_newsregionali_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_newsregionali_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'News Regionali', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import newsregionali Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_newsregionali_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_newsregionali_feed_category" name="adnk_importer_options[selected_newsregionali_feed_category]">
                                <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import newsregionali ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_newsregionali_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_newsregionali_post_status" name="adnk_importer_options[selected_newsregionali_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection Salute ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_salute_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_salute_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Salute', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import salute Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_salute_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_salute_feed_category" name="adnk_importer_options[selected_salute_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import salute ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_salute_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_salute_post_status" name="adnk_importer_options[selected_salute_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection Lavoro ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_lavoro_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_lavoro_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Lavoro', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import lavoro Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_lavoro_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_lavoro_feed_category" name="adnk_importer_options[selected_lavoro_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import lavoro ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_lavoro_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_lavoro_post_status" name="adnk_importer_options[selected_lavoro_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection sostenibilita ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_sostenibilita_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_sostenibilita_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Sostenibilita', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import sostenibilita Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_sostenibilita_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_sostenibilita_feed_category" name="adnk_importer_options[selected_sostenibilita_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import sostenibilita ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_sostenibilita_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_sostenibilita_post_status" name="adnk_importer_options[selected_sostenibilita_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection comunicati ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_comunicati_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_comunicati_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Comunicati', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import comunicati Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_comunicati_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_comunicati_feed_category" name="adnk_importer_options[selected_comunicati_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import comunicati ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_comunicati_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_comunicati_post_status" name="adnk_importer_options[selected_comunicati_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection motori ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_motori_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_motori_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Motori', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import motori Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_motori_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_motori_feed_category" name="adnk_importer_options[selected_motori_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import motori ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_motori_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_motori_post_status" name="adnk_importer_options[selected_motori_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection fintech ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_fintech_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_fintech_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Fintech', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import fintech Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_fintech_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_fintech_feed_category" name="adnk_importer_options[selected_fintech_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import fintech ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_fintech_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_fintech_post_status" name="adnk_importer_options[selected_fintech_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection tecnologia ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_tecnologia_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_tecnologia_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Tecnologia', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import tecnologia Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_tecnologia_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_tecnologia_feed_category" name="adnk_importer_options[selected_tecnologia_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import tecnologia ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_tecnologia_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_tecnologia_post_status" name="adnk_importer_options[selected_tecnologia_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection wine ?>
                        <tr valign="top">
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'url_wine_feed' ); ?>
                                <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_wine_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Wine', 'adnk_importer' ); ?>
                            </td>
                            <?php // Category to import wine Feed Posts ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_wine_feed_category' ); ?>
                                <select required <?php echo esc_attr($field_disabled) ?> id="selected_wine_feed_category" name="adnk_importer_options[selected_wine_feed_category]">
                                    <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                    <?php foreach($categories as $category){ ?>
                                        <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                            <?php echo esc_html( $category->name ); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </td>
                            <?php // Post status tu import wine ?>
                            <td>
                                <?php $value = self::get_adnk_importer_option( 'selected_wine_post_status' ); ?>
                                <select <?php echo esc_attr($field_disabled) ?> id="selected_wine_post_status" name="adnk_importer_options[selected_wine_post_status]">
                                    <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                    <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                </select>
                            </td>
                        </tr>
                        <?php // Feed URL selection video ?>
                        <tr valign="top" class="td-video" >
                            <td colspan="3">
                                <div class="adnk-video-container">
                                    <div class="adnk-video-disclaimer">
                                        I video vengono scaricati in locale sul tuo sito. Questo potrebbe portare ad un rapido consumo delle risorse (spazio e banda)
                                        del tuo servizio web.<br />
                                        <br />
                                        I video vengono inclusi nel contenuto dell'articolo tramite shortcode [video] come da standard WordPress. Per poter essere
                                        visualizzati E' NECESSARIO che il sito (tema o altri plugin) siano in grado di elaborare correttamente
                                        lo shortcode.
                                    </div>
                                    <div class="adnk-video-form">
                                        <div>
                                            <?php $value = self::get_adnk_importer_option( 'url_video_feed' ); ?>
                                            <input <?php echo esc_attr($field_disabled) ?> type="checkbox" name="adnk_importer_options[url_video_feed]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Video', 'adnk_importer' ); ?>
                                        </div>
                                        <div>
                                            <?php $value = self::get_adnk_importer_option( 'selected_video_feed_category' ); ?>
                                            <select required <?php echo esc_attr($field_disabled) ?> id="selected_video_feed_category" name="adnk_importer_options[selected_video_feed_category]">
                                                <option value=""><?php esc_html_e('---','adnk_importer') ?></option>
                                                <?php foreach($categories as $category){ ?>
                                                    <option value="<?php echo esc_attr( $category->term_id ); ?>" <?php selected( $category->term_id, $value ); ?>>
                                                        <?php echo esc_html( $category->name ); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div>
                                        <?php $value = self::get_adnk_importer_option( 'selected_video_post_status' ); ?>
                                        <select <?php echo esc_attr($field_disabled) ?> id="selected_video_post_status" name="adnk_importer_options[selected_video_post_status]">
                                            <option value="publish" <?php selected( 'publish', $value ); ?>><?php esc_html_e('Pubblicato','adnk_importer') ?></option>
                                            <option value="draft" <?php selected( 'draft', $value ); ?>><?php esc_html_e('Bozza','adnk_importer') ?></option>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                        </div>
                    </div>
                    <?php submit_button(); ?>

				    </div>
                    <div class="col-3">
                        <div class="card card-static border-primary mt-4">
                           <div class="card-body">
                               <p class="card-text">
	                               <?php $logo_tdm_url = plugin_dir_url(__FILE__)."img/logo-adnkronos.svg"?>
                                   <img width="200px" src="<?php echo esc_url($logo_tdm_url) ?>">
                                   <br /><br />
                                   <b>ROMA</b> Piazza Mastai n.9 - 00153<br />
                                   T: +39 06 5807666 <br /> F: +39 06 5807815<br />
                                   <br />
                                   <b>MILANO</b> Via Manin, 37 - 20121<br />
                                   T: +39 02 763661
                               </p>
                           </div>
                        </div>
                    </div>
                    </form>
                </div>
                </div><!-- tab-content -->



			</div><!-- .wrap -->
        </div>
		<?php }

	}
}
new adnk_importer_Options();

// Helper function to use in your theme to return a theme option value
function get_adnk_importer_option( $id = '' ) {
	return adnk_importer_Options::get_adnk_importer_option( $id );
}

add_action( 'admin_enqueue_scripts', 'ecctdm_enqueue_color_picker' );
function ecctdm_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_style( 'admin-style-adk', plugin_dir_url( __FILE__ ) . '/css/admin.css');

}

if ( ! class_exists( 'adnk_settings_Options' ) ) {

	class adnk_settings_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'adnk_settings_Options', 'add_settings_menu_page' ) );
				add_action( 'admin_init', array( 'adnk_settings_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all adnk_settings options
		 *
		 * @since 1.0.0
		 */
		public static function get_adnk_settings_options() {
			return get_option( 'adnk_settings_options' );
		}

		/**
		 * Returns single adnk_settings option
		 *
		 * @since 1.0.0
		 */
		public static function get_adnk_settings_option( $id ) {
			$options = self::get_adnk_settings_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_settings_menu_page() {
			add_submenu_page( 'adnk-plugin-settings', 'Impostazioni Account', 'Impostazioni Account', 'manage_options', 'adnk-plugin-account', array( 'adnk_settings_Options','adnk_plugin_func_accsettings') );
		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'adnk_settings_options', 'adnk_settings_options', array( 'adnk_settings_Options', 'sanitize_settings' ) );
		}

		public static function sanitize_settings( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

					// Checkbox consent_send_statistical_data
					if ( ! empty( $options['consent_send_statistical_data'] ) ) {
						$options['consent_send_statistical_data'] = 'on';
					} else {
						unset( $options['consent_send_statistical_data'] ); // Remove from options if not checked
					} 
				}

				// Return sanitized options
			return $options;
			}

					/* */
		public static function adnk_plugin_func_accsettings(){
			?>
            <div id="adk">
            <div class="container wrap">

                <?php $logo_tdm_url = plugin_dir_url(__FILE__)."img/logoadnkronos.jpg"?>
				<h1><img src="<?php echo esc_url($logo_tdm_url) ?>"><?php esc_html_e( 'AdnKronos Feed Importer Options', 'adnk_importer' ); ?></h1>
				<p></p>

				<nav class="nav-tab-wrapper">
					<a href="?page=adnk-plugin-settings" class="nav-tab "><?php esc_html_e('Impostazioni di Importazione','adnk_importer');?></a>
					<a href="?page=adnk-plugin-account" class="nav-tab nav-tab-active"><?php esc_html_e('Account Settings','adnk_importer');?></a>
				</nav>

				<div class="tab-content">
                    <div class="row">
                        <div class="col-9">
                            <form method="post" action="options.php">

                            <?php settings_fields( 'adnk_settings_options' ); ?>

                                <div class="card card-static mt-4">
                                    <div class="card-header"><strong><?php esc_html_e( 'Consenso invio dati statistici', 'adnk_importer' ); ?></strong></div>
                                    <div class="card-body">
                                        <p>
                                            Il sistema non raccoglie dati di navigazione degli utenti, siano essi visitatori del sito o gestori.<br />
                                            Le statistiche raccolte sono relative al numero di articoli importati e pubblicati per categoria, non rientrano pertanto
                                            nella categoria dei "dati sensibili" o "dati personali".<br />
                                            Il consenso è necessario per poter abilitare tutti i feed.
                                        </p>
                                        <?php $value = self::get_adnk_settings_option( 'consent_send_statistical_data' );?>
                                        <input type="checkbox" name="adnk_settings_options[consent_send_statistical_data]" <?php checked( $value, 'on' ); ?>> <?php esc_html_e( 'Acconsento all\'invio dei dati', 'adnk_importer' ); ?>
                                    </div>
                                </div>
                                <?php $regist_user = get_option('adn_site_active',0);
                                    if(!$regist_user){
                                ?>
                                <div class="card card-static mt-4">
                                    <div class="card-header"><strong><?php esc_html_e( 'Verifica il tuo dominio', 'adnk_importer' ); ?></strong></div>
                                    <div class="card-body">
                                        <p>
                                            Per verificare il tuo dominio <a target="_blank" href="https://plugin.adnkronos.com?domain=<?php echo urlencode(esc_url(get_site_url())) ?>">compila la richiesta cliccando qui registrando il tuo dominio <?php echo esc_url(get_site_url()); ?></a>!
                                        </p>
			                        </div>
                                </div>
                                <?php } ?>
                            <?php submit_button(); ?>
                            </form>
                        </div>
                        <div class="col-3">
                            <div class="card card-static border-primary mt-4">
                                <div class="card-body">
                                    <p class="card-text">
					                    <?php $logo_tdm_url = plugin_dir_url(__FILE__)."img/logo-adnkronos.svg"?>
                                        <img width="200px" src="<?php echo esc_url($logo_tdm_url) ?>">
                                        <br /><br />
                                        <b>ROMA</b> Piazza Mastai n.9 - 00153<br />
                                        T: +39 06 5807666 <br /> F: +39 06 5807815<br />
                                        <br />
                                        <b>MILANO</b> Via Manin, 37 - 20121<br />
                                        T: +39 02 763661
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>`
				</div>
			</div>
            </div>
			<?php 
		}


}
}

new adnk_settings_Options();

// Helper function to use in your theme to return a theme option value
function get_adnk_settings_option( $id = '' ) {
	return adnk_settings_Options::get_adnk_settings_option( $id );
}

/* admin notices */
function adnk_admin_notice(){
   
	//$site_url = get_home_url();
	$regist_user = get_option('adn_site_active',0);

	if ( $regist_user != true){
		$user = wp_get_current_user();
		if ( in_array( 'administrator', (array) $user->roles ) ) {
            echo '<div class="notice notice-warning is-dismissible">
                  <p>Adnkronos importer non è registrato <a href="/wp-admin/admin.php?page=adnk-plugin-account">Registra il tuo dominio</a></p>
                 </div>';
            }
	}
    
}
add_action('admin_notices', 'adnk_admin_notice');

