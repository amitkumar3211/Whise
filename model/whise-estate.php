<?php

namespace wp_whise\model;

use wp_whise\config\cpt\Estate_Cpt_Config;
use wp_whise\controller\log\Log_Controller_Interface;
use wp_whise\lib\helper;

class Whise_Estate {

	/**
	 * Project ID
	 *
	 * @var int
	 *
	 * @since 1.0.0
	 */
	public $post_id;

	/**
	 * Post meta from existing Project
	 * This variable is used to skip updating post meta (expensive query call)
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $post_meta;

	/**
	 * Term ID for category
	 *
	 * @var int
	 *
	 * @since 1.0.0
	 */
	public $category_id;

	/**
	 * Term ID for subcategory
	 *
	 * @var int
	 *
	 * @since 1.0.0
	 */
	public $subcategory_id;

	/**
	 * Logger
	 *
	 * @var Log_Controller_Interface
	 *
	 * @since 1.0.0
	 */
	private $log;

	/**
	 * Set logger
	 *
	 * @param Log_Controller_Interface $log
	 *
	 * @since 1.0.0
	 */
	public function set_logger( Log_Controller_Interface $log ) {
		$this->log = $log;
	}

	/**
	 * Returns Estate ID
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function get_estate_id() {
		return $this->EstateID;
	}

	/**
	 * Returns true if the Estate ID exists in the database
	 *
	 * @since 1.0.0
	 */
	public function does_post_exist() {
		$response = false;

		global $wpdb;

		$query  = "select * from $wpdb->postmeta where meta_key = '_estate_id' AND meta_value='" . $this->get_estate_id() . "'";
		$result = $wpdb->get_results( $query );

		if ( is_array( $result ) && sizeof( $result ) == 1 ) {
			$response = $result[0]->post_id;
		}

		return $response;
	}

	/**
	 * Create new Estate post
	 *
	 * @return mixed
	 *
	 * @since 1.0.0
	 */
	public function create_wp_post() {
		$post_arr = array(
			'post_title'   => $this->Name,
			'post_content' => $this->LongDescription,
			'post_excerpt' => $this->ShortDescription,
			'post_status'  => 'draft',
			'post_type'    => Estate_Cpt_Config::POST_TYPE
		);

		$this->post_id = wp_insert_post( $post_arr );

		$this->set_taxonomy();

		$this->update_post_metas();

		$this->set_gallery_images();

		$this->set_documents();

		return $this->post_id;
	}

	/**
	 * Update Estate post
	 *
	 * @param $post_id  Post ID
	 *
	 * @return  mixed
	 *
	 * @since 1.0.0
	 */
	public function update_wp_post( $post_id ) {
		$this->post_id = $post_id;

		$post_arr = array(
			'ID'           => $post_id,
			'post_title'   => $this->Name,
			'post_content' => $this->LongDescription,
			'post_excerpt' => $this->ShortDescription
		);

		wp_update_post( $post_arr );

		$this->post_meta = get_post_meta( $this->post_id );

		$this->set_taxonomy();

		$this->update_post_metas();

		$this->set_gallery_images();

		$this->set_documents();

		return $this->post_id;
	}

	/**
	 * Set Estate Category taxonomy
	 *
	 * @since 1.0.0
	 */
	protected function set_taxonomy() {
		$terms = array();

		if ( $this->category_id !== false ) {
			$terms[] = $this->category_id;
		}

		if ( $this->subcategory_id !== false ) {
			$terms[] = $this->subcategory_id;
		}

		wp_set_object_terms( $this->post_id, $terms, 'estate-category' );
	}

	/**
	 * Update Post meta
	 *
	 * @TODO Find better way to update the database table!
	 *
	 * @since 1.0.0
	 */
	protected function update_post_metas() {
		$this->update_meta( '_price', $this->Price );
		$this->update_meta( '_address', $this->Address1 );
		$this->update_meta( '_address_2', $this->Address2 );
		$this->update_meta( '_number', $this->Number );
		$this->update_meta( '_box', $this->Box );
		$this->update_meta( '_city', $this->City );
		$this->update_meta( '_zip', $this->Zip );
		$this->update_meta( '_country', $this->Country );
		$this->update_meta( '_estate_id', $this->EstateID );
		$this->update_meta( '_area', $this->Area );
		$this->update_meta( '_ground_area', $this->GroundArea );
		$this->update_meta( '_office', $this->Office );
		$this->update_meta( '_client', $this->Client );
		$this->update_meta( '_currency', $this->Currency );
		$this->update_meta( '_created', helper::get_date_time( $this->CreateDateTime ) );
		$this->update_meta( '_updated', helper::get_date_time( $this->UpdateDateTime ) );
		$this->update_meta( '_price_updated', helper::get_date_time( $this->PriceChangeDateTime ) );
		$this->update_meta( '_availability', $this->Availability );
		$this->update_meta( '_status', $this->Status );
		$this->update_meta( '_reference_number', $this->ReferenceNumber );
		$this->update_meta( '_sold_rent_date', helper::get_date_time( $this->SoldRentDate ) );
		$this->update_meta( '_put_online_datetime', helper::get_date_time( $this->PutOnlineDateTime ) );
		$this->update_meta( '_energy_class', $this->EnergyClass );
		$this->update_meta( '_energy_value', $this->EnergyValue );
		$this->update_meta( '_floor', $this->Floor );
		$this->update_meta( '_investment_estate', $this->InvestmentEstate );
		$this->update_meta( '_fronts', $this->Fronts );
		$this->update_meta( '_terrace', $this->Terrace );
		$this->update_meta( '_garage', $this->Garage );
		$this->update_meta( '_parking', $this->Parking );
		$this->update_meta( '_bathrooms', $this->BathRooms );
		$this->update_meta( '_furnished', $this->Furnished );
		$this->update_meta( '_garden', $this->Garden );
		$this->update_meta( '_garden_area', $this->GardenArea );
		$this->update_meta( '_availability_datetime', helper::get_date_time( $this->AvailabilityDateTime ) );
		$this->update_meta( '_parent_id', $this->ParentID );
	}

	/**
	 * Update Post Meta
	 *
	 * This will be done if the new value is different than old value
	 * Or if the meta doesn't exist.
	 *
	 * @param $db_key       string          The database meta key
	 * @param $new_value    string|array    The Whise meta value
	 *
	 * @since 1.0.0
	 */
	protected function update_meta( $db_key, $new_value ) {
		/**
		 * Check if meta key exist and if the old value is the same with new value
		 * else update post meta
		 */
		$old_value = ( isset( $this->post_meta[ $db_key ][0] ) ) ? maybe_unserialize( $this->post_meta[ $db_key ][0] ) : false;

		if ( $old_value !== false && $old_value != $new_value ) {
			update_post_meta( $this->post_id, $db_key, $new_value );
		} elseif ( $old_value === false && ! empty( $new_value ) ) {
			/**
			 * If the meta key doesn't exist
			 * then create post meta
			 */
			add_post_meta( $this->post_id, $db_key, $new_value );
		}
	}

	/**
	 * Updates gallery images
	 *
	 * Uploads if image doesn't exist
	 *
	 * @since 1.0.0
	 */
	protected function set_gallery_images() {
		if ( isset( $this->Pictures ) && is_array( $this->Pictures ) ) {
			$gallery_ids = array();

			foreach (helper::generator( $this->Pictures ) as $picture ) {
				$split     = explode( '/', $picture->UrlXXL );
				$file_name = end( $split );

				if ( ! $gallery_id = helper::does_attachment_exist( $file_name ) ) {
					$this->log->info( $file_name . ' does not exist, so we are uploading the image.' );
					$gallery_ids[] = helper::upload_image_to_wordpress( $file_name, $picture->UrlXXL, $this->post_id );
				} else {
					$this->log->info( $file_name . ' does exist, so we are not uploading the image again.' );
					$gallery_ids[] = $gallery_id;
				}
			}

			$this->log->debug( $gallery_ids );

			$this->update_meta( '_gallery_image_ids', $gallery_ids );
		}
	}

	/**
	 * Updates gallery images
	 *
	 * Uploads if image doesn't exist
	 *
	 * @since 1.0.0
	 */
	protected function set_documents() {
		if ( isset( $this->Documents ) && is_array( $this->Documents ) ) {
			$document_ids = array();

			foreach (helper::generator( $this->Documents ) as $document ) {
				$split     = explode( '/', $document->Url );
				$file_name = end( $split );

				if ( ! $gallery_id = helper::does_attachment_exist( $file_name ) ) {
					$this->log->info( $file_name . ' does not exist, so we are uploading the document.' );
					$document_ids[] = helper::upload_image_to_wordpress( $file_name, $document->Url, $this->post_id );
				} else {
					$this->log->info( $file_name . ' does exist, so we are not uploading the document again.' );
					$document_ids[] = $gallery_id;
				}
			}

			$this->log->debug( $document_ids );

			$this->update_meta( '_document_ids', $document_ids );
		}
	}
}

/**
 *  public $__type =>
 * string(54) "EstateServiceGetEstateListResponseEstate:Whoman.Estate"
 * public $EstateID =>
 * int(3492787)
 * public $Price =>
 * string(9) "888888.00"
 * public $Comments =>
 * string(24) "zuidwest, 2de west, open"
 * public $ParentID =>
 * int(3492295)
 * public $Name =>
 * string(6) "LOT 30"
 * public $Address1 =>
 * string(13) "Verloren Eind"
 * public $Address2 =>
 * NULL
 * public $Number =>
 * NULL
 * public $Box =>
 * NULL
 * public $Zip =>
 * string(4) "3940"
 * public $City =>
 * string(13) "Hechtel-Eksel"
 * public $Country =>
 * string(7) "België"
 * public $Pictures =>
 * array(12) {
 * [0] =>
 * class stdClass#7080 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/0e67bf7bcf9941ec833ad2ae84992fdc.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/0e67bf7bcf9941ec833ad2ae84992fdc.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(1)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/0e67bf7bcf9941ec833ad2ae84992fdc.jpg"
 * public $PictureId =>
 * int(18166875)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [1] =>
 * class stdClass#9794 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/84996ce7e97f4b1585e4e8733a1c3709.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/84996ce7e97f4b1585e4e8733a1c3709.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(2)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/84996ce7e97f4b1585e4e8733a1c3709.jpg"
 * public $PictureId =>
 * int(18166876)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [2] =>
 * class stdClass#7082 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/6b70c6861e0147b59c7418ba0b0c0171.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/6b70c6861e0147b59c7418ba0b0c0171.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(3)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/6b70c6861e0147b59c7418ba0b0c0171.jpg"
 * public $PictureId =>
 * int(18166877)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [3] =>
 * class stdClass#7083 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/bc804e1d6cc94f36842d22bebc412a4c.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/bc804e1d6cc94f36842d22bebc412a4c.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(4)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/bc804e1d6cc94f36842d22bebc412a4c.jpg"
 * public $PictureId =>
 * int(18166878)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [4] =>
 * class stdClass#7084 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/e000c88292754a80a0464b54e37092f4.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/e000c88292754a80a0464b54e37092f4.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(5)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/e000c88292754a80a0464b54e37092f4.jpg"
 * public $PictureId =>
 * int(18166879)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [5] =>
 * class stdClass#7085 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/ea06c80ff21a4947ac7e411535e0fa1d.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/ea06c80ff21a4947ac7e411535e0fa1d.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(6)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/ea06c80ff21a4947ac7e411535e0fa1d.jpg"
 * public $PictureId =>
 * int(18166880)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [6] =>
 * class stdClass#9806 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/6c0d9f0f330e49bb8748c6cb5483d550.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/6c0d9f0f330e49bb8748c6cb5483d550.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(7)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/6c0d9f0f330e49bb8748c6cb5483d550.jpg"
 * public $PictureId =>
 * int(18166881)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [7] =>
 * class stdClass#9805 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/5e6982b666a74066b13d7a9185de26d2.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/5e6982b666a74066b13d7a9185de26d2.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(8)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/5e6982b666a74066b13d7a9185de26d2.jpg"
 * public $PictureId =>
 * int(18166882)
 * public $Orientation =>
 * string(8) "Portrait"
 * }
 * [8] =>
 * class stdClass#9804 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/94bb9b1ae2e6423ab0e557d52b803903.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/94bb9b1ae2e6423ab0e557d52b803903.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(9)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/94bb9b1ae2e6423ab0e557d52b803903.jpg"
 * public $PictureId =>
 * int(18166883)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [9] =>
 * class stdClass#9803 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/46cad51b59aa4e6b9cca7b2662b27155.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/46cad51b59aa4e6b9cca7b2662b27155.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(10)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/46cad51b59aa4e6b9cca7b2662b27155.jpg"
 * public $PictureId =>
 * int(18166884)
 * public $Orientation =>
 * string(8) "Portrait"
 * }
 * [10] =>
 * class stdClass#9802 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/02e7d6a10f29473db8dc2c582ecf90dd.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/02e7d6a10f29473db8dc2c582ecf90dd.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(11)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/02e7d6a10f29473db8dc2c582ecf90dd.jpg"
 * public $PictureId =>
 * int(18166885)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * [11] =>
 * class stdClass#9801 (9) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePicture:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $UrlLarge =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/640/061441f26ac743fc9af9025b9da32db2.jpg"
 * public $UrlSmall =>
 * string(113) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/200/061441f26ac743fc9af9025b9da32db2.jpg"
 * public $DetailId =>
 * NULL
 * public $Order =>
 * int(12)
 * public $UrlXXL =>
 * string(114) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Pictures/3492787/1600/061441f26ac743fc9af9025b9da32db2.jpg"
 * public $PictureId =>
 * int(18166886)
 * public $Orientation =>
 * string(9) "Landscape"
 * }
 * }
 * public $Category =>
 * string(5) "grond"
 * public $SubCategory =>
 * string(9) "bouwgrond"
 * public $Purposes =>
 * array(1) {
 * [0] =>
 * class stdClass#9800 (5) {
 * public $__type =>
 * string(55) "EstateServiceGetEstateListResponsePurpose:Whoman.Estate"
 * public $PurposeStatus =>
 * string(8) "verkocht"
 * public $Name =>
 * string(7) "te koop"
 * public $PurposeId =>
 * int(1)
 * public $PurposeStatusId =>
 * int(3)
 * }
 * }
 * public $Sms =>
 * string(61) "!!! VERKOCHT !!!...
 *
 * Er zijn echter nog andere loten te koop."
 * public $CanHaveChildren =>
 * bool(false)
 * public $Evaluation =>
 * NULL
 * public $Link3DModel =>
 * NULL
 * public $LinkVirtualVisit =>
 * NULL
 * public $Office =>
 * string(10) "nv COMPASS"
 * public $Client =>
 * string(30) "NV Compass Projectontwikkeling"
 * public $Currency =>
 * string(3) "€"
 * public $CreateDateTime =>
 * string(26) "/Date(1529933059967+0200)/"
 * public $UpdateDateTime =>
 * string(26) "/Date(1529933154593+0200)/"
 * public $PriceChangeDateTime =>
 * string(26) "/Date(1529933059967+0200)/"
 * public $Area =>
 * NULL
 * public $GroundArea =>
 * double(539.14)
 * public $Documents =>
 * array(3) {
 * [0] =>
 * class stdClass#9799 (6) {
 * public $__type =>
 * string(56) "EstateServiceGetEstateListResponseDocument:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $Url =>
 * string(201) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Utility/Handlers/GetFile.ashx?estateID=3492787&path=Documents/2f566df7feb74642a8c579509700f85f-_2017-12-08-vergunn-wijziging-1-verkav-besluit.pdf"
 * public $LanguageID =>
 * NULL
 * public $BaseDocumentTypeId =>
 * NULL
 * public $BaseDocumentType =>
 * string(0) ""
 * }
 * [1] =>
 * class stdClass#9403 (6) {
 * public $__type =>
 * string(56) "EstateServiceGetEstateListResponseDocument:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $Url =>
 * string(202) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Utility/Handlers/GetFile.ashx?estateID=3492787&path=Documents/81d281fd339f4c6aa33fa9bb4cb29506-_2017-12-08-vergunn-wijziging-1-verkav-voorschr.pdf"
 * public $LanguageID =>
 * NULL
 * public $BaseDocumentTypeId =>
 * NULL
 * public $BaseDocumentType =>
 * string(0) ""
 * }
 * [2] =>
 * class stdClass#9797 (6) {
 * public $__type =>
 * string(56) "EstateServiceGetEstateListResponseDocument:Whoman.Estate"
 * public $Description =>
 * string(0) ""
 * public $Url =>
 * string(225) "http://nvcompassprojectontwikkeling.websites.whoman2.be/Utility/Handlers/GetFile.ashx?estateID=3492787&path=Documents/2710c774d34c4c52973ef4e05b2d7e8b-_2017-12-08-vergunn-wijziging-1-verkav-dl-3v9-plan-verkav-m-correcties.pdf"
 * public $LanguageID =>
 * NULL
 * public $BaseDocumentTypeId =>
 * NULL
 * public $BaseDocumentType =>
 * string(0) ""
 * }
 * }
 * public $Rooms =>
 * NULL
 * public $Details =>
 * array(0) {
 * }
 * public $Availability =>
 * string(10) "vanaf akte"
 * public $Status =>
 * string(6) "actief"
 * public $State =>
 * string(30) "terrein klaar voor constructie"
 * public $Regions =>
 * NULL
 * public $LinkRouteDesc =>
 * bool(true)
 * public $EstateOrder =>
 * int(1)
 * public $Floor =>
 * NULL
 * public $ReferenceNumber =>
 * string(7) "3431541"
 * public $InvestmentEstate =>
 * bool(false)
 * public $Fronts =>
 * NULL
 * public $Terrace =>
 * NULL
 * public $Garage =>
 * NULL
 * public $Parking =>
 * NULL
 * public $BathRooms =>
 * NULL
 * public $Furnished =>
 * NULL
 * public $Garden =>
 * NULL
 * public $GardenArea =>
 * NULL
 * public $AvailabilityDateTime =>
 * NULL
 * public $ShortDescription =>
 * string(71) "!!! VERKOCHT !!!...<br /><br />Er zijn echter nog andere loten te koop."
 * public $LongDescription =>
 * string(71) "!!! VERKOCHT !!!...<br /><br />Er zijn echter nog andere loten te koop."
 * public $PublicationText =>
 * string(0) ""
 * public $MinArea =>
 * NULL
 * public $MaxArea =>
 * NULL
 * public $ClientId =>
 * int(2465)
 * public $OfficeId =>
 * int(4191)
 * public $CategoryId =>
 * int(3)
 * public $CountryId =>
 * int(1)
 * public $Representative =>
 * NULL
 * public $DisplayAddress =>
 * bool(true)
 * public $DisplayPrice =>
 * bool(true)
 * public $DisplayStatusId =>
 * array(1) {
 * [0] =>
 * int(2)
 * }
 * public $IsTypeEstate =>
 * bool(false)
 * public $SoldRentDate =>
 * string(26) "/Date(1529933154073+0200)/"
 * public $CompromisDate =>
 * NULL
 * public $AnnuityMonthly =>
 * NULL
 * public $RepresentativeList =>
 * NULL
 * public $PutOnlineDateTime =>
 * string(26) "/Date(1529933059500+0200)/"
 * public $SubCategoryId =>
 * int(26)
 * public $StatusId =>
 * int(1)
 * public $EnergyClass =>
 * NULL
 * public $EnergyValue =>
 * NULL
 * public $ContractTypeId =>
 * NULL
 * public $ContractType =>
 * string(0) ""
 */