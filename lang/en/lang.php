<?php

return [
    'plugin' => [
        'name' => 'services',
        'description' => 'Create and manage services - for agancies',
    ],
    'yes' => 'yes',
    'no' => 'no',
    'permissions' => [
        'some_permission' => 'Permission example',
    ],
    'service' => [
        'new' => 'Create new service',
        'name' => 'Name',
        'basic_info' => 'Basic nfo',
        'create_title' => 'Create new service',
        'name_comment' => 'the "|" character is converted into a new line in Service Content component',
        'price_from' => 'Price starting at',
        'short_description' => 'Short description',
        'short_description_comment' => 'Short description displayed in the Services List component',
        'background_image' => 'Background image',
        'icon' => 'Icon',
        'icon_comment' => 'Icon displayed in the Services List component',
        'published?' => 'Publish this service?',
        'content_tab' => 'Content',
        'seo_tab' => 'SEO',
        'meta_title' => 'Meta title',
        'meta_description' => 'Meta description',
        'content' => 'Content',
        'content_blocks' => 'Content blocks',
        'content_prompt' => 'Add new content block',
        'label' => 'Service',
        'quote'=>[
        	'label' => 'Quote',
        	'description' => 'Create a blockquote with author',
        	'content' => 'Content',
        	'author' => 'Author',
        ],
        'content_with_menu'=>[
        	'label' => 'Content with menu',
        	'description' => 'Content with sidebar menu',
        	'content' => 'Content',
        ],
        'section'=>[
        	'label' => 'Section',
        	'description' => 'Add a section with ID and class',
        	'id' => 'ID',
        	'class' => 'class',
        	'style' => 'Additional style',
            'image_style' => 'Additional style',
        	'background_image' => 'Background image',
        	'background_color' => 'Background color',
        	'content_sections' => 'Content sections',
        	'add_new_content_section' => 'Add new content section',
        	'content' => 'Content',
        	'content_id' => 'Content ID',
        	'content_class' => 'Content class',
        	'content_style' => 'Content additional style',
        	'content_text' => 'Content text',
        	'image' => 'Image',
        	'image_code' => 'Image code',
        	'image_code_description' => 'For example svg code',
            'container_class' => 'Container class name',
            'sidebar_class' => 'Sidebar class name',
        ],
        'numbers'=>[
        	'label' => 'Numbers',
        	'description' => 'Add numbers section with source',
        ],

    ],
    'services' => [
    	'menu_label' => 'Services',
        'delete_selected_success' => 'Succesfully deleted the selected Service',
        'delete_selected_empty' => 'Select services to delete',
    ],

    'components' => [
    	'serviceslist' => [
            'name' => 'Services list',
            'description' => 'Show a list of published services',
            'url' => 'URL',
            'url_description' => 'CMS page with the Service Content component',
            'slug' => 'Slug',
            'slug_description' => 'varibale name passed to the CMS page above',
            'container_class' => 'Container class',
            'container_class_description' => 'Class for the services wrapper DIV element',
        ],
        'servicecontent' => [
            'name' => 'Service content',
            'description' => 'Show content of the service',
        ],
    ],




];