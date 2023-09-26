<?php

return [
    'required' => ':attribute alanı gereklidir.',
    'string' => ':attribute alanı bir dize olmalıdır.',
    'max' => [
        'string' => ':attribute alanı en fazla :max karakter olmalıdır.',
    ],
    'date' => ':attribute alanı geçerli bir tarih olmalıdır.',
    'integer' => ':attribute alanı bir tam sayı olmalıdır.',
    'exists' => 'Seçilen :attribute geçerli değil.',
    'image' => ':attribute alanı bir resim olmalıdır.',

    'attributes' => [
     'name' => 'İsim',
     'description' => 'Açıklama',
     'image' => 'Resim',
     'motor_model' => 'Motor Modeli',
     'oem_code' => 'OEM Kodu',
     'product_code' => 'Ürün Kodu',
     'stock_entry_date' => 'Stok Giriş Tarihi',
     'category_id' => 'Kategori ',
     'brand_id' => 'Marka',
     'vehicle_type_id' => 'Araç Tipi',
 ]
];
