<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333; line-height: 1.6;">
    <h2 style="color: #2c3e50;">Salut {{ $user->name }},</h2>
    
    <div style="background-color: #fdf2e9; border-left: 4px solid #e67e22; padding: 15px; margin: 20px 0; font-style: italic;">
        <p style="margin: 0;">"Avant Taalimu : 10h/semaine en compta manuelle. Maintenant : 100% automatisé, +30% revenus récupérés !"</p>
        <p style="margin-top: 10px; font-size: 0.9em; color: #7f8c8d;">– Directeur Centre Y, Caire.</p>
    </div>

    <h3 style="color: #34495e;">Prochaines étapes pour vous :</h3>
    <ul style="list-style-type: disc; padding-left: 20px;">
        <li style="margin-bottom: 10px;">Ajoutez vos profs (plan Croissance : 10 profs, 500 élèves)</li>
        <li style="margin-bottom: 10px;">Rapports financiers avancés</li>
        <li style="margin-bottom: 10px;">Support multi-branches</li>
    </ul>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.url') }}/admin/subscription" style="background-color: #e67e22; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Passer au plan Croissance
        </a>
    </div>

    <p style="color: #e74c3c; font-weight: bold;">
        Votre essai se termine dans {{ $trialDaysLeft }} jours.
    </p>

    <p style="margin-top: 20px;">
        <strong>Bonus :</strong> Répondez à cet email pour démo personnalisée gratuite !
    </p>

    <p style="margin-top: 30px; color: #7f8c8d;">
        Équipe Taalimu
    </p>
</div>
