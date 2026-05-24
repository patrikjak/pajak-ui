<?php

declare(strict_types=1);

return [

    'form' => [
        'submit' => 'Odeslat',
        'select' => [
            'placeholder' => 'Vyberte možnost',
            'search_placeholder' => 'Hledat…',
        ],
        'password' => [
            'label' => 'Heslo',
            'confirmation_label' => 'Potvrďte heslo',
        ],
        'file' => [
            'placeholder' => 'Žádný soubor nevybrán',
        ],
        'dropzone' => [
            'title' => 'Přetáhněte soubory sem nebo klikněte pro nahrání',
            'sub' => 'Podporuje jakýkoli typ souboru',
            'aria_label' => 'Nahrát soubory',
            'remove' => 'Odebrat soubor',
        ],
        'avatar' => [
            'upload' => 'Nahrát fotku',
            'remove' => 'Odebrat',
        ],
        'image_grid' => [
            'add' => 'Přidat obrázek',
            'remove' => 'Odebrat obrázek',
        ],
        'repeater' => [
            'add' => 'Přidat položku',
            'remove' => 'Odebrat',
        ],
    ],

    'alert' => [
        'dismiss' => 'Zavřít',
    ],

    'banner' => [
        'dismiss' => 'Zavřít',
    ],

    'stepper' => [
        'step_of' => 'Krok :current z :total',
    ],

    'error_page' => [
        'maintenance_progress' => 'Průběh údržby',
        '404' => [
            'title' => 'Stránka nenalezena',
            'description' => 'Stránka, kterou hledáte, byla přesunuta, odstraněna nebo nikdy neexistovala. Zkontrolujte adresu URL nebo se vraťte na hlavní stránku.',
        ],
        '500' => [
            'title' => 'Něco se pokazilo',
            'description' => 'Na naší straně došlo k neočekávané chybě. Náš tým byl automaticky upozorněn a pracuje na nápravě. Zkuste stránku za chvíli obnovit.',
        ],
        '403' => [
            'title' => 'Přístup zamítnut',
            'description' => 'Nemáte oprávnění zobrazit tuto stránku. Pokud si myslíte, že jde o chybu, kontaktujte správce účtu nebo podporu.',
        ],
        '401' => [
            'title' => 'Relace vypršela',
            'description' => 'Vaše relace vypršela z bezpečnostních důvodů. Přihlaste se znovu a pokračujte — váš postup byl uložen.',
        ],
        '503' => [
            'title' => 'Hned se vrátíme',
            'description' => 'Provádíme plánovanou údržbu za účelem zlepšení spolehlivosti a výkonu. Vydržte — budeme zpět za několik minut.',
        ],
    ],

    'calendar' => [
        'placeholder' => 'Vyberte datum',
        'range_placeholder' => 'Vyberte rozsah',
        'today' => 'Dnes',
        'apply' => 'Použít',
        'clear' => 'Vymazat',
        'prev_month' => 'Předchozí měsíc',
        'next_month' => 'Další měsíc',
        'time' => 'Čas',
        'start_time' => 'Čas začátku',
        'end_time' => 'Čas konce',
    ],

];
