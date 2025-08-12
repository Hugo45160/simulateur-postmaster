<?php
/**
 * PostMaster™ Analysis System - Main Entry Point
 * 
 * Point d'entrée principal pour l'analyse des posts
 * Version: 1.0
 * Date: 2025-08-11
 */

require_once 'postmaster_analyzer.php';

class PostMasterAPI {

    private $analyzer;

    public function __construct($config = []) {
        // Configuration par défaut
        $defaultConfig = [
            'openai_api_key' => '', // À configurer
            'google_cloud_api_key' => '', // À configurer
            'brand_colors' => ['#FF5A00', '#FFFFFF', '#000000'], // Couleurs PostMaster™
            'brand_domain' => 'postmaster.com'
        ];

        $config = array_merge($defaultConfig, $config);

        $this->analyzer = new PostMasterAnalyzer(
            $config['openai_api_key'],
            $config['google_cloud_api_key'],
            $config['brand_colors'],
            $config['brand_domain']
        );
    }

    /**
     * ENDPOINT PRINCIPAL D'ANALYSE
     */
    public function analyze($payload) {
        try {
            // Log de la requête (optionnel)
            error_log("PostMaster Analysis Request: " . json_encode($payload));

            // Analyser le post
            $result = $this->analyzer->analyzePost($payload);

            // Log du résultat (optionnel)
            error_log("PostMaster Analysis Result: " . json_encode($result));

            return [
                'success' => true,
                'data' => $result,
                'timestamp' => date('c'),
                'version' => '1.0'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'timestamp' => date('c'),
                'version' => '1.0'
            ];
        }
    }

    /**
     * ENDPOINT POUR TESTER LA CONFIGURATION
     */
    public function healthCheck() {
        return [
            'success' => true,
            'message' => 'PostMaster™ Analysis System is ready',
            'version' => '1.0',
            'timestamp' => date('c')
        ];
    }
}

/**
 * EXEMPLES D'UTILISATION
 */

// Charger la configuration depuis le fichier config
$config = require_once 'postmaster_config.php';

$api = new PostMasterAPI($config);

/**
 * EXEMPLE 1: INSTAGRAM REEL (GROUPE 1)
 */
function exemple1_instagram_reel() {
    global $api;
    
    $payload = [
        'platform' => 'instagram',
        'format' => 'reel',
        'text_blocks' => [
            'Découvre cette astuce en 30 secondes pour booster tes ventes de 40% ! 
            
            Étape 1 : Identifie ton client idéal avec précision
            Étape 2 : Crée un message qui résonne avec ses besoins
            Étape 3 : Teste sur 100 prospects minimum
            
            Résultat garanti ou remboursé ! Teste dès aujourd\'hui cette méthode.'
        ],
        'caption' => 'Tu as testé cette approche ? Raconte-moi en commentaire !',
        'hashtags' => ['#marketing', '#vente', '#entrepreneur'],
        'image_mode' => 'single',
        'image_urls' => [
            'https://example.com/reel-cover.jpg'
        ],
        'language' => 'fr'
    ];

    return $api->analyze($payload);
}

/**
 * EXEMPLE 2: LINKEDIN ARTICLE (GROUPE 3)
 */
function exemple2_linkedin_article() {
    global $api;
    
    $payload = [
        'platform' => 'linkedin',
        'format' => 'article',
        'text_blocks' => [
            'Les 5 erreurs fatales en stratégie de contenu B2B (et comment les éviter)

            Après avoir analysé plus de 500 stratégies de contenu, voici les patterns d\'échec les plus récurrents :

            1. Manque de persona précis
            La plupart des entreprises ciblent "les décideurs" - trop vague. Définissez : directeur marketing, 100-500 employés, secteur SaaS, problème = acquisition client coûteuse.

            2. Absence de funnel de contenu
            80% produisent uniquement du contenu TOFU (top of funnel). Créez du contenu pour chaque étape : awareness, considération, décision.

            3. Metrics vanity au lieu de business metrics
            Les likes ne paient pas les factures. Mesurez : leads qualifiés, opportunités créées, revenus attribués.

            Quelle est votre plus grosse difficulté en content marketing B2B ?'
        ],
        'caption' => '',
        'hashtags' => ['#contentmarketing', '#b2b'],
        'image_mode' => 'single',
        'image_urls' => [
            'https://example.com/linkedin-hero.jpg'
        ],
        'language' => 'fr'
    ];

    return $api->analyze($payload);
}

/**
 * EXEMPLE 3: TWITTER THREAD (GROUPE 4)
 */
function exemple3_twitter_thread() {
    global $api;
    
    $payload = [
        'platform' => 'twitter',
        'format' => 'thread',
        'text_blocks' => [
            'Comment j\'ai automatisé 90% de ma prospection avec 3 outils gratuits 🧵',
            
            '1/ Le problème : 8h/jour à chercher des prospects
            - Recherche manuelle sur LinkedIn
            - Emails envoyés un par un
            - Suivi aléatoire des réponses
            
            Résultat : 2% de taux de réponse',
            
            '2/ La solution : Stack d\'automatisation
            - PhantomBuster pour scraper LinkedIn
            - Instantly.ai pour les séquences email
            - Notion pour le CRM et suivi
            
            Temps investi : 2h de setup',
            
            '3/ Les résultats après 30 jours :
            - 500 prospects qualifiés/semaine
            - 15% taux de réponse
            - 3h/semaine de maintenance
            
            ROI : 2300% vs méthode manuelle',
            
            '4/ Les 3 règles d\'or :
            - Personnaliser le 1er message
            - Suivre max 7 jours
            - Tester 3 angles différents
            
            Qui veut le template exact ? RT si ça t\'intéresse 👇'
        ],
        'caption' => '',
        'hashtags' => ['#prospection'],
        'image_mode' => 'single',
        'image_urls' => [],
        'language' => 'fr'
    ];

    return $api->analyze($payload);
}

/**
 * EXEMPLE 4: INSTAGRAM CARROUSEL (GROUPE 2)
 */
function exemple4_instagram_carrousel() {
    global $api;
    
    $payload = [
        'platform' => 'instagram',
        'format' => 'carrousel',
        'text_blocks' => [
            'Slide 1: 5 erreurs qui tuent tes ventes',
            'Slide 2: Erreur 1 - Prix trop bas = clients difficiles',
            'Slide 3: Erreur 2 - Pas de processus de vente défini',
            'Slide 4: Erreur 3 - Suivre tous les prospects sans qualification',
            'Slide 5: Erreur 4 - Vendre le produit au lieu du résultat',
            'Slide 6: Erreur 5 - Négliger le suivi post-vente',
            'Slide 7: BONUS - Ma checklist de vente parfaite'
        ],
        'caption' => 'Laquelle de ces erreurs tu reconnais ? Dis-moi en commentaire !',
        'hashtags' => ['#vente', '#business', '#entrepreneur', '#commercial', '#reussite'],
        'image_mode' => 'carousel',
        'image_urls' => [
            'https://example.com/slide1.jpg',
            'https://example.com/slide2.jpg',
            'https://example.com/slide3.jpg',
            'https://example.com/slide4.jpg',
            'https://example.com/slide5.jpg',
            'https://example.com/slide6.jpg',
            'https://example.com/slide7.jpg'
        ],
        'language' => 'fr'
    ];

    return $api->analyze($payload);
}

/**
 * POINT D'ENTRÉE POUR WORDPRESS
 */
if (isset($_POST['action']) && $_POST['action'] === 'postmaster_analyze') {
    // Vérification de sécurité (à implémenter selon vos besoins)
    // check_ajax_referer('postmaster_nonce', 'nonce');
    
    header('Content-Type: application/json');
    
    try {
        $payload = json_decode(file_get_contents('php://input'), true);
        
        if (!$payload) {
            throw new Exception('Payload JSON invalide');
        }
        
        $result = $api->analyze($payload);
        echo json_encode($result);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    
    exit;
}

/**
 * FONCTION POUR TESTER LES EXEMPLES
 */
function tester_exemples() {
    echo "<h1>Tests PostMaster™ Analysis System</h1>";
    
    $exemples = [
        'Instagram Reel' => 'exemple1_instagram_reel',
        'LinkedIn Article' => 'exemple2_linkedin_article',
        'Twitter Thread' => 'exemple3_twitter_thread',
        'Instagram Carrousel' => 'exemple4_instagram_carrousel'
    ];
    
    foreach ($exemples as $nom => $fonction) {
        echo "<h2>$nom</h2>";
        echo "<pre>";
        
        try {
            $result = $fonction();
            echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo "ERREUR: " . $e->getMessage();
        }
        
        echo "</pre><hr>";
    }
}

/**
 * SORTIE ATTENDUE POUR L'EXEMPLE 1 (INSTAGRAM REEL)
 */
/*
{
  "success": true,
  "data": {
    "text_scoring": {
      "by_category": {
        "clarity": {"yes": 8, "no": 2, "failed": ["T4", "T6"]},
        "hook": {"yes": 4, "no": 1, "failed": ["T13"]},
        "structure": {"yes": 5, "no": 0, "failed": []},
        "specificity": {"yes": 5, "no": 0, "failed": []},
        "platform_fit": {"yes": 4, "no": 1, "failed": ["T29"]},
        "group_specific": {"yes": 8, "no": 2, "failed": ["T36", "T38"]},
        "legend_caption": {"yes": 4, "no": 1, "failed": ["T45"]},
        "hashtags": {"yes": 5, "no": 0, "failed": []}
      },
      "total_yes": 43,
      "total_no": 7,
      "improvements": [
        "Réduisez le texte à 80 caractères max pour l'accroche",
        "Supprimez les phrases d'ouverture faibles",
        "Ajoutez plus de sauts de ligne pour aérer"
      ]
    },
    "image_scoring": {
      "by_category": {
        "composition": {"yes": 8, "no": 2, "failed": ["V2", "V5"]},
        "readability": {"yes": 7, "no": 3, "failed": ["V11", "V14", "V17"]},
        "branding": {"yes": 6, "no": 4, "failed": ["V21", "V22", "V26", "V29"]},
        "quality": {"yes": 8, "no": 2, "failed": ["V33", "V35"]},
        "format_fit": {"yes": 7, "no": 3, "failed": ["V41", "V44", "V49"]}
      },
      "total_yes": 36,
      "total_no": 14,
      "improvements": [
        "Adoptez le format portrait 9:16 pour les Reels",
        "Agrandissez la taille du texte (min 3% hauteur image)",
        "Intégrez vos couleurs de marque dans le visuel"
      ]
    },
    "combined": {
      "weights": {"text": 0.5, "image": 0.5},
      "score_out_of_100": 83
    }
  },
  "timestamp": "2025-08-11T15:30:00+00:00",
  "version": "1.0"
}
*/

/**
 * DOCUMENTATION DES AJUSTEMENTS POSSIBLES
 */

/*
AJUSTEMENTS CRITÈRES TEXTE :
- Modifier les seuils dans PostMasterConstants::getAnalysisThresholds()
- Ajouter/retirer des outils reconnus dans getRecognizedTools()
- Personnaliser les prompts dans PostMasterPrompts
- Ajuster les poids par groupe dans getGroupWeights()

AJUSTEMENTS CRITÈRES IMAGE :
- Modifier les seuils Vision dans getAnalysisThresholds()['image']
- Personnaliser les améliorations dans getImageImprovements()
- Ajuster les résolutions minimales dans getImageRequirements()
- Configurer les couleurs de marque dans le constructeur

AJOUT DE NOUVEAUX FORMATS :
1. Ajouter le mapping dans getPlatformGroupMapping()
2. Définir les limites dans getCharacterLimits()
3. Si nouveau groupe : créer le profil dans PostMasterPrompts
4. Ajuster les critères Vision si nécessaire

INTÉGRATION WORDPRESS :
1. Copier tous les fichiers PHP dans le thème/plugin
2. Configurer les clés API OpenAI et Google Cloud
3. Ajouter l'endpoint AJAX dans functions.php
4. Appeler depuis JavaScript avec les données PostMaster™

MONITORING ET LOGS :
- Activer error_log() pour tracer les requêtes
- Ajouter des métriques de performance
- Monitorer les taux d'erreur API
- Suivre la distribution des scores
*/
?>
