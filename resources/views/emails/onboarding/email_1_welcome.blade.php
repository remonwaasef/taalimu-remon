<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333; line-height: 1.6;">
    <h2 style="color: #2c3e50;">Bonjour {{ $user->name }},</h2>
    <p>Bienvenue sur Taalimu, le système n°1 pour centres éducatifs ! 🎓</p>
    
    <div style="background-color: #f8f9fa; border-left: 4px solid #4CAF50; padding: 15px; margin: 20px 0;">
        <p style="margin: 0;"><strong>Votre "Aha!" Moment :</strong> Ajoutez vos 10 premiers élèves en 2 clics et gagnez 10h/semaine dès aujourd'hui.</p>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.url') }}/login" style="background-color: #4CAF50; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Vérifier & Ajouter mes élèves
        </a>
    </div>

    <ul style="list-style-type: none; padding-left: 0;">
        <li style="margin-bottom: 10px;">✓ Facturation auto</li>
        <li style="margin-bottom: 10px;">✓ Suivi présence</li>
        <li style="margin-bottom: 10px;">✓ Alertes WhatsApp</li>
    </ul>

    <p style="margin-top: 30px; font-weight: bold; color: #e74c3c;">
        Votre essai gratuit de {{ $trialDaysLeft }} jours commence maintenant !
    </p>

    <p style="margin-top: 30px; color: #7f8c8d;">
        Équipe Taalimu
    </p>
</div>
