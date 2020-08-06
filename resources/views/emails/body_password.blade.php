Bonjour Mr/Me/Mme <i style="text-transform: uppercase;"><strong> {{ $receiver['first_name']  }} {{ $receiver['last_name']  }} </strong></i>
<p>Votre mot de passe a été réinitialisé avec succès</p>

<div>
    <p>
        &nbsp;Vos nouveaux coordonnées de connexion sont les suivants: <br/><br/>
        <strong><u>Email</u>: </strong><strong><i> {{ $receiver['email'] }} </i></strong><br/>
        <strong><u>Password</u>: </strong><strong><i> {{ $password}} </i></strong>
    </p>
</div>

Merci,

<br/>
<i>L'équipe de <strong> {{ $companie_name }} </strong></i>