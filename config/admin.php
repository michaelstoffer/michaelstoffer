<?php

return [
    /*
    | Only these exact email addresses may enter the Filament panel. Keep this
    | list deliberately narrow; this is a personal site, not a public portal.
    */
    'allowed_emails' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('ADMIN_ALLOWED_EMAILS', 'mstoffer@michaelstoffer.com')),
    ))),
];
