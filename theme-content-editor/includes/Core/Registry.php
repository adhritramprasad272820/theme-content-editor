<?php

namespace ThemeContentEditor\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Registry {
	private $sections = array();
	private $fields   = array();

	public function register_section( $key, $label ) {
		$this->sections[ $key ] = $label;
	}

	public function register_field( array $config ) {
		if ( empty( $config['key'] ) || empty( $config['type'] ) || empty( $config['section'] ) ) {
			return;
		}
		$this->fields[ $config['key'] ] = wp_parse_args(
			$config,
			array(
				'label'   => $config['key'],
				'default' => '',
			)
		);
	}

	public function get_sections() {
		return $this->sections;
	}

	public function get_fields() {
		return $this->fields;
	}

	public function get_fields_by_section( $section ) {
		return array_filter(
			$this->fields,
			function ( $field ) use ( $section ) {
				return $field['section'] === $section;
			}
		);
	}

	public function register_default_fields() {
		$this->register_section( 'general', __( 'General Content', 'theme-content-editor' ) );
		$this->register_section( 'homepage', __( 'Homepage', 'theme-content-editor' ) );
		$this->register_section( 'projects', __( 'Projects', 'theme-content-editor' ) );
		$this->register_section( 'gallery', __( 'Gallery', 'theme-content-editor' ) );
		$this->register_section( 'pdfs', __( 'PDFs / Downloads', 'theme-content-editor' ) );
		$this->register_section( 'contact', __( 'Contact Info', 'theme-content-editor' ) );

		$this->register_field(
			array(
				'key'     => 'site_tagline',
				'label'   => __( 'Site Tagline', 'theme-content-editor' ),
				'type'    => 'text',
				'section' => 'general',
			)
		);
		$this->register_field(
			array(
				'key'     => 'hero_title',
				'label'   => __( 'Hero Title', 'theme-content-editor' ),
				'type'    => 'rich_text',
				'section' => 'homepage',
			)
		);
		$this->register_field(
			array(
				'key'     => 'hero_background',
				'label'   => __( 'Hero Background Image', 'theme-content-editor' ),
				'type'    => 'background_image',
				'section' => 'homepage',
			)
		);
		$this->register_field(
			array(
				'key'     => 'cta_button_text',
				'label'   => __( 'CTA Button Text', 'theme-content-editor' ),
				'type'    => 'button_text',
				'section' => 'homepage',
			),
		);
		$this->register_field(
			array(
				'key'     => 'cta_button_link',
				'label'   => __( 'CTA Button Link', 'theme-content-editor' ),
				'type'    => 'button_link',
				'section' => 'homepage',
			),
		);
		$this->register_field(
			array(
				'key'     => 'company_brochure_pdf',
				'label'   => __( 'Company Brochure PDF', 'theme-content-editor' ),
				'type'    => 'file',
				'section' => 'pdfs',
			),
		);
		$this->register_field(
			array(
				'key'     => 'projects_repeater',
				'label'   => __( 'Project Cards', 'theme-content-editor' ),
				'type'    => 'repeater',
				'section' => 'projects',
			),
		);
	}
}
