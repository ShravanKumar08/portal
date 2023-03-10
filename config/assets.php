<?php

return array(
    'images' => array(

        'paths' => array(
            'input' => storage_path('app/public'),
            'output' => storage_path('app/public/cache')
        ),

        'sizes' => array(
            'small' => array(
                'width' => 150,
                'height' => 150
            ),
            'big' => array(
                'width' => 600,
                'height' => 400
            )
        )
    )

);