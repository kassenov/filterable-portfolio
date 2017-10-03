<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_Scripts' ) ):

	class Filterable_Portfolio_Scripts {

		private $plugin_name = 'filterable-portfolio';
		private $options;

		public function __construct( $options ) {
			$this->options = $options;

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ), 10 );
			add_action( 'wp_head', array( $this, 'inline_style' ), 30 );
		}

		public function admin_scripts() {
			global $post;
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_media();

			wp_enqueue_style(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/css/admin.css',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				'all'
			);
			wp_enqueue_script(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/js/admin/script.js',
				array(
					'jquery',
					'wp-color-picker',
					'jquery-ui-datepicker'
				),
				FILTERABLE_PORTFOLIO_VERSION,
				true );

			wp_localize_script( $this->plugin_name, 'FilterablePortfolio', array(
				'ajaxurl'           => admin_url( 'admin-ajax.php' ),
				'nonce'             => wp_create_nonce( 'fp_ajax_nonce' ),
				'post_id'           => $post ? $post->ID : '',
				'image_ids'         => $post ? get_post_meta( $post->ID, '_project_images', true ) : '',
				'create_btn_text'   => __( 'Create Gallery', 'filterable-portfolio' ),
				'edit_btn_text'     => __( 'Edit Gallery', 'filterable-portfolio' ),
				'progress_btn_text' => __( 'Saving...', 'filterable-portfolio' ),
				'save_btn_text'     => __( 'Save Gallery', 'filterable-portfolio' ),
			) );
		}

		public function frontend_scripts() {
			wp_register_script(
				'filterable-portfolio',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/script.js',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				true
			);

			wp_register_script(
				'isotope',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/isotope.pkgd.min.js',
				array(),
				'3.0.3',
				true
			);
			wp_register_script(
				'shuffle',
				FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/shuffle.min.js',
				array(),
				'4.0.2',
				true
			);

			if ( is_singular( 'portfolio' ) ) {
				wp_enqueue_script(
					'tiny-slider',
					FILTERABLE_PORTFOLIO_ASSETS . '/js/vendors/tiny-slider.min.js',
					array(),
					'2.2.0',
					true
				);

				wp_enqueue_script( 'filterable-portfolio' );
			}

			wp_enqueue_style(
				$this->plugin_name,
				FILTERABLE_PORTFOLIO_ASSETS . '/css/style.css',
				array(),
				FILTERABLE_PORTFOLIO_VERSION,
				'all'
			);
		}

		public function inline_style() {
			$btn_bg = ! empty( $this->options['button_color'] ) ? $this->options['button_color'] : '#4cc1be';
			?>
            <style type="text/css" id="filterable-portfolio-inline-style">
                .portfolio-terms {
                    border-bottom: 1px solid <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button {
                    border: 1px solid <?php echo $btn_bg; ?>;
                    color: <?php echo $btn_bg; ?>;
                }

                .portfolio-terms button.active,
                .portfolio-terms button:focus,
                .portfolio-terms button:hover {
                    border: 1px solid <?php echo $btn_bg; ?>;
                    background-color: <?php echo $btn_bg; ?>;
                }

                .portfolio-items .button {
                    background-color: <?php echo $btn_bg; ?>;
                }
            </style>
			<?php
		}
	}

endif;
