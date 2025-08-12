<?php
/**
 * PostMaster™ Analysis System - GPT Prompts Builder
 * 
 * Générateur de prompts pour l'analyse texte par groupe
 * Version: 1.0
 * Date: 2025-08-11
 */

class PostMasterPrompts {

    /**
     * MESSAGE SYSTÈME COMMUN À TOUS LES GROUPES
     */
    public static function getSystemMessage() {
        return "Tu es un auditeur de contenu strict. Tu dois sortir uniquement du JSON. 
Objectif : décider OUI ou NON pour chaque critère texte T1 à T50 selon un profil de plateforme et format. 
Si tu manques d'indices, réponds NON. Donne exactement trois améliorations concrètes courtes et impératives en fin. 
Aucune note chiffrée, aucune prose hors JSON.";
    }

    /**
     * TEMPLATE MESSAGE UTILISATEUR COMMUN
     */
    public static function getUserMessageTemplate() {
        return "LANG={{language}}
PLATFORM={{platform}}
FORMAT={{format}}

PROFILE:
{{profile_bloc}}

TEXT_MAIN:
{{text_main}}

CAPTION:
{{caption}}

HASHTAGS:
{{hashtags_csv}}

Retourne STRICTEMENT ce JSON:
{
 \"binary\": {
   \"T1\":\"YES|NO\",\"T2\":\"YES|NO\",\"T3\":\"YES|NO\",\"T4\":\"YES|NO\",\"T5\":\"YES|NO\",
   \"T6\":\"YES|NO\",\"T7\":\"YES|NO\",\"T8\":\"YES|NO\",\"T9\":\"YES|NO\",\"T10\":\"YES|NO\",
   \"T11\":\"YES|NO\",\"T12\":\"YES|NO\",\"T13\":\"YES|NO\",\"T14\":\"YES|NO\",\"T15\":\"YES|NO\",
   \"T16\":\"YES|NO\",\"T17\":\"YES|NO\",\"T18\":\"YES|NO\",\"T19\":\"YES|NO\",\"T20\":\"YES|NO\",
   \"T21\":\"YES|NO\",\"T22\":\"YES|NO\",\"T23\":\"YES|NO\",\"T24\":\"YES|NO\",\"T25\":\"YES|NO\",
   \"T26\":\"YES|NO\",\"T27\":\"YES|NO\",\"T28\":\"YES|NO\",\"T29\":\"YES|NO\",\"T30\":\"YES|NO\",
   \"T31\":\"YES|NO\",\"T32\":\"YES|NO\",\"T33\":\"YES|NO\",\"T34\":\"YES|NO\",\"T35\":\"YES|NO\",
   \"T36\":\"YES|NO\",\"T37\":\"YES|NO\",\"T38\":\"YES|NO\",\"T39\":\"YES|NO\",\"T40\":\"YES|NO\",
   \"T41\":\"YES|NO\",\"T42\":\"YES|NO\",\"T43\":\"YES|NO\",\"T44\":\"YES|NO\",\"T45\":\"YES|NO\",
   \"T46\":\"YES|NO\",\"T47\":\"YES|NO\",\"T48\":\"YES|NO\",\"T49\":\"YES|NO\",\"T50\":\"YES|NO\"
 },
 \"improvements\": [\"...\", \"...\", \"...\"]
}

Règles:
- JSON seulement.
- Si doute, NON.
- Améliorations impératives, très concrètes, max 220 caractères chacune, en LANG.";
    }

    /**
     * PROFILS SPÉCIFIQUES PAR GROUPE
     */
    
    /**
     * GROUPE 1 - SHORT VIDEO ET MULTI SÉQUENCES
     */
    public static function getGroup1Profile() {
        return "Premier écran : accroche en 80-120 caractères, verbe d'action, bénéfice rapide
Limites : éviter pavés, pas de liens sortants, emojis modérés, hashtags en fin
Ton : direct, énergique, concret
CTA typiques : Enregistre, Partage, Teste cette astuce, Essaye aujourd'hui
Tabous : ouverture molle, phrases meta, répétitions, claims vagues
Bonnes pratiques : angle unique, chiffres, délais précis, séquence claire

Critères spécifiques Groupe 1 (T31-T40):
T31: Accroche orientée bénéfice immédiat dans les 80 premiers caractères
T32: Présence d'un angle unique clair, pas de double sujet
T33: Langage oral direct, phrases courtes
T34: Pas d'appel à un lien externe
T35: Utilisation d'au moins un marqueur d'action (teste, essaye, fais)
T36: Pas de filler meta du type 'like et abonne-toi' en première ligne
T37: Rythme implicite via verbes d'action et énumérations limitées
T38: Contrainte temps respectée (shorts 60s, tiktok 3min)
T39: Pas d'auto-promotion gratuite sans valeur
T40: Si multi-séquences, annonce explicite de la progression";
    }

    /**
     * GROUPE 2 - IMAGE ET CARROUSEL CRÉATIF
     */
    public static function getGroup2Profile() {
        return "Premier écran : titre accroche court slide 1, texte court par slide, incitation au swipe
Limites : 2-10 slides, 50-150 mots par slide, pas d'URL en accroche
Ton : clair, visuel, bénéfice, professionnel accessible
CTA typiques : Swipe jusqu'à la fin, Enregistre, Commente ton cas
Tabous : paragraphes denses sur une slide, jargon, hashtags dans l'accroche
Bonnes pratiques : étapes numérotées, exemples concrets, CTA final unique

Critères spécifiques Groupe 2 (T31-T40):
T31: Titre court slide 1 (inférieur à 8 mots dans le texte associé)
T32: Présence de balises de progression (slide, étape, page) dans le texte
T33: Un exemple concret au moins
T34: Alternance texte court et respiration visuelle suggérée
T35: CTA final unique sans répétition
T36: Pas d'URL en accroche textuelle
T37: Au moins un mot-clé de niche
T38: Présence d'un chiffre clé dans une slide centrale
T39: Vocabulaire descriptif précis pour chaque slide
T40: Répétitions trans-slide limitées";
    }

    /**
     * GROUPE 3 - PRO ET LONG FORMAT
     */
    public static function getGroup3Profile() {
        return "Premier écran : idée forte en 120 caractères, ton professionnel, aucun hashtag première ligne
Limites : texte en paragraphes, emojis maximum 3
Ton : professionnel, précis, sourcé
CTA typiques : Partage ton retour, Dis-moi ton approche, Contacte-moi
Tabous : sensationnalisme, répétitions
Bonnes pratiques : données, exemples, clarification terminologique, message unique

Critères spécifiques Groupe 3 (T31-T40):
T31: Ton professionnel stable, pas de slang
T32: Argument appuyé par donnée ou source indicative
T33: Au moins deux paragraphes de plusieurs lignes
T34: Pas plus de 3 emojis et aucun juron
T35: Conclusion claire avec ouverture (question ou next step)
T36: Terminologie cohérente terrain expert
T37: Un exemple ou mini étude de cas
T38: Pas d'accroche sensationnaliste
T39: Aucun hashtag dans la première ligne
T40: Si document ou article, présence d'un plan ou sections";
    }

    /**
     * GROUPE 4 - MICROBLOG ET THREADS
     */
    public static function getGroup4Profile() {
        return "Premier écran : promesse claire pour tweet 1, pas d'URL ni hashtag première ligne
Limites : single ≤280 caractères, thread ≤2500
Ton : tranchant, dense, lisible, concret
CTA typiques : RT si utile, Réponds avec ton cas, Teste ce format
Tabous : fils sans structure, vagues généralités, spam emoji ou hashtag
Bonnes pratiques : chiffres, verbes d'action, enchaînement logique par tweet

Critères spécifiques Groupe 4 (T31-T40):
T31: 1er tweet = phrase promesse claire
T32: Si thread, chaque bloc avance une idée unique
T33: Total thread inférieur ou égal à 2500 caractères
T34: Pas d'URL ni hashtag en première ligne
T35: Hashtags maximum deux en fin
T36: Enchaînements logiques marqués par connecteurs
T37: CTA adapté (RT, réponds, partage ton cas)
T38: Pas de spam d'emoji
T39: Mentions limitées et pertinentes
T40: Dernier tweet inclut une synthèse brève";
    }

    /**
     * GROUPE 5 - STORIES ÉPHÉMÈRES
     */
    public static function getGroup5Profile() {
        return "Premier écran : message principal en moins de deux lignes, pas de texte dense
Limites : très peu de texte, aucun empilement de hashtags
Ton : conversationnel, proche
CTA typiques : Voir plus, Enregistrer, Réponds
Tabous : liens non natifs, texte long
Bonnes pratiques : objectif unique, progression si séquence

Critères spécifiques Groupe 5 (T31-T40):
T31: Ton conversationnel proche réel
T32: Message principal compris en moins de 2 lignes
T33: Aucun texte dense
T34: Un seul objectif par story
T35: CTA clair en fin (swipe up équivalent, enregistrer)
T36: Évite tout lien externe visible si non natif
T37: Texte très court (moins de 120 caractères total conseillé)
T38: Pas d'empilement de hashtags
T39: Pas de répétition identique d'une story à l'autre
T40: Si séquence, mention d'une progression";
    }

    /**
     * BUILDER DE MESSAGE GPT
     */
    public static function buildGptMessage($group, $language, $platform, $format, $textBlocks, $caption = '', $hashtags = []) {
        $profiles = [
            1 => self::getGroup1Profile(),
            2 => self::getGroup2Profile(),
            3 => self::getGroup3Profile(),
            4 => self::getGroup4Profile(),
            5 => self::getGroup5Profile()
        ];

        $textMain = implode("\n\n", $textBlocks);
        $hashtagsCsv = implode(', ', $hashtags);

        $userMessage = str_replace([
            '{{language}}',
            '{{platform}}',
            '{{format}}',
            '{{profile_bloc}}',
            '{{text_main}}',
            '{{caption}}',
            '{{hashtags_csv}}'
        ], [
            $language,
            $platform,
            $format,
            $profiles[$group],
            $textMain,
            $caption,
            $hashtagsCsv
        ], self::getUserMessageTemplate());

        return [
            'system' => self::getSystemMessage(),
            'user' => $userMessage
        ];
    }

    /**
     * CRITÈRES TEXTE T1-T50 POUR RÉFÉRENCE
     */
    public static function getTextCriteria() {
        return [
            // T1-T10: Clarity
            'T1' => 'Langage simple sans jargon',
            'T2' => 'Idée principale visible dans les 200 premiers caractères',
            'T3' => 'Moyenne de phrase entre 8 et 24 mots',
            'T4' => 'Pas de tout en majuscules ni triple point d\'exclamation ni double espace récurrent',
            'T5' => 'Pas de contradictions détectées',
            'T6' => 'Pas d\'ouverture vide type "dans cette vidéo je vais"',
            'T7' => 'Nombres écrits de façon cohérente (point ou virgule uniforme)',
            'T8' => 'Emojis maximum 6 au total et jamais plus de 3 d\'affilée',
            'T9' => 'Pas de phrase strictement identique répétée',
            'T10' => 'Langue conforme au champ language',

            // T11-T15: Hook
            'T11' => 'Accroche promesse/tension/curiosité dans les 120 premiers caractères',
            'T12' => 'Un verbe d\'action fort dès la première ligne',
            'T13' => 'Pas d\'ouverture faible type "peut-être, je pense que"',
            'T14' => 'Première ligne longueur inférieure ou égale à 120 caractères',
            'T15' => 'Aucun hashtag dans la première ligne',

            // T16-T20: Structure
            'T16' => 'Bénéfice concret cité dans les 200 premiers caractères',
            'T17' => 'Si format court (reels/shorts/tiktok), action/verbe dans les 80 premiers caractères',
            'T18' => 'Un seul CTA global situé dans le dernier tiers du texte total',
            'T19' => 'Pas d\'URL dans la première ligne',
            'T20' => 'Mentions totales inférieures ou égales à 3',

            // T21-T25: Specificity
            'T21' => 'Présence d\'au moins un nombre ou délai précis',
            'T22' => 'Présence d\'au moins un outil/méthode/framework connu',
            'T23' => 'Présence d\'au moins une formulation d\'exemple',
            'T24' => 'Au moins un verbe impératif dans le CTA',
            'T25' => 'Évite intensificateurs vagues (ratio de "très/trop" sans mesure inférieur à seuil)',

            // T26-T30: Platform fit
            'T26' => 'Respect des limites de caractères de la plateforme et du format',
            'T27' => 'Hashtags si présents sont regroupés en fin du texte principal',
            'T28' => 'Pas de duplication de hashtags',
            'T29' => 'Sauts de ligne suffisants (au moins un tous les 300 caractères)',
            'T30' => 'Nombre de blocs conforme au format (carrousel/thread au moins deux, sinon un)',

            // T31-T40: Spécifiques par groupe (voir profils ci-dessus)

            // T41-T45: Legend caption
            'T41' => 'Légende présente si pertinente au format, sinon vide acceptable',
            'T42' => 'Légende ne répète pas mot à mot la première ligne',
            'T43' => 'Légende apporte un exemple concret ou un détail utile',
            'T44' => 'Légende contient un seul CTA',
            'T45' => 'Légende sans lien externe pour groupes 1 et 5',

            // T46-T50: Hashtags
            'T46' => 'Hashtags placés en fin',
            'T47' => 'Aucun doublon',
            'T48' => 'Volume par groupe (Groupe1: 0-5, Groupe2: 3-10, Groupe3: 0-3, Groupe4: 0-2, Groupe5: 0-3)',
            'T49' => 'Hashtags pertinents sémantiquement par rapport au texte',
            'T50' => 'Pas de hashtag dans la première ligne'
        ];
    }
}
?>
