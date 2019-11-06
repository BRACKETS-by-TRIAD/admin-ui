<?php

return [
    'page_title_suffix' => 'Craftable',

    'operation' => [
        'succeeded' => 'Akcia prebehla úspešne',
        'failed' => 'Akcia sa nepodarila',
        'not_allowed' => 'Operácia nie je povolená',
        'publish_now' => 'Publikovať',
        'unpublish_now' => 'Zrušiť publikovanie',
        'publish_later' => 'Publikovať neskôr',
    ],

    'dialogs' => [
        'duplicateDialog' => [
            'title' => 'Varovanie!',
            'text' => 'Naozaj chcete duplikovať túto položku?',
            'yes' => 'Áno, duplikovať.',
            'no' => 'Nie, zatvoriť.',
            'success_title' => 'Úspešne!',
            'success' => 'Položka úspešne duplikovaná.',
            'error_title' => 'Chyba!',
            'error' => 'Došlo k chybe.',
        ],
        'deleteDialog' => [
            'title' => 'Varovanie!',
            'text' => 'Naozaj chcete vymazať túto položku?',
            'yes' => 'Áno, vymazať.',
            'no' => 'Nie, zatvoriť.',
            'success_title' => 'Úspešne!',
            'success' => 'Položka úspešne odstránená.',
            'error_title' => 'Chyba!',
            'error' => 'Došlo k chybe.',
        ],
        'publishNowDialog' => [
            'title' => 'Varovanie!',
            'text' => 'Naozaj chcete publikovať túto položku?',
            'yes' => 'Áno, publikovať.',
            'no' => 'Nie, zatvoriť.',
            'success_title' => 'Úspešne!',
            'success' => 'Položka úspešne publikovaná.',
            'error_title' => 'Chyba!',
            'error' => 'Došlo k chybe.',
        ],
        'unpublishNowDialog' => [
            'title' => 'Varovanie!',
            'text' => 'Naozaj chcete zrušiť publikovanie tejto položky?',
            'yes' => 'Áno, zrušiť puklikovanie.',
            'no' => 'Nie, zatvoriť.',
            'success_title' => 'Úspešne!',
            'success' => 'Položka úspešne publikovaná.',
            'error_title' => 'Chyba!',
            'error' => 'Došlo k chybe.',
        ],
        'publishLaterDialog' => [
            'text' => 'Prosím vyberťe dátum kedy má byť pooložka publikovaná:',
            'yes' => 'Uložiť',
            'no' => 'Zatvoriť',
            'success_title' => 'Úspešne!',
            'success' => 'Položka bola úspešne uložená.',
            'error_title' => 'Chyba!',
            'error' => 'Došlo k chybe.',
        ],
    ],

    'btn' => [
        'save' => 'Uložiť',
        'cancel' => 'Zrušiť',
        'edit' => 'Upraviť',
        'delete' => 'Vymazať',
        'search' => 'Hľadať',
        'saved' => 'Uložené',
    ],

    'index' => [
        'no_items' => 'Nenašli sa žiadne položky',
        'try_changing_items' => 'Skúste zmeniť filter alebo pridať novú',
    ],

    'listing' => [
        'selected_items' => 'Vybrané položky',
        'uncheck_all_items' => 'Odznačiť všetky položky',
        'check_all_items' => 'Označiť všetky položky',
    ],

    'forms' => [
        'select_a_date' => 'Zvoľte dátum',
        'select_a_time' => 'Zvoľte čas',
        'select_date_and_time' => 'Zvoľte dátum a čas',
        'choose_translation_to_edit' => 'Zvoľte preklad na úpravu',
        'manage_translations' => 'Spravovať preklady',
        'more_can_be_managed' => '({{ otherLocales.length }} možno spravovať)',
        'currently_editing_translation' => 'Práve upravujete {{ this.defaultLocale.toUpperCase() }} (základný) preklad',
        'hide' => 'Skryť preklady',
        'publish' => 'Publikácia',
        'history' => 'História',
        'created_by' => 'Vytvoril',
        'updated_by' => 'Aktualizoval',
        'created_on' => 'Vytvorené',
        'updated_on' => 'Aktualizované'
    ],

    'placeholder' => [
        'search' => 'Hľadať'
    ],

    'pagination' => [
        'overview' => 'Zobrazujú sa položky od {{ pagination.state.from }} do {{ pagination.state.to }} z celkom {{ pagination.state.total }} položiek.'
    ],

    'logo' => [
        'title' => 'Craftable',
    ],

    'profile_dropdown' => [
        'account' => 'Účet',
    ],

    'sidebar' => [
        'content' => 'Obsah',
        'settings' => 'Nastavenia',
    ],

    'media_uploader' => [
        'max_number_of_files' => '(max. počet súborov: :maxNumberOfFiles files)',
        'max_size_pre_file' => '(max. veľkosť súboru: :maxFileSize MB)',

        'private_title' => 'Súbory nie sú verejne dostupné.',
    ],

    'footer' => [
        'powered_by' => 'Powered by', // we leave this in english intentionally
    ]

];