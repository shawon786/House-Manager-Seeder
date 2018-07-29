<?php

/*
 * Plugin Name:       HM Dummy Data Generator
 * Plugin URI:        http://house-manager.com
 * Description:       Seeder for house manager
 * Version:           1.0.0
 * Author:            Shawon Chowdhury
 * Author URI:        shawon-chowdhury.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       hm-seeder
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'HM_SEEDER', '1.0.0' );

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Insert dummy flats data into database
 * @param  int $limit
 * @return void
 */
function seed_flats($limit) {
	global $wpdb;

	$faker = Faker\Factory::create();

	$wpdb->get_results('TRUNCATE ' . $wpdb->prefix . 'hm_flats');

	for ($i = 0; $i < $limit; $i++) {
		$wpdb->insert(
			$wpdb->prefix . 'hm_flats',
			array(
				'flat_no'  => 'flat_' . $faker->hexcolor,
				'rent'     => $faker->numberBetween(5000, 20000),
				'desc'     => $faker->text(200),
				'meter_no' => 'meter_' . $faker->hexcolor,
				'note'     => $faker->sentence(),
			)
		);
	}
}

/**
 * Insert dummy renters data into database
 * @param  int $limit
 * @return void
 */
function seed_renters($limit) {
	global $wpdb;

	$faker = Faker\Factory::create();

	$wpdb->get_results('TRUNCATE ' . $wpdb->prefix . 'hm_renters');

	for ($i = 0; $i < $limit; $i++) {
		$wpdb->insert(
			$wpdb->prefix . 'hm_renters',
            array(
                'flat_id'         => $i + 1,
                'first_name'      => $faker->titleMale,
                'middle_name'     => $faker->firstNameFemale,
                'last_name'       => $faker->lastName,
                'passport'        => strtoupper($faker->randomLetter) . $faker->randomNumber(2) . $faker->randomNumber(5),
                'nid'             => $faker->randomNumber(4) . $faker->randomNumber(4) . $faker->randomNumber(5),
                'driving_license' => strtoupper($faker->randomLetter) . $faker->randomNumber(5) . $faker->randomNumber(5) . $faker->randomNumber(5),
                'occupation'      => $faker->word,
                'father'          => $faker->firstNameMale . ' ' . $faker->lastName,
                'mother'          => $faker->firstNameFemale . ' ' . $faker->lastName,
                'address'         => $faker->firstNameMale . ' ' . $faker->lastName . ', ' . $faker->streetAddress . ', ' . $faker->state . ', ' . $faker->country,
                'mobile'          => $faker->e164PhoneNumber,
                'phone'           => $faker->tollFreePhoneNumber,
                'marital'         => $faker->randomElement(['single', 'married', 'widowed', 'divorced', 'separated']),
                'spouse'          => $faker->firstNameFemale . ' ' . $faker->lastName,
                'children'        => $faker->numberBetween(1, 5),
                'members'         => $faker->numberBetween(2, 8),
                'advance'         => $faker->numberBetween(5000, 8000),
                'agreement'       => $faker->numberBetween(1, 3),
                'rent'            => $faker->numberBetween(10000, 25000),
                'note'            => $faker->sentence()
            )
		);
	}
}

/**
 * Insert dummy invoices data into database
 * @param  int $limit
 * @return void
 */
function seed_invoices($limit) {
	global $wpdb;

	$faker = Faker\Factory::create();

	$wpdb->get_results('TRUNCATE ' . $wpdb->prefix . 'hm_invoices');
	$wpdb->get_results('TRUNCATE ' . $wpdb->prefix . 'hm_invoice_items');

	for ($i = 0; $i < $limit; $i++) {
		$wpdb->insert(
			$wpdb->prefix . 'hm_invoices',
            array(
                'flat_id' => $i + 1,
                'purpose' => $faker->randomElement(['advance', 'bills', 'monthly_rent']),
                'date'    => $faker->date()
            )
		);

		$id = $wpdb->insert_id;

		for ($j = 1; $j < 5; $j++) {
			$wpdb->insert(
				$wpdb->prefix . 'hm_invoice_items',
				array(
					'invoice_id' => $id,
					'bill_for'   => $faker->sentence(3),
					'desc'       => $faker->sentence(6),
					'amount'     => $faker->numberBetween(500, 4000)
				)
			);
		}
	}
}

function seeds() {
	seed_flats(31);
	seed_renters(31);
	seed_invoices(31);
}

add_action( 'hm-loaded', 'seeds' );
