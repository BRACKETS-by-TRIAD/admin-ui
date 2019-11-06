<?php

return [
    'page_title_suffix' => 'Craftable',

    'operation' => [
        'succeeded' => 'Operation successful',
        'failed' => 'Operation failed',
        'not_allowed' => 'Operation not allowed',
        'publish_now' => 'Publish now',
        'unpublish_now' => 'Unpublish now',
        'publish_later' => 'Publish later',
    ],

    'dialogs' => [
        'duplicateDialog' => [
            'title' => 'Warning!',
            'text' => 'Do you really want to duplicate this item?',
            'yes' => 'Yes, duplicate.',
            'no' => 'No, cancel.',
            'success_title' => 'Success!',
            'success' => 'Item successfully duplicated.',
            'error_title' => 'Error!',
            'error' => 'An error has occured.',
        ],
        'deleteDialog' => [
            'title' => 'Warning!',
            'text' => 'Do you really want to delete this item?',
            'yes' => 'Yes, delete.',
            'no' => 'No, cancel.',
            'success_title' => 'Success!',
            'success' => 'Item successfully deleted.',
            'error_title' => 'Error!',
            'error' => 'An error has occured.',
        ],
        'publishNowDialog' => [
            'title' => 'Warning!',
            'text' => 'Do you really want to publish this item now?',
            'yes' => 'Yes, publish now.',
            'no' => 'No, cancel.',
            'success_title' => 'Success!',
            'success' => 'Item successfully published.',
            'error_title' => 'Error!',
            'error' => 'An error has occured.',
        ],
        'unpublishNowDialog' => [
            'title' => 'Warning!',
            'text' => 'Do you really want to unpublish this item now?',
            'yes' => 'Yes, unpublish now.',
            'no' => 'No, cancel.',
            'success_title' => 'Success!',
            'success' => 'Item successfully published.',
            'error_title' => 'Error!',
            'error' => 'An error has occured.',
        ],
        'publishLaterDialog' => [
            'text' => 'Please choose the date when the item should be published:',
            'yes' => 'Save',
            'no' => 'Cancel',
            'success_title' => 'Success!',
            'success' => 'Item was successfully saved.',
            'error_title' => 'Error!',
            'error' => 'An error has occured.',
        ],
    ],

    'btn' => [
        'save' => 'Save',
        'cancel' => 'Cancel',
        'edit' => 'Edit',
        'delete' => 'Delete',
        'search' => 'Search',
        'new' => 'New',
        'saved' => 'Saved',
    ],

    'index' => [
        'no_items' => 'Could not find any items',
        'try_changing_items' => 'Try changing the filters or add a new one',
    ],

    'listing' => [
        'selected_items' => 'Selected items',
        'uncheck_all_items' => 'Uncheck all items',
        'check_all_items' => 'Check all items',
    ],

    'forms' => [
        'select_a_date' => 'Select date',
        'select_a_time' => 'Select time',
        'select_date_and_time' => 'Select date and time',
        'choose_translation_to_edit' => 'Choose translation to edit:',
        'manage_translations' => 'Manage translations',
        'more_can_be_managed' => '({{ otherLocales.length }} more can be managed)',
        'currently_editing_translation' => 'Currently editing {{ this.defaultLocale.toUpperCase() }} (default) translation',
        'hide' => 'Hide Translations',
        'select_an_option' => 'Select an option',
        'select_options' => 'Select options',
        'publish' => 'Publish',
        'history' => 'History',
        'created_by' => 'Created by',
        'updated_by' => 'Updated by',
        'created_on' => 'Created on',
        'updated_on' => 'Updated on'
    ],

    'placeholder' => [
        'search' => 'Search'
    ],

    'pagination' => [
        'overview' => 'Displaying items from {{ pagination.state.from }} to {{ pagination.state.to }} of total {{ pagination.state.total }} items.'
    ],

    'logo' => [
        'title' => 'Craftable',
    ],

    'profile_dropdown' => [
        'account' => 'Account',
    ],

    'sidebar' => [
        'content' => 'Content',
        'settings' => 'Settings',
    ],

    'media_uploader' => [
        'max_number_of_files' => '(max no. of files: :maxNumberOfFiles files)',
        'max_size_pre_file' => '(max size per file: :maxFileSize MB)',

        'private_title' => 'Files are not accessible for public',
    ],

    'footer' => [
        'powered_by' => 'Powered by',
    ]

];