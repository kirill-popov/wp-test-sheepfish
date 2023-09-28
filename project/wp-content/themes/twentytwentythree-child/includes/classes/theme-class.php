<?php

namespace SheepFish\classes;

use Exception;

class SheepFishTheme
{
    private $styles = [];
    private $scripts = [];
    private $script_vars = [];
    protected $actions = [];
    protected $filters = [];

    public function __construct() {
        $this->setup();
        $this->init_styles();
        $this->init_scripts();
    }

    private function setup(): void
    {
        $this->add_post_type('Customer Orders');
        $this->add_action('acf/include_fields', [$this, 'add_order_acf_fields']);

        $this->add_action('after_switch_theme', [$this, 'switch_theme_setup']);

        $this->add_ajax_handler('customer_form_submit', function() {
            $this->customer_form_submit();
        });
    }

    public function add_order_acf_fields() {
        if (!function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_6515582d760c7',
            'title' => 'Order Settings',
            'fields' => array(
                array(
                    'key' => 'field_6515582ee0c20',
                    'label' => 'User',
                    'name' => 'user',
                    'aria-label' => '',
                    'type' => 'user',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'role' => array(
                        0 => 'customer',
                    ),
                    'return_format' => 'id',
                    'multiple' => 0,
                    'allow_null' => 0,
                    'bidirectional' => 0,
                    'bidirectional_target' => array(
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'customer-orders',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    }

    private function add_post_type(string $type) {
        $args = array(
            'labels' => [
                'name' => $type,
            ],
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => sanitize_title($type) ),
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail'),
        );

        register_post_type( sanitize_title($type), $args );
    }

    public function switch_theme_setup(): void
    {
        $front_page = array(
            'post_type' => 'page',
            'post_title'    => 'Front Page Title',
            'post_content'  => $this->front_page_content(),
            'post_status'   => 'publish',
            'post_author'   => 1
        );

        $post_id = wp_insert_post($front_page);
        if (!is_wp_error($post_id)) {
            update_option( 'page_on_front', $post_id );
            update_option( 'show_on_front', 'page' );
        } else {
            echo $post_id->get_error_message();
        }
    }

    private function init_styles(): void
    {
        $this->add_action('wp_enqueue_scripts', [$this, 'load_styles']);
    }

    public function load_styles(): void
    {
        foreach ($this->styles as $style) {
            wp_enqueue_style($style['name'], $style['file'], $style['deps'], $style['ver'], $style['media']);
        }
    }

    public function add_style(string $name, string $file, array $deps=[], string|bool|null $ver = false, string $media = 'all'): SheepFishTheme
    {
        $this->styles[] = [
            'name'  => $name,
            'file'  => $file,
            'deps'  => $deps,
            'ver'   => $ver,
            'media' => $media
        ];

        return $this;
    }


    private function init_scripts(): void
    {
        $this->add_action('wp_enqueue_scripts', [$this, 'load_scripts']);
    }

    public function load_scripts(): void
    {
        foreach ($this->scripts as $script) {
            wp_enqueue_script($script['name'], $script['file'], $script['deps'], $script['ver'], $script['args']);
        }

        foreach ($this->script_vars as $vars) {
            wp_localize_script($vars['handle'], $vars['object_name'], $vars['data']);
        }
    }

    public function add_script(string $name, string $file, array $deps=[], string|bool|null $ver = false, array $args=[]): SheepFishTheme
    {
        $this->scripts[] = [
            'name'  => $name,
            'file'  => $file,
            'deps'  => $deps,
            'ver'   => $ver,
            'args'  => $args
        ];

        return $this;
    }

    public function add_script_vars(string $handle, string $object_name, array $data=[]): SheepFishTheme
    {
        $this->script_vars[] = [
            'handle'        => $handle,
            'object_name'   => $object_name,
            'data'          => $data
        ];

        return $this;
    }

    public function add_user_role(string $role_title=''): SheepFishTheme
    {
        if (!empty($role)) {
            add_role(
                sanitize_title($role_title), $role_title,
                array(
                    'read' => true,
                    'create_posts' => true,
                    'edit_posts' => true,
                    'edit_others_posts' => false,
                    'publish_posts' => true,
                    'manage_categories' => true,
                )
            );
        }
        return $this;
    }

    public function add_action($hook, $callback, $priority = 10, $accepted_args = 1) {
		$this->actions = $this->add($this->actions, $hook, $callback, $priority, $accepted_args);
	}

	public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1) {
		$this->filters = $this->add($this->filters, $hook, $callback, $priority, $accepted_args);
	}

	private function add($hooks, $hook, $callback, $priority, $accepted_args) {
		$hooks[] = array(
			'hook'          => $hook,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;
	}

    private function add_ajax_handler(
        string $name,
        callable $callable,
        bool $authenticated_only=false,
    ): SheepFishTheme
    {
        $name = sanitize_title(trim($name));
        if (!empty($name)) {
            $this->add_action('wp_ajax_'.$name, $callable);
            if (!$authenticated_only) {
                $this->add_action('wp_ajax_nopriv_'.$name, $callable);
            }
        }
        return $this;
    }

    public function run() {
        foreach ($this->filters as $hook) {
            add_filter($hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args']);
        }

        foreach ($this->actions as $hook) {
            add_action($hook['hook'], $hook['callback'], $hook['priority'], $hook['accepted_args']);
        }
	}

    private function front_page_content(): string
    {
        ob_start();
        get_template_part('templates/partials/customer-form');
        return ob_get_clean();
    }

    private function customer_form_submit() {
        $response = [
            'success' => true,
            'message' => ''
        ];

        try {
            if (!isset($_POST['customer_form_wpnonce'])
            || !wp_verify_nonce($_POST['customer_form_wpnonce'], 'customer_form_submit')
            ) {
                throw new Exception('Sorry, your nonce did not verify.');
            }

            if (empty($_POST['email'])) {
                throw new Exception('Empty email.');
            }

            $user_id = wp_insert_user([
                'user_email'    =>  esc_sql($_POST['email']),
                'user_login'    =>  esc_sql($_POST['email']),
                'user_pass'     =>  wp_hash_password('temp123'),
                'role'          => 'customer',
            ]);

            if (is_wp_error($user_id)) {
                throw new Exception("User not created.");
            }

            $post_id = wp_insert_post([
                'post_type'     => 'customer-orders',
                'post_title'    => 'Customer Order for ' . esc_sql($_POST['email']),
                'post_content'  => esc_sql($_POST['details']),
                'post_status'   => 'publish',
                'post_author'   => 1
            ]);

            if (is_wp_error($post_id)) {
                throw new Exception("Order not created.");
            }

            if (function_exists('update_field')) {
                update_field('user', $user_id, $post_id);
            }

            $this->customer_form_submit_send_email(get_bloginfo('admin_email'), $_POST);

            $response['message'] = 'Success.';
        } catch (Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }

        wp_send_json($response);
    }

    private function customer_form_submit_send_email(string $to, array $data): void
    {
        $message = 'User ' . $_POST['email'] . ' submitted form.<br>Details: ' . strip_tags(nl2br($_POST['details']), '<br>');

        wp_mail($to, 'New Customer Orders form submit', $message);
    }

}