<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333; line-height: 1.6;">
    <h2 style="color: #2c3e50;">Salut {{ $user->name }},</h2>
    <p>Votre dashboard attend ! En 3 étapes simples :</p>
    
    <ol style="padding-left: 20px; margin-bottom: 25px;">
        <li style="margin-bottom: 10px;">Importez votre liste élèves (Excel/CSV)</li>
        <li style="margin-bottom: 10px;">Configurez vos cours & salles</li>
        <li style="margin-bottom: 10px;">Activez le suivi présence automatique</li>
    </ol>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ config('app.url') }}/admin/students/import" style="background-color: #3498db; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            Importer mes données maintenant
        </a>
    </div>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 20px;">
        <p style="margin: 0;"><strong>Résultat :</strong> Récupérez 68% de votre temps administratif (pas de papiers, tout en ligne).</p>
    </div>

    <p style="margin-top: 30px; color: #7f8c8d;">
        Équipe Taalimu
    </p>
</div>
