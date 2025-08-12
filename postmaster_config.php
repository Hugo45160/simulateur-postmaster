<?php
/**
 * PostMaster™ Analysis System - Configuration
 * 
 * Fichier de configuration des clés API et paramètres
 * Version: 1.0
 * Date: 2025-08-11
 * 
 * IMPORTANT: Ne jamais commiter ce fichier dans un repo public !
 * Ajouter à .gitignore
 */

return [
    // CLÉS API - REMPLACE PAR TES VRAIES CLÉS
    'openai_api_key' => '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',
    'google_cloud_api_key' => 'AIzaSyBHWwsB9jC8056kliyPtB7guzpW4xtJuwU',
    
    // CONFIGURATION MARQUE
    'brand_colors' => [
        '#FF5A00', // Orange PostMaster™
        '#FFFFFF', // Blanc
        '#000000'  // Noir
    ],
    'brand_domain' => 'postmaster.com',
    
    // PARAMÈTRES SYSTÈME
    'debug_mode' => false,
    'log_requests' => true,
    'max_image_size_mb' => 10,
    'api_timeout' => 60,
    
    // WORDPRESS INTEGRATION
    'wp_ajax_action' => 'postmaster_analyze',
    'wp_nonce_name' => 'postmaster_nonce'
];
?>
