<?php

namespace SheepFish\classes;

class SheepFishTheme
{
    private $styles = [];
    private $scripts = [];
    protected $actions = [];
    protected $filters = [];

    public function __construct() {
        $this->setup();
        $this->init_styles();
        $this->init_scripts();
    }

    private function setup(): void
    {
        $this->add_action('after_switch_theme', [$this, 'switch_theme_setup']);
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

}