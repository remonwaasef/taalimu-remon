<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333; line-height: 1.6;">
    <h2 style="color: #2c3e50;">Bonjour {{ $user->name }},</h2>
    
    <p><strong>Problème :</strong> 30% des revenus perdus par manque de suivi des paiements.</p>
    <p><strong>Solution Taalimu :</strong> Factures auto + relances WhatsApp pour chaque parent.</p>

    <h3 style="color: #34495e; margin-top: 25px;">Comment ?</h3>
    <ul style="list-style-type: disc; padding-left: 20px;">
        <li style="margin-bottom: 10px;">Définissez vos tarifs (mensuel/trimestriel)</li>
        <li style="margin-bottom: 10px;">Activez alertes WhatsApp Business</li>
        <li style="margin-bottom: 10px;">Le système envoie rappels 3 jours avant échéance</li>
    </ul>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.url') }}/admin/settings/financial" style="background-color: #9b59b6; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Configurer ma facturation automatique
        </a>
    </div>

    <div style="border-left: 4px solid #f1c40f; padding-left: 15px; margin-top: 20px; color: #555;">
        <p style="margin: 0;"><em>Cas réel : Centre X a récupéré 98% des paiements à temps !</em></p>
    </div>

    <p style="margin-top: 30px; color: #7f8c8d;">
        Équipe Taalimu
    </p>
</div>
