<?php
/***
 * Examples on adding post types, taxonomies, user roles, sidebars, settings, meta boxes and API endpoints.
 *
 * @package Kickoff
 */

namespace Lambry\Kickoff;

use Lambry\Kickoff\{Post, Taxonomy, Role, Sidebar, Setting, Meta, Router};

defined('ABSPATH') || exit;

/**
 * Register book post type.
 */
Post::add('book', 'Book', 'Books')->set([
    'menu_icon' => 'dashicons-book-alt'
]);

/**
 * Register genre taxonomy for books.
 */
Taxonomy::add('genre', 'Genre', 'Genres')->to('book')->set([
    'hierarchical' => true,
    'public'       => true
]);

/**
 * Register customer user role.
 */
Role::add('customer', 'Customer')->set([
    'publish_posts' => false,
    'read'          => true
]);

/**
 * Register footer sidebar/widget.
 */
Sidebar::add('footer', 'Footer Widget')->set([
    'description' => 'Main footer area'
]);

/**
 * Register a settings screen with sections and fields.
 */
Setting::add('menu', 'Options', 'Plugin Options')
    ->section('general', 'General', 'Generic plugin options', [
        [
            'id'    => 'authors_display',
            'label' => 'Display author card',
            'type'  => 'on_off'
        ], [
            'id'    => 'authors_display_2',
            'label' => 'Display author card',
            'type'  => 'upload'
        ], [
            'id'    => 'authors_intro',
            'label' => 'Authors intro copy',
            'description' => 'This section will be displayed above all authors.',
            'type'  => 'editor'
        ]
    ])
    ->section('display', 'Display', 'Display specific options', [
        [
            'id'    => 'book_color',
            'label' => 'Book icon color',
            'type'  => 'color'
        ], [
            'id'    => 'book_position',
            'label' => 'Book icon position',
            'type'  => 'radio',
            'choices' => [
                'left' => 'Left',
                'right' => 'Right'
            ]
        ]
])->set();

/**
 * Register a metabox with fields.
 */
Meta::add('review', 'Review Section', 'Reviews to display under the books info.')
	->fields([
        [
            'id'    => 'reviews_display',
            'label' => 'Display review section',
            'description' => 'Check here to display all reviews on this page.',
            'type'  => 'on_off'
        ], [
            'id'    => 'reviews_title',
            'label' => 'Reviewer section title',
            'type'  => 'text'
        ]
    ])
    ->repeat('reviews', 'Reviews', 'Add as many reviews as neeeded below.', [
        [
            'id'    => 'reviewer_name',
            'label' => 'Reviewer name',
            'type'  => 'text'
        ], [
            'id'    => 'reviewer_rating',
            'label' => 'Reviewer rating',
			'type'  => 'radio',
			'choices' => [
                'one' => 'One Star',
                'two' => 'Two Stars',
                'three' => 'Three Stars'
            ]
        ]
])->to('book')->set();

/**
 * Setup api endpoints.
 *
 * @access private
 * @return null
 */
add_action('rest_api_init', function() {
    $route = new Router('kickoff');

	// Add individual routes
	$route->get('genres', 'Genres');
	$route->get('settings', 'Settings');

	// Supported HTTP request methods
	$route->get('books', 'Books');
	$route->post('books', 'Books', ['auth' => true]);
	$route->put('books/:id', 'Books', ['auth' => true]);
	$route->patch('books/:id', 'Books', ['auth' => true]);
	$route->delete('books/:id', 'Books', ['auth' => true]);
});
