<?php
/**
 * Plugin Name: WP-Setting-API Demo
 * Description: WP-Setting-API 的使用示例插件
 * Author: 树新蜂团队
 * Author URI: https://github.com/TheTNB
 * Version: 1.0.1
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
defined( 'ABSPATH' ) or exit;
include_once 'class-setting.php';

use TheTNB\Setting as Setting;

$setting_api = new Setting;

$sections = array(
	array(
		'id'    => 'demo',
		'title' => __( '基础', 'demo' )
	),
	array(
		'id'    => 'demo2',
		'title' => __( '高级', 'demo' )
	),
	array(
		'id'          => 'demo3',
		'show_submit' => false,
		'title'       => __( '测试', 'demo' )
	)
);

$fields = array(
	'demo'  => array(
		array(
			'name'              => 'text_val',
			'label'             => __( 'Text Input', 'demo' ),
			'desc'              => __( 'Text input description', 'demo' ),
			'placeholder'       => __( 'Text Input placeholder', 'demo' ),
			'type'              => 'text',
			'default'           => 'Title',
			'sanitize_callback' => 'sanitize_text_field'
		),
		array(
			'name'              => 'number_input',
			'label'             => __( 'Number Input', 'demo' ),
			'desc'              => __( 'Number field with validation callback `floatval`', 'demo' ),
			'placeholder'       => __( '1.99', 'demo' ),
			'min'               => 0,
			'max'               => 100,
			'step'              => '0.01',
			'type'              => 'number',
			'default'           => 'Title',
			'sanitize_callback' => 'floatval'
		),
		array(
			'name'        => 'textarea',
			'label'       => __( 'Textarea Input', 'demo' ),
			'desc'        => __( 'Textarea description', 'demo' ),
			'placeholder' => __( 'Textarea placeholder', 'demo' ),
			'type'        => 'textarea'
		),
		array(
			'name'  => 'html',
			'label' => __( 'HTML Area', 'demo' ),
			'type'  => 'html',
			'html'  => '<h1>Hello World!</h1>'
		),
		array(
			'name'  => 'checkbox',
			'label' => __( 'Checkbox', 'demo' ),
			'desc'  => __( 'Checkbox Label', 'demo' ),
			'type'  => 'checkbox'
		),
		array(
			'name'    => 'radio',
			'label'   => __( 'Radio Button', 'demo' ),
			'desc'    => __( 'A radio button', 'demo' ),
			'type'    => 'radio',
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No'
			)
		),
		array(
			'name'    => 'selectbox',
			'label'   => __( 'A Dropdown', 'demo' ),
			'desc'    => __( 'Dropdown description', 'demo' ),
			'type'    => 'select',
			'default' => 'no',
			'options' => array(
				'yes' => 'Yes',
				'no'  => 'No'
			)
		),
		array(
			'name'    => 'password',
			'label'   => __( 'Password', 'demo' ),
			'desc'    => __( 'Password description', 'demo' ),
			'type'    => 'password',
			'default' => ''
		)
	),
	'demo2' => array(
		array(
			'name'    => 'file',
			'label'   => __( 'File', 'demo' ),
			'desc'    => __( 'File description', 'demo' ),
			'type'    => 'file',
			'default' => '',
			'options' => array(
				'button_label' => 'Choose Image'
			)
		),
		array(
			'name'    => 'color',
			'label'   => __( 'Color', 'demo' ),
			'desc'    => __( 'Color description', 'demo' ),
			'type'    => 'color',
			'default' => ''
		),
		array(
			'name'    => 'password',
			'label'   => __( 'Password', 'demo' ),
			'desc'    => __( 'Password description', 'demo' ),
			'type'    => 'password',
			'default' => ''
		),
		array(
			'name'    => 'wysiwyg',
			'label'   => __( 'Advanced Editor', 'demo' ),
			'desc'    => __( 'WP_Editor description', 'demo' ),
			'type'    => 'wysiwyg',
			'default' => ''
		),
		array(
			'name'    => 'multicheck',
			'label'   => __( 'Multile checkbox', 'demo' ),
			'desc'    => __( 'Multi checkbox description', 'demo' ),
			'type'    => 'multicheck',
			'default' => array( 'one' => 'one', 'four' => 'four' ),
			'options' => array(
				'one'   => 'One',
				'two'   => 'Two',
				'three' => 'Three',
				'four'  => 'Four'
			)
		),
	),
	'demo3' => array(
		array(
			'name'  => 'html',
			'label' => __( '无保存按钮', 'demo' ),
			'type'  => 'html',
			'html'  => '<h1>通过给页面设置show_submit = false属性，即可隐藏保存按钮</h1>'
		)
	)
);

/**
 * 挂载设置字段 Demo
 *
 * @return void
 */
function load_setting_fields() {
	global $setting_api;
	global $sections;
	global $fields;

	$setting_api->set_sections( $sections );
	$setting_api->set_fields( $fields );

	$setting_api->admin_init();
}

/**
 * 挂载设置页面 Demo
 */
function load_setting_page() {
	add_options_page( esc_html__( 'Demo', 'demo' ), esc_html__( 'Demo', 'demo' ), 'manage_options', 'demo', 'setting_page' );
	add_filter( 'plugin_action_links', function ( $links, $file ) {
		if ( 'wp-setting-api/wp-setting-api.php' !== $file ) {
			return $links;
		}
		$settings_link = '<a href="' . add_query_arg( array( 'page' => 'demo' ), admin_url( 'options-general.php' ) ) . '">' . esc_html__( '设置', 'demo' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}, 10, 2 );
}

/**
 * 设置页面 Demo
 */
function setting_page() {
	echo '<h1>WP-Setting-API Demo</h1><span style="float: right;">By: WordPressCN@耗子</span>';
	echo '<div class="wrap">';

	global $setting_api;
	$setting_api->show_navigation();
	$setting_api->show_forms();

	echo '</div>';
}

add_action( 'admin_init', 'load_setting_fields' );
add_action( 'admin_menu', 'load_setting_page' );

/**
 * 获取设置字段的值 Demo
 */
$demo_value['text_val']  = $setting_api->get_option( 'text_val', 'demo', '' );
$demo_value['textarea']  = $setting_api->get_option( 'textarea', 'demo', '' );
$demo_value['selectbox'] = $setting_api->get_option( 'selectbox', 'demo', '' );
