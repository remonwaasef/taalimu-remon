#!/bin/bash

# ==============================================================================
# Script to rotate compromised secrets
# ==============================================================================

echo "WARNING: This will generate a new APP_KEY."
echo "Doing this will invalidate all existing sessions and encrypted data."
read -p "Are you sure you want to proceed? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan key:generate --force
    echo "APP_KEY regenerated successfully."
    
    echo ""
    echo "====================================================================="
    echo "ACTION REQUIRED: You MUST also rotate the following secrets manually:"
    echo "====================================================================="
    echo "1. DB_PASSWORD (Change in your database server and update .env)"
    echo "2. STRIPE_SECRET (Revoke old key in Stripe dashboard and generate new one)"
    echo "3. PAYPAL_CLIENT_SECRET (Regenerate in PayPal developer portal)"
    echo "4. TELEGRAM_BOT_TOKEN (Revoke via @BotFather in Telegram)"
    echo "5. GOOGLE_CLIENT_SECRET (Reset in Google Cloud Console)"
    echo "6. MAIL_PASSWORD (Change your SMTP password)"
    echo "7. REVERB_APP_SECRET (Generate a new random string)"
    echo "====================================================================="
    echo "Once all secrets are updated, run: php artisan config:clear && php artisan cache:clear"
fi
