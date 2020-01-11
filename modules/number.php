<?php

class KintoneFormNumber {

	/**
	 * Get instance
	 */
	public static function get_instance() {
		/**
		 * A variable that keeps the sole instance.
		 */
		static $instance;

		if ( ! isset( $instance ) ) {
			$instance = new KintoneFormNumber();
		}

		return $instance;
	}

	/**
	 * Format for kintone data.
	 *
	 * @param array    $kintone_form_data .
	 * @param array    $cf7_send_data .
	 * @param string   $cf7_mail_tag .
	 * @param WP_Error $e .
	 *
	 * @return array An array of image URLs.
	 */
	public static function format_to_kintone_data( $kintone_form_data, $cf7_send_data, $cf7_mail_tag, $e ) {

		$return_data = array();

		$value = '';
		if ( in_array( $cf7_mail_tag, Kintone_Form::CF7_SPECAIL_TAGS, true ) ) {
			$value = apply_filters( 'wpcf7_special_mail_tags', '', $cf7_mail_tag, false );
		} else {
			if ( isset( $cf7_send_data[ $cf7_mail_tag ] ) ) {
				$value = $cf7_send_data[ $cf7_mail_tag ];
			}
		}

		if ( is_array( $value ) ) {
			$value = $value[0];
		}

		if ( ! empty( $value ) && ! is_numeric( $value ) ) {
			$e->add( 'Error', $cf7_mail_tag . '->' . $kintone_form_data['code'] . ' : Numeric format error' );
		}

		if ( ! empty( $kintone_form_data['minValue'] ) ) {

			if ( $kintone_form_data['minValue'] > $value ) {
				$e->add( 'Error', $cf7_mail_tag . '->' . $kintone_form_data['code'] . ' : Minimum value error' );
			}
		}

		if ( ! empty( $kintone_form_data['maxValue'] ) ) {

			if ( $kintone_form_data['maxValue'] < $value ) {
				$e->add( 'Error', $cf7_mail_tag . '->' . $kintone_form_data['code'] . ' : Maximum value error' );
			}
		}

		$return_data['value'] = mb_convert_kana( $value, "n", "utf-8" );;

		return $return_data;

	}


}


