<?php
declare(strict_types=1);
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once dirname(__DIR__) . '/../config/constants.php';
require_once dirname(__DIR__) . '/../config/database.php';

$page_title        = 'Servicios — ' . SITE_NAME;
$extra_stylesheets = ['assets/css/client/servicios.css'];
$base              = BASE_URL;

// Re-using the same services arrays that were in index.php
$services = [
    ['key' => 'coworking', 'nombre' => 'Coworking Abierto',    'descripcion' => 'Área común con vistas al mar.'],
    ['key' => 'cafe',      'nombre' => 'Café Salitre',         'descripcion' => 'Café de especialidad. 7am-8pm.'],
    ['key' => 'surf',      'nombre' => 'Clases de Surf',       'descripcion' => 'Instructor certificado. 7am. Incluidas 3+ noches.'],
    ['key' => 'yoga',      'nombre' => 'Yoga Frente al Mar',   'descripcion' => 'Sesiones grupales en terraza. Lun/Mié/Vie 6:30am.'],
    ['key' => 'transfer',  'nombre' => 'Transfer Aeropuerto',  'descripcion' => 'Traslado programable. Precio según destino.'],
    ['key' => 'late',      'nombre' => 'Late Checkout',        'descripcion' => 'Disponible hasta 3pm según disponibilidad.'],
];

$service_icons = [
    'coworking' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>',
    'cafe'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>',
    'surf'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12c2-4 6-6 10-3s8 3 10-1"/><path d="M2 18c2-4 6-6 10-3s8 3 10-1"/></svg>',
    'yoga'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="4" r="1.5"/><path d="M7 21l3-7 2 3 2-3 3 7"/><path d="M4 12c2-2 4-3 8-3s6 1 8 3"/></svg>',
    'transfer'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="2"/><path d="M16 8h4l3 5v4h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    'late'      => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
];

require_once dirname(__DIR__) . '/includes/header.php';
require_once dirname(__DIR__) . '/includes/nav.php';

require __DIR__ . '/index.view.php';
