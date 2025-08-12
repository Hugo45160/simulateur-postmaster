<?php
/**
 * PostMaster™ Analysis System - Core Analysis Functions
 * 
 * Fonctions d'appel GPT, analyse Vision et calcul des scores
 * Version: 1.0
 * Date: 2025-08-11
 */

require_once 'postmaster_constants.php';
require_once 'postmaster_prompts.php';

class PostMasterAnalyzer {

    private $openaiApiKey;
    private $googleCloudApiKey;
    private $brandColors = [];
    private $brandDomain = '';

    public function __construct($openaiApiKey, $googleCloudApiKey, $brandColors = [], $brandDomain = '') {
        $this->openaiApiKey = $openaiApiKey;
        $this->googleCloudApiKey = $googleCloudApiKey;
        $this->brandColors = $brandColors;
        $this->brandDomain = $brandDomain;
    }

    /**
     * FONCTION PRINCIPALE D'ANALYSE
     */
    public function analyzePost($payload) {
        try {
            // 1. Valider l'entrée
            $validatedData = $this->validateInput($payload);
            
            // 2. Mapper vers un groupe
            $group = $this->mapPlatformToGroup($validatedData['platform'], $validatedData['format']);
            
            // 3. Analyser le texte avec GPT
            $textScoring = $this->analyzeText($group, $validatedData);
            
            // 4. Analyser les images avec Vision
            $imageScoring = $this->analyzeImages($group, $validatedData);
            
            // 5. Calculer le score combiné
            $combined = $this->calculateCombinedScore($group, $textScoring, $imageScoring);
            
            return [
                'text_scoring' => $textScoring,
                'image_scoring' => $imageScoring,
                'combined' => $combined
            ];
            
        } catch (Exception $e) {
            throw new Exception("Erreur d'analyse: " . $e->getMessage());
        }
    }

    /**
     * VALIDATION DE L'ENTRÉE
     */
    private function validateInput($payload) {
        $required = ['platform', 'format', 'text_blocks'];
        foreach ($required as $field) {
            if (!isset($payload[$field])) {
                throw new Exception("Champ requis manquant: $field");
            }
        }

        $validatedData = [
            'platform' => $payload['platform'],
            'format' => $payload['format'],
            'text_blocks' => (array) $payload['text_blocks'],
            'caption' => $payload['caption'] ?? '',
            'hashtags' => (array) ($payload['hashtags'] ?? []),
            'image_mode' => $payload['image_mode'] ?? 'single',
            'image_urls' => (array) ($payload['image_urls'] ?? []),
            'language' => $payload['language'] ?? 'fr'
        ];

        // Valider que la plateforme/format existe
        $mapping = PostMasterConstants::getPlatformGroupMapping();
        if (!isset($mapping[$validatedData['platform']][$validatedData['format']])) {
            throw new Exception("Combinaison plateforme/format non supportée");
        }

        return $validatedData;
    }

    /**
     * MAPPER PLATEFORME + FORMAT VERS GROUPE
     */
    private function mapPlatformToGroup($platform, $format) {
        $mapping = PostMasterConstants::getPlatformGroupMapping();
        return $mapping[$platform][$format];
    }

    /**
     * ANALYSE TEXTE AVEC GPT
     */
    private function analyzeText($group, $data) {
        try {
            // Construire le message pour GPT
            $messages = PostMasterPrompts::buildGptMessage(
                $group,
                $data['language'],
                $data['platform'],
                $data['format'],
                $data['text_blocks'],
                $data['caption'],
                $data['hashtags']
            );

            // Appeler l'API OpenAI
            $response = $this->callOpenAI([
                [
                    'role' => 'system',
                    'content' => $messages['system']
                ],
                [
                    'role' => 'user',
                    'content' => $messages['user']
                ]
            ]);

            // Parser la réponse JSON
            $gptResult = json_decode($response, true);
            if (!$gptResult || !isset($gptResult['binary']) || !isset($gptResult['improvements'])) {
                throw new Exception("Réponse GPT invalide");
            }

            // Valider que T1-T50 existent, compléter avec NON si manquant
            $binary = $this->validateBinaryResponse($gptResult['binary']);

            // Calculer les scores par catégorie
            $byCategory = $this->calculateTextCategories($binary);

            // Compter les totaux
            $totalYes = array_sum(array_map(function($cat) { return $cat['yes']; }, $byCategory));
            $totalNo = 50 - $totalYes;

            return [
                'by_category' => $byCategory,
                'total_yes' => $totalYes,
                'total_no' => $totalNo,
                'improvements' => array_slice($gptResult['improvements'], 0, 3) // Assurer exactement 3
            ];

        } catch (Exception $e) {
            throw new Exception("Erreur analyse texte: " . $e->getMessage());
        }
    }

    /**
     * APPEL API OPENAI
     */
    private function callOpenAI($messages) {
        $data = [
            'model' => 'gpt-4-1106-preview',
            'messages' => $messages,
            'temperature' => 0.1,
            'max_tokens' => 2000,
            'response_format' => ['type' => 'json_object']
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->openaiApiKey
            ],
            CURLOPT_TIMEOUT => 60
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Erreur API OpenAI: HTTP $httpCode");
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['choices'][0]['message']['content'])) {
            throw new Exception("Réponse OpenAI invalide");
        }

        return $result['choices'][0]['message']['content'];
    }

    /**
     * VALIDER ET COMPLÉTER LA RÉPONSE BINAIRE T1-T50
     */
    private function validateBinaryResponse($binary) {
        $validated = [];
        for ($i = 1; $i <= 50; $i++) {
            $key = "T$i";
            $value = isset($binary[$key]) ? strtoupper($binary[$key]) : 'NO';
            $validated[$key] = ($value === 'YES') ? 'YES' : 'NO';
        }
        return $validated;
    }

    /**
     * CALCULER LES SCORES PAR CATÉGORIE TEXTE
     */
    private function calculateTextCategories($binary) {
        $categories = [
            'clarity' => range(1, 10),
            'hook' => range(11, 15),
            'structure' => range(16, 20),
            'specificity' => range(21, 25),
            'platform_fit' => range(26, 30),
            'group_specific' => range(31, 40),
            'legend_caption' => range(41, 45),
            'hashtags' => range(46, 50)
        ];

        $result = [];
        foreach ($categories as $category => $indices) {
            $yes = 0;
            $no = 0;
            $failed = [];

            foreach ($indices as $i) {
                $key = "T$i";
                if ($binary[$key] === 'YES') {
                    $yes++;
                } else {
                    $no++;
                    $failed[] = $key;
                }
            }

            $result[$category] = [
                'yes' => $yes,
                'no' => $no,
                'failed' => $failed
            ];
        }

        return $result;
    }

    /**
     * ANALYSE IMAGES AVEC GOOGLE VISION
     */
    private function analyzeImages($group, $data) {
        try {
            if (empty($data['image_urls'])) {
                // Pas d'images à analyser
                return [
                    'by_category' => $this->getEmptyImageCategories(),
                    'total_yes' => 0,
                    'total_no' => 50,
                    'improvements' => [
                        'Ajoutez des visuels pour améliorer l\'impact de votre contenu',
                        'Utilisez des images de qualité adaptées au format',
                        'Respectez les dimensions recommandées pour la plateforme'
                    ]
                ];
            }

            // Télécharger et analyser chaque image
            $imageAnalyses = [];
            foreach ($data['image_urls'] as $url) {
                $imageAnalyses[] = $this->analyzeImageWithVision($url, $group, $data);
            }

            // Calculer les critères V1-V50
            $visionResults = $this->calculateVisionCriteria($imageAnalyses, $group, $data);

            // Regrouper par catégories
            $byCategory = $this->calculateImageCategories($visionResults);

            // Compter les totaux
            $totalYes = array_sum(array_map(function($cat) { return $cat['yes']; }, $byCategory));
            $totalNo = 50 - $totalYes;

            // Générer les améliorations basées sur les échecs
            $improvements = $this->generateImageImprovements($visionResults, $data['language']);

            return [
                'by_category' => $byCategory,
                'total_yes' => $totalYes,
                'total_no' => $totalNo,
                'improvements' => $improvements
            ];

        } catch (Exception $e) {
            throw new Exception("Erreur analyse images: " . $e->getMessage());
        }
    }

    /**
     * ANALYSER UNE IMAGE AVEC VISION API
     */
    private function analyzeImageWithVision($imageUrl, $group, $data) {
        // Télécharger l'image
        $imageData = $this->downloadImage($imageUrl);
        
        // Appeler Vision API
        $visionData = [
            'requests' => [
                [
                    'image' => [
                        'content' => base64_encode($imageData)
                    ],
                    'features' => [
                        ['type' => 'LABEL_DETECTION', 'maxResults' => 20],
                        ['type' => 'TEXT_DETECTION'],
                        ['type' => 'IMAGE_PROPERTIES'],
                        ['type' => 'SAFE_SEARCH_DETECTION'],
                        ['type' => 'OBJECT_LOCALIZATION', 'maxResults' => 10]
                    ]
                ]
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://vision.googleapis.com/v1/images:annotate?key=' . $this->googleCloudApiKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($visionData),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Erreur Vision API: HTTP $httpCode");
        }

        $result = json_decode($response, true);
        if (!$result || !isset($result['responses'][0])) {
            throw new Exception("Réponse Vision API invalide");
        }

        // Extraire les dimensions de l'image
        $imageInfo = getimagesizefromstring($imageData);
        $dimensions = [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
            'size' => strlen($imageData)
        ];

        return [
            'vision_response' => $result['responses'][0],
            'dimensions' => $dimensions,
            'url' => $imageUrl
        ];
    }

    /**
     * TÉLÉCHARGER UNE IMAGE
     */
    private function downloadImage($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERAGENT => 'PostMaster-Analyzer/1.0'
        ]);

        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new Exception("Impossible de télécharger l'image: HTTP $httpCode");
        }

        if (!str_starts_with($contentType, 'image/')) {
            throw new Exception("Le fichier n'est pas une image valide");
        }

        if (strlen($data) > 10 * 1024 * 1024) { // 10MB
            throw new Exception("Image trop volumineuse (max 10MB)");
        }

        return $data;
    }

    /**
     * CALCULER LES CRITÈRES VISION V1-V50
     */
    private function calculateVisionCriteria($imageAnalyses, $group, $data) {
        $results = [];
        $thresholds = PostMasterConstants::getAnalysisThresholds()['image'];

        // Pour chaque critère V1-V50
        for ($i = 1; $i <= 50; $i++) {
            $key = "V$i";
            $results[$key] = $this->evaluateVisionCriterion($i, $imageAnalyses, $group, $data, $thresholds);
        }

        return $results;
    }

    /**
     * ÉVALUER UN CRITÈRE VISION SPÉCIFIQUE
     */
    private function evaluateVisionCriterion($criterionNumber, $imageAnalyses, $group, $data, $thresholds) {
        // Pour un carrousel, un critère n'est OUI que si TOUTES les images le valident
        $isCarousel = $data['image_mode'] === 'carousel' || count($imageAnalyses) > 1;

        switch ($criterionNumber) {
            case 1: // V1: Objets localisés par image ≤ 6
                foreach ($imageAnalyses as $analysis) {
                    $objects = $analysis['vision_response']['localizedObjectAnnotations'] ?? [];
                    if (count($objects) > $thresholds['max_objects_per_image']) {
                        return 'NO';
                    }
                }
                return 'YES';

            case 2: // V2: Sujet principal occupe entre 15 et 70% de l'image
                foreach ($imageAnalyses as $analysis) {
                    $objects = $analysis['vision_response']['localizedObjectAnnotations'] ?? [];
                    if (empty($objects)) continue;
                    
                    $mainObject = $objects[0]; // Le premier est généralement le plus confiant
                    $boundingPoly = $mainObject['boundingPoly']['normalizedVertices'] ?? [];
                    
                    if (count($boundingPoly) >= 4) {
                        $area = $this->calculatePolygonArea($boundingPoly);
                        $areaPercent = $area * 100;
                        
                        if ($areaPercent < $thresholds['subject_min_area_percent'] || 
                            $areaPercent > $thresholds['subject_max_area_percent']) {
                            return 'NO';
                        }
                    }
                }
                return 'YES';

            case 3: // V3: Pas de visage sauf groupe 3 format single
                $allowFaces = ($group === 3 && $data['image_mode'] === 'single');
                if (!$allowFaces) {
                    foreach ($imageAnalyses as $analysis) {
                        $labels = $analysis['vision_response']['labelAnnotations'] ?? [];
                        foreach ($labels as $label) {
                            if (in_array(strtolower($label['description']), ['face', 'person', 'human face']) && 
                                $label['score'] > 0.8) {
                                return 'NO';
                            }
                        }
                    }
                }
                return 'YES';

            case 11: // V11: OCR total caractères par image entre limites
                foreach ($imageAnalyses as $analysis) {
                    $textAnnotation = $analysis['vision_response']['fullTextAnnotation']['text'] ?? '';
                    $charCount = strlen($textAnnotation);
                    
                    $minChars = $thresholds['ocr_min_chars'];
                    $maxChars = $thresholds['ocr_max_chars_single'];
                    
                    // Ajuster selon le format
                    if ($isCarousel) {
                        $maxChars = $thresholds['ocr_max_chars_carrousel'];
                    } elseif ($group === 5) { // Stories
                        $maxChars = $thresholds['ocr_max_chars_story'];
                    }
                    
                    if ($charCount < $minChars || $charCount > $maxChars) {
                        return 'NO';
                    }
                }
                return 'YES';

            case 31: // V31: Résolution minimale par format
                $requirements = PostMasterConstants::getImageRequirements()[$group];
                
                foreach ($imageAnalyses as $analysis) {
                    $width = $analysis['dimensions']['width'];
                    $height = $analysis['dimensions']['height'];
                    
                    $minWidth = $requirements['min_width'] ?? 1080;
                    $minHeight = $requirements['min_height'] ?? 1080;
                    
                    if ($width < $minWidth || $height < $minHeight) {
                        return 'NO';
                    }
                }
                return 'YES';

            case 32: // V32: Ratio conforme (tolérance ±3%)
                $requirements = PostMasterConstants::getImageRequirements()[$group];
                $expectedRatio = $requirements['ratio'] ?? 1.0;
                $tolerance = $requirements['tolerance'] ?? 0.03;
                
                foreach ($imageAnalyses as $analysis) {
                    $width = $analysis['dimensions']['width'];
                    $height = $analysis['dimensions']['height'];
                    $actualRatio = $width / $height;
                    
                    $deviation = abs($actualRatio - $expectedRatio) / $expectedRatio;
                    if ($deviation > $tolerance) {
                        return 'NO';
                    }
                }
                return 'YES';

            // Ajouter d'autres critères selon le même pattern...
            // Pour la démo, on va simuler quelques autres critères importants

            case 39: // V39: Taille de fichier ≥ 60KB
                foreach ($imageAnalyses as $analysis) {
                    $sizeKB = $analysis['dimensions']['size'] / 1024;
                    if ($sizeKB < $thresholds['min_file_size_kb']) {
                        return 'NO';
                    }
                }
                return 'YES';

            case 47: // V47: Carrousel première slide titre ≤ 8 mots
                if ($isCarousel && !empty($imageAnalyses)) {
                    $firstImage = $imageAnalyses[0];
                    $text = $firstImage['vision_response']['fullTextAnnotation']['text'] ?? '';
                    $wordCount = str_word_count($text);
                    
                    if ($wordCount > $thresholds['carrousel_title_max_words']) {
                        return 'NO';
                    }
                }
                return 'YES';

            default:
                // Pour les critères non encore implémentés, retourner YES par défaut
                // Dans une version complète, tous les critères V1-V50 seraient implémentés
                return 'YES';
        }
    }

    /**
     * CALCULER L'AIRE D'UN POLYGONE NORMALISÉ
     */
    private function calculatePolygonArea($vertices) {
        $area = 0;
        $n = count($vertices);
        
        for ($i = 0; $i < $n; $i++) {
            $j = ($i + 1) % $n;
            $area += $vertices[$i]['x'] * $vertices[$j]['y'];
            $area -= $vertices[$j]['x'] * $vertices[$i]['y'];
        }
        
        return abs($area) / 2;
    }

    /**
     * CALCULER LES SCORES PAR CATÉGORIE IMAGE
     */
    private function calculateImageCategories($visionResults) {
        $categories = [
            'composition' => range(1, 10),
            'readability' => range(11, 20),
            'branding' => range(21, 30),
            'quality' => range(31, 40),
            'format_fit' => range(41, 50)
        ];

        $result = [];
        foreach ($categories as $category => $indices) {
            $yes = 0;
            $no = 0;
            $failed = [];

            foreach ($indices as $i) {
                $key = "V$i";
                if ($visionResults[$key] === 'YES') {
                    $yes++;
                } else {
                    $no++;
                    $failed[] = $key;
                }
            }

            $result[$category] = [
                'yes' => $yes,
                'no' => $no,
                'failed' => $failed
            ];
        }

        return $result;
    }

    /**
     * GÉNÉRER LES AMÉLIORATIONS IMAGE
     */
    private function generateImageImprovements($visionResults, $language) {
        $improvements = PostMasterConstants::getImageImprovements()[$language] ?? 
                       PostMasterConstants::getImageImprovements()['fr'];
        
        $suggestions = [];
        foreach ($visionResults as $key => $result) {
            if ($result === 'NO' && isset($improvements[$key])) {
                $suggestions[] = $improvements[$key];
            }
        }

        // Prendre les 3 premières ou compléter avec des suggestions génériques
        $final = array_slice($suggestions, 0, 3);
        
        while (count($final) < 3) {
            $generic = $language === 'en' ? 
                "Improve image quality and composition" : 
                "Améliorez la qualité et la composition de l'image";
            $final[] = $generic;
        }

        return $final;
    }

    /**
     * CATÉGORIES IMAGE VIDES
     */
    private function getEmptyImageCategories() {
        return [
            'composition' => ['yes' => 0, 'no' => 10, 'failed' => array_map(function($i) { return "V$i"; }, range(1, 10))],
            'readability' => ['yes' => 0, 'no' => 10, 'failed' => array_map(function($i) { return "V$i"; }, range(11, 20))],
            'branding' => ['yes' => 0, 'no' => 10, 'failed' => array_map(function($i) { return "V$i"; }, range(21, 30))],
            'quality' => ['yes' => 0, 'no' => 10, 'failed' => array_map(function($i) { return "V$i"; }, range(31, 40))],
            'format_fit' => ['yes' => 0, 'no' => 10, 'failed' => array_map(function($i) { return "V$i"; }, range(41, 50))]
        ];
    }

    /**
     * CALCULER LE SCORE COMBINÉ
     */
    private function calculateCombinedScore($group, $textScoring, $imageScoring) {
        $weights = PostMasterConstants::getGroupWeights()[$group];
        
        // Calculer le score texte pondéré selon les règles internes
        $textScore = $this->calculateWeightedTextScore($textScoring);
        
        // Le score image est directement le pourcentage de YES sur 50
        $imageScore = ($imageScoring['total_yes'] / 50) * 100;
        
        // Score combiné
        $combinedScore = round(
            ($textScore * $weights['text']) + 
            ($imageScore * $weights['image'])
        );

        return [
            'weights' => $weights,
            'score_out_of_100' => $combinedScore
        ];
    }

    /**
     * CALCULER LE SCORE TEXTE PONDÉRÉ
     */
    private function calculateWeightedTextScore($textScoring) {
        $categories = $textScoring['by_category'];
        
        // Tronc commun (T1-T30) = 60% du score texte
        $trunkYes = $categories['clarity']['yes'] + 
                   $categories['hook']['yes'] + 
                   $categories['structure']['yes'] + 
                   $categories['specificity']['yes'] + 
                   $categories['platform_fit']['yes'];
        $trunkScore = ($trunkYes / 30) * 60;
        
        // Spécifiques groupe (T31-T40) = 25% du score texte
        $specificScore = ($categories['group_specific']['yes'] / 10) * 25;
        
        // Légende et hashtags (T41-T50) = 15% du score texte
        $metaYes = $categories['legend_caption']['yes'] + $categories['hashtags']['yes'];
        $metaScore = ($metaYes / 10) * 15;
        
        return $trunkScore + $specificScore + $metaScore;
    }
}
?>
