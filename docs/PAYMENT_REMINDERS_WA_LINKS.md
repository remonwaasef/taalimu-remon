# Rappels de paiement — WhatsApp gratuit via liens Telegram « click-to-send »

## But
Envoyer les rappels de paiement aux parents **via WhatsApp sans payer l'API Meta**.
Chaque matin, le centre reçoit sur **son Telegram** un message listant les élèves impayés,
avec un **bouton par élève**. Un clic ouvre WhatsApp pré-rempli (lien `wa.me`) → le staff
appuie sur « Envoyer ». Zéro coût Meta, zéro template à faire valider.

C'est un **complément** au système existant `reminders:send-payment` (email + WhatsApp API),
pas un remplacement. On réutilise la même détection d'impayés, les mêmes modèles de message
et la même table anti-doublon `payment_reminders`.

## Fonctionnement
1. Commande planifiée `reminders:send-wa-links` — tous les jours à **08:05** (`routes/console.php`).
2. Pour chaque centre ayant **activé** l'option et renseigné un **chat Telegram** :
   - élèves `status = active`, non payés ce mois-ci (aucune `sale` `paid` ce mois),
   - échéance **dans `wa_days_before` jours** (défaut 3) **ou dépassée** (relance quotidienne).
3. Construit un **digest Telegram** (par lots de 20) avec un bouton `wa.me` par élève, envoyé
   au `telegram_chat_id` du centre via `TelegramService::sendToChat()`.
4. Anti-doublon : une ligne `payment_reminders` par élève et par **jour**
   (`stage = watg_AAAA-MM-JJ`, canal `telegram_wa`), écrite seulement si l'envoi Telegram réussit.

## Fichiers ajoutés / modifiés
- `app/Console/Commands/SendWaLinkRemindersCommand.php` — **nouveau** (la commande).
- `app/Services/TelegramService.php` — **+** méthode `sendToChat($chatId, $message, $inlineButtons)` (additif, boutons URL inline).
- `Modules/Center/app/Services/SettingsService.php` — stocke `wa_telegram_enabled`, `telegram_chat_id`, `wa_days_before`.
- `Modules/Center/app/Http/Controllers/SettingsController.php` — validation des 3 champs.
- `routes/console.php` — planification 08:05.

## Réglages (JSON `tenant.settings['payment_reminders']`)
| Clé | Type | Défaut | Rôle |
|---|---|---|---|
| `wa_telegram_enabled` | bool | false | Active le canal pour ce centre |
| `telegram_chat_id` | string | null | Chat/groupe Telegram qui reçoit les digests |
| `wa_days_before` | int | 3 | Nb de jours avant l'échéance pour le 1er rappel |

## Configuration côté centre (à faire une fois)
1. **Bot Telegram** : le projet utilise déjà un bot global (`TELEGRAM_BOT_TOKEN`). Le centre
   n'a pas besoin de créer un bot — il doit juste obtenir **son `chat_id`** :
   - démarrer une conversation avec le bot (ou l'ajouter à un groupe du centre),
   - récupérer le `chat_id` via `https://api.telegram.org/bot<TOKEN>/getUpdates` (champ `chat.id`).
2. Coller ce `chat_id` dans les réglages de rappels, cocher « Activer », choisir le nb de jours.

> Note multi-tenant : aujourd'hui `TELEGRAM_BOT_TOKEN` est global. `sendToChat` accepte un
> `chat_id` par centre, ce qui suffit pour router les messages au bon centre avec un seul bot.
> Si un jour on veut un bot par centre, ajouter un `telegram_bot_token` par tenant.

## Interface à ajouter (snippet Blade)
À insérer dans le formulaire de rappels (`Modules/Center/resources/views/settings/index.blade.php`
et/ou `Modules/Instructor/resources/views/settings.blade.php`), à l'intérieur du `<form>` qui
poste vers `reminders.update` :

```blade
@php($pr = ($tenant->settings['payment_reminders'] ?? []))
<div class="card mb-3">
    <div class="card-body">
        <h5 class="mb-3">📲 Rappels WhatsApp gratuits (via Telegram)</h5>
        <p class="text-muted small">
            Recevez chaque matin sur Telegram la liste des impayés, avec un bouton par parent
            pour envoyer le rappel WhatsApp en un clic — sans l'API payante de Meta.
        </p>

        <div class="form-check form-switch mb-3">
            <input type="hidden" name="wa_telegram_enabled" value="0">
            <input class="form-check-input" type="checkbox" id="wa_telegram_enabled"
                   name="wa_telegram_enabled" value="1"
                   {{ ($pr['wa_telegram_enabled'] ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="wa_telegram_enabled">Activer ce canal</label>
        </div>

        <div class="mb-3">
            <label class="form-label">Chat ID Telegram du centre</label>
            <input type="text" class="form-control" name="telegram_chat_id"
                   value="{{ $pr['telegram_chat_id'] ?? '' }}" placeholder="ex. 123456789">
            <small class="text-muted">
                Démarrez une conversation avec notre bot, puis récupérez votre chat_id.
            </small>
        </div>

        <div class="mb-3">
            <label class="form-label">Premier rappel — jours avant l'échéance</label>
            <input type="number" min="0" max="28" class="form-control" name="wa_days_before"
                   value="{{ $pr['wa_days_before'] ?? 3 }}">
        </div>
    </div>
</div>
```

## Tester (Remon, en local)
```bash
# Configurer un tenant de test avec wa_telegram_enabled=true + un telegram_chat_id valide,
# au moins un élève actif impayé dont l'échéance tombe aujourd'hui/-3j ou est dépassée, puis :
php artisan reminders:send-wa-links --tenant=<ID>
```
Vérifier : un message Telegram arrive dans le chat, chaque bouton ouvre WhatsApp pré-rempli
avec le bon numéro et le bon texte ; relancer la commande le même jour n'envoie pas de doublon.

## Limites connues
- **Semi-automatique** : un clic humain par parent (voulu — c'est ce qui rend le canal gratuit).
  Pour de très gros volumes, garder l'email auto en complément.
- Normalisation du téléphone heuristique (`normalizePhone`) : indicatif pris de
  `settings['whatsapp']['country_code']`, défaut Égypte (20). Vérifier pour d'autres pays.
- Léger recouvrement de logique avec `SendPaymentRemindersCommand` (sélection des impayés) —
  à factoriser plus tard dans un service partagé si besoin.

> ⚠️ Non testé automatiquement (pas d'environnement PHP côté autrice). Relecture manuelle OK.
> Lancer `php artisan test` + un essai manuel avant merge.
