<?php
/**
 * PostMaster™ Analysis System - Constants & Configuration
 * 
 * Système de notation binaire pour l'analyse de posts sociaux
 * Version: 1.0
 * Date: 2025-08-11
 */

class PostMasterConstants {

    /**
     * MAPPING PLATEFORMES/FORMATS VERS GROUPES
     */
    public static function getPlatformGroupMapping() {
        return [
            // GROUPE 1 - Short video et multi séquences (Poids: texte 0.5, image 0.5)
            'tiktok' => [
                'video' => 1,
                'multi-sequences' => 1
            ],
            'youtube' => [
                'short' => 1
            ],

            // GROUPE 2 - Image et carrousel créatif (Poids: texte 0.6, image 0.4)  
            'instagram' => [
                'publication' => 2,
                'carrousel' => 2,
                'reel' => 1,      // Groupe 1
                'story' => 5      // Groupe 5
            ],
            'pinterest' => [
                'epingle' => 2,
                'epingle-idee' => 2
            ],
            'facebook' => [
                'publication' => 2,
                'reel' => 1,      // Groupe 1
                'story' => 5      // Groupe 5
            ],

            // GROUPE 3 - Fil professionnel et long format (Poids: texte 0.7, image 0.3)
            'linkedin' => [
                'publication' => 3,
                'document' => 3,
                'article' => 3
            ],

            // GROUPE 4 - Microblog et threads (Poids: texte 0.8, image 0.2)
            'twitter' => [
                'tweet' => 4,
                'thread' => 4
            ],
            'threads' => [
                'thread' => 4
            ]

            // GROUPE 5 - Stories éphémères (Poids: texte 0.4, image 0.6)
            // Déjà mappé dans instagram/facebook story ci-dessus
        ];
    }

    /**
     * POIDS PAR GROUPE
     */
    public static function getGroupWeights() {
        return [
            1 => ['text' => 0.5, 'image' => 0.5], // Short video
            2 => ['text' => 0.6, 'image' => 0.4], // Image/carrousel
            3 => ['text' => 0.7, 'image' => 0.3], // Pro/long format
            4 => ['text' => 0.8, 'image' => 0.2], // Microblog/threads
            5 => ['text' => 0.4, 'image' => 0.6]  // Stories
        ];
    }

    /**
     * LIMITES DE CARACTÈRES PAR PLATEFORME/FORMAT
     */
    public static function getCharacterLimits() {
        return [
            'instagram' => [
                'publication' => 2200,
                'carrousel' => 2200,
                'reel' => 2200,
                'story' => 120
            ],
            'facebook' => [
                'publication' => 63206,
                'reel' => 2200,
                'story' => 120
            ],
            'tiktok' => [
                'video' => 2200,
                'multi-sequences' => 2200
            ],
            'youtube' => [
                'short' => 1000
            ],
            'linkedin' => [
                'publication' => 3000,
                'document' => 3000,
                'article' => 8000
            ],
            'twitter' => [
                'tweet' => 280,
                'thread' => 2500
            ],
            'threads' => [
                'thread' => 2500
            ],
            'pinterest' => [
                'epingle' => 500,
                'epingle-idee' => 500
            ]
        ];
    }

    /**
     * VOLUMES HASHTAGS PAR GROUPE
     */
    public static function getHashtagLimits() {
        return [
            1 => ['min' => 0, 'max' => 5],   // Short video
            2 => ['min' => 3, 'max' => 10],  // Image/carrousel
            3 => ['min' => 0, 'max' => 3],   // Pro/long
            4 => ['min' => 0, 'max' => 2],   // Microblog
            5 => ['min' => 0, 'max' => 3]    // Stories
        ];
    }

    /**
     * OUTILS/MÉTHODES/FRAMEWORKS RECONNUS
     */
    public static function getRecognizedTools() {
        return [
            // Outils marketing
            'canva', 'figma', 'photoshop', 'illustrator', 'indesign',
            'hootsuite', 'buffer', 'later', 'sprout social',
            'google analytics', 'facebook ads', 'google ads',
            'mailchimp', 'klaviyo', 'convertkit',
            
            // Frameworks business
            'swot', 'pestel', '5w2h', 'smart', 'okr', 'kpi',
            'lean startup', 'design thinking', 'scrum', 'agile',
            'canvas', 'persona', 'customer journey',
            
            // Méthodes de contenu
            'storytelling', 'copywriting', 'seo', 'sem',
            'growth hacking', 'content marketing', 'inbound',
            'outbound', 'lead magnet', 'funnel',
            
            // Outils techniques
            'wordpress', 'shopify', 'woocommerce', 'magento',
            'salesforce', 'hubspot', 'pipedrive', 'notion',
            'trello', 'asana', 'slack', 'zoom',
            
            // Frameworks dev (pour contenu tech)
            'react', 'vue', 'angular', 'laravel', 'symfony',
            'django', 'flask', 'node.js', 'express'
        ];
    }

    /**
     * VERBES D'ACTION FORTS
     */
    public static function getActionVerbs() {
        return [
            'fr' => [
                'découvre', 'teste', 'essaye', 'applique', 'utilise',
                'transforme', 'optimise', 'améliore', 'développe',
                'crée', 'lance', 'booste', 'maîtrise', 'domine',
                'révèle', 'dévoile', 'partage', 'montre', 'prouve'
            ],
            'en' => [
                'discover', 'test', 'try', 'apply', 'use',
                'transform', 'optimize', 'improve', 'develop',
                'create', 'launch', 'boost', 'master', 'dominate',
                'reveal', 'unveil', 'share', 'show', 'prove'
            ]
        ];
    }

    /**
     * MOTS DE CTA PAR GROUPE
     */
    public static function getCtaWords() {
        return [
            1 => [ // Short video
                'fr' => ['enregistre', 'partage', 'teste', 'essaye', 'découvre'],
                'en' => ['save', 'share', 'test', 'try', 'discover']
            ],
            2 => [ // Image/carrousel
                'fr' => ['swipe', 'enregistre', 'commente', 'partage', 'découvre'],
                'en' => ['swipe', 'save', 'comment', 'share', 'discover']
            ],
            3 => [ // Pro/long
                'fr' => ['partage', 'commente', 'contacte', 'découvre', 'applique'],
                'en' => ['share', 'comment', 'contact', 'discover', 'apply']
            ],
            4 => [ // Microblog
                'fr' => ['rt', 'réponds', 'teste', 'partage', 'découvre'],
                'en' => ['rt', 'reply', 'test', 'share', 'discover']
            ],
            5 => [ // Stories
                'fr' => ['swipe', 'enregistre', 'réponds', 'découvre', 'teste'],
                'en' => ['swipe', 'save', 'reply', 'discover', 'test']
            ]
        ];
    }

    /**
     * RÉSOLUTIONS MINIMALES ET RATIOS
     */
    public static function getImageRequirements() {
        return [
            1 => [ // Reels/Shorts
                'min_width' => 1080,
                'min_height' => 1920,
                'ratio' => 0.5625, // 9:16
                'tolerance' => 0.03
            ],
            2 => [ // Feed/Carrousel
                'portrait' => [
                    'min_width' => 1080,
                    'min_height' => 1350,
                    'ratio' => 0.8, // 4:5
                    'tolerance' => 0.03
                ],
                'square' => [
                    'min_width' => 1080,
                    'min_height' => 1080,
                    'ratio' => 1.0, // 1:1
                    'tolerance' => 0.03
                ],
                'landscape' => [
                    'min_width' => 1080,
                    'min_height' => 608,
                    'ratio' => 1.77, // ~16:9
                    'tolerance' => 0.03
                ]
            ],
            3 => [ // LinkedIn/Pro
                'document' => [
                    'min_width' => 1080,
                    'min_height' => 1350,
                    'ratio' => 0.8,
                    'tolerance' => 0.03
                ],
                'article' => [
                    'min_width' => 1200,
                    'min_height' => 675,
                    'ratio' => 1.78,
                    'tolerance' => 0.03
                ],
                'publication' => [
                    'min_width' => 1200,
                    'min_height' => 1200,
                    'ratio' => 1.0,
                    'tolerance' => 0.03
                ]
            ],
            4 => [ // Twitter/Microblog
                'tweet' => [
                    'min_width' => 1200,
                    'min_height' => 675,
                    'ratio' => 1.78,
                    'tolerance' => 0.03
                ],
                'thread' => [
                    'min_width' => 1080,
                    'min_height' => 1080,
                    'ratio' => 1.0,
                    'tolerance' => 0.03
                ]
            ],
            5 => [ // Stories
                'min_width' => 1080,
                'min_height' => 1920,
                'ratio' => 0.5625, // 9:16
                'tolerance' => 0.03
            ]
        ];
    }

    /**
     * SEUILS NUMÉRIQUES POUR L'ANALYSE
     */
    public static function getAnalysisThresholds() {
        return [
            'text' => [
                'sentence_min_words' => 8,
                'sentence_max_words' => 24,
                'max_emojis_total' => 6,
                'max_emojis_consecutive' => 3,
                'hook_max_chars' => 120,
                'first_line_max_chars' => 120,
                'benefit_max_chars' => 200,
                'action_max_chars_short' => 80,
                'max_mentions' => 3,
                'line_break_interval' => 300,
                'intensifier_ratio_max' => 0.15,
                'thread_max_chars' => 2500,
                'story_max_chars' => 120,
                'carrousel_max_chars_per_slide' => 150,
                'max_cta_count' => 1
            ],
            'image' => [
                'max_objects_per_image' => 6,
                'subject_min_area_percent' => 15,
                'subject_max_area_percent' => 70,
                'center_tolerance_percent' => 5,
                'blur_threshold' => 0.8,
                'safe_search_max_level' => 'POSSIBLE',
                'ocr_min_chars' => 4,
                'ocr_max_chars_single' => 220,
                'ocr_max_chars_carrousel' => 1200,
                'ocr_max_chars_story' => 120,
                'ocr_min_confidence' => 0.7,
                'contrast_ratio_min' => 2.5,
                'stock_photo_threshold' => 0.9,
                'compression_threshold' => 0.8,
                'max_dominant_colors' => 6,
                'background_dominance_min' => 35,
                'min_file_size_kb' => 60,
                'word_height_min_percent' => 3,
                'story_safe_zone_percent' => 13,
                'carrousel_title_max_words' => 8
            ]
        ];
    }

    /**
     * DICTIONNAIRE D'AMÉLIORATIONS IMAGE (V ratés -> suggestions)
     */
    public static function getImageImprovements() {
        return [
            'fr' => [
                'V1' => 'Simplifiez votre composition, maximum 6 éléments visuels principaux',
                'V2' => 'Redimensionnez votre sujet principal pour occuper 15-70% de l\'image',
                'V3' => 'Évitez les visages sauf pour les publications LinkedIn professionnelles',
                'V4' => 'Redressez votre image, évitez les inclinations visibles',
                'V5' => 'Décentrez légèrement votre sujet principal pour plus de dynamisme',
                'V6' => 'Améliorez la netteté de votre image, évitez le flou',
                'V7' => 'Vérifiez que votre contenu respecte les standards de sécurité',
                'V8' => 'Modérez le contenu adulte/violent pour respecter les guidelines',
                'V9' => 'Ajoutez du texte lisible si votre format l\'exige',
                'V10' => 'Supprimez les watermarks et mentions de banques d\'images',
                'V11' => 'Ajustez la quantité de texte selon le format (4-220 caractères)',
                'V12' => 'Améliorez la lisibilité du texte, police plus claire',
                'V13' => 'Évitez la fragmentation excessive du texte',
                'V14' => 'Augmentez le contraste entre texte et arrière-plan',
                'V15' => 'Diversifiez votre palette de couleurs',
                'V16' => 'Remplacez les emojis par du texte lisible',
                'V17' => 'Réduisez le texte overlay pour les formats courts',
                'V18' => 'Créez au moins une slide épurée avec peu de texte',
                'V19' => 'Corrigez l\'orientation du texte (pas de miroir)',
                'V20' => 'Supprimez les URLs externes non autorisées',
                'V21' => 'Intégrez vos couleurs de marque dans le visuel',
                'V22' => 'Ajoutez votre logo de manière subtile',
                'V23' => 'Supprimez les références aux marques concurrentes',
                'V24' => 'Respectez des marges régulières (4-12% d\'espacement)',
                'V25' => 'Harmonisez le style graphique de tous vos éléments',
                'V26' => 'Utilisez des images originales plutôt que du stock',
                'V27' => 'Améliorez la qualité d\'export, réduisez la compression',
                'V28' => 'Limitez votre palette à 6 couleurs dominantes maximum',
                'V29' => 'Assurez-vous d\'un fond suffisamment contrasté',
                'V30' => 'Supprimez les QR codes sauf formats professionnels',
                'V31' => 'Augmentez la résolution selon les standards du format',
                'V32' => 'Respectez le ratio d\'image recommandé (±3%)',
                'V33' => 'Exportez en qualité supérieure',
                'V34' => 'Réduisez le bruit numérique de l\'image',
                'V35' => 'Corrigez la surexposition des zones claires',
                'V36' => 'Éclaircissez les zones trop sombres',
                'V37' => 'Ajoutez de la couleur si autorisé par votre charte',
                'V38' => 'Respectez l\'orientation requise par le format',
                'V39' => 'Augmentez la taille de fichier (minimum 60KB)',
                'V40' => 'Supprimez la transparence si non nécessaire',
                'V41' => 'Adoptez le format portrait 9:16 pour les Reels/Shorts',
                'V42' => 'Utilisez le format portrait 4:5 pour le feed Instagram',
                'V43' => 'Uniformisez le ratio de toutes vos slides carrousel',
                'V44' => 'Agrandissez la taille du texte (min 3% hauteur image)',
                'V45' => 'Supprimez les emojis pour les contenus LinkedIn',
                'V46' => 'Libérez 13% d\'espace en haut/bas pour les stories',
                'V47' => 'Limitez le titre de première slide à 8 mots',
                'V48' => 'Ajoutez un CTA visible sur la dernière slide',
                'V49' => 'Supprimez les bandes noires latérales des Shorts',
                'V50' => 'Utilisez le même format pour tout le carrousel'
            ],
            'en' => [
                'V1' => 'Simplify your composition, maximum 6 main visual elements',
                'V2' => 'Resize your main subject to occupy 15-70% of the image',
                'V3' => 'Avoid faces except for LinkedIn professional posts',
                'V4' => 'Straighten your image, avoid visible tilts',
                'V5' => 'Slightly offset your main subject for more dynamism',
                'V6' => 'Improve image sharpness, avoid blur',
                'V7' => 'Ensure your content meets safety standards',
                'V8' => 'Moderate adult/violent content to respect guidelines',
                'V9' => 'Add readable text if your format requires it',
                'V10' => 'Remove watermarks and stock photo mentions',
                'V11' => 'Adjust text quantity according to format (4-220 characters)',
                'V12' => 'Improve text readability, use clearer font',
                'V13' => 'Avoid excessive text fragmentation',
                'V14' => 'Increase contrast between text and background',
                'V15' => 'Diversify your color palette',
                'V16' => 'Replace emojis with readable text',
                'V17' => 'Reduce text overlay for short formats',
                'V18' => 'Create at least one clean slide with minimal text',
                'V19' => 'Fix text orientation (no mirroring)',
                'V20' => 'Remove unauthorized external URLs',
                'V21' => 'Integrate your brand colors into the visual',
                'V22' => 'Add your logo subtly',
                'V23' => 'Remove references to competitor brands',
                'V24' => 'Maintain regular margins (4-12% spacing)',
                'V25' => 'Harmonize the graphic style of all elements',
                'V26' => 'Use original images rather than stock',
                'V27' => 'Improve export quality, reduce compression',
                'V28' => 'Limit your palette to maximum 6 dominant colors',
                'V29' => 'Ensure sufficient background contrast',
                'V30' => 'Remove QR codes except for professional formats',
                'V31' => 'Increase resolution according to format standards',
                'V32' => 'Respect the recommended image ratio (±3%)',
                'V33' => 'Export in higher quality',
                'V34' => 'Reduce image digital noise',
                'V35' => 'Correct overexposure in bright areas',
                'V36' => 'Brighten areas that are too dark',
                'V37' => 'Add color if allowed by your brand guidelines',
                'V38' => 'Respect the orientation required by the format',
                'V39' => 'Increase file size (minimum 60KB)',
                'V40' => 'Remove transparency if not necessary',
                'V41' => 'Adopt 9:16 portrait format for Reels/Shorts',
                'V42' => 'Use 4:5 portrait format for Instagram feed',
                'V43' => 'Standardize the ratio of all your carousel slides',
                'V44' => 'Enlarge text size (min 3% image height)',
                'V45' => 'Remove emojis for LinkedIn content',
                'V46' => 'Keep 13% space free at top/bottom for stories',
                'V47' => 'Limit first slide title to 8 words',
                'V48' => 'Add visible CTA on the last slide',
                'V49' => 'Remove black side bars from Shorts',
                'V50' => 'Use the same format for the entire carousel'
            ]
        ];
    }

    /**
     * INTENSIFICATEURS VAGUES À DÉTECTER
     */
    public static function getVagueIntensifiers() {
        return [
            'fr' => ['très', 'trop', 'vraiment', 'super', 'hyper', 'ultra', 'méga', 'énormément'],
            'en' => ['very', 'really', 'extremely', 'incredibly', 'absolutely', 'totally', 'completely']
        ];
    }

    /**
     * OUVERTURES FAIBLES À ÉVITER
     */
    public static function getWeakOpenings() {
        return [
            'fr' => [
                'dans cette vidéo je vais',
                'salut les amis',
                'aujourd\'hui on va voir',
                'je pense que',
                'peut-être que',
                'il se peut que',
                'probablement',
                'j\'espère que'
            ],
            'en' => [
                'in this video i will',
                'hey guys',
                'today we will see',
                'i think that',
                'maybe',
                'it might be',
                'probably',
                'i hope that'
            ]
        ];
    }
}
?>
