Bonjour Mr/Me/Mme <i style="text-transform: uppercase;"><strong> {{ $receiver['first_name']  }} {{ $receiver['last_name']  }} </strong></i>
<p>Félicitations votre compte a été créé avec succès</p>

<div>
    <p>
        &nbsp;vos coordonnées de connexion sont les suivants: <br/><br/>
        <strong><u>Login</u>: </strong><strong><i> {{ $receiver['login'] }} </i></strong><br/>
        <strong><u>Password</u>: </strong><strong><i> {{ $receiver['password']}} </i></strong>
        <p style="text-align=justify;">Vous avez été enbauche comme administrateur du service: <strong>{{ $service['name'] }}</strong></p>
        <strong>Voici une description du service que vous allez desormais Administrer</strong>
        <p style="text-align=justify;">
            {{ $service['description'] }}
        </p>
    </p>
</div>

Merci,

<br/>
<i>L'équipe de <strong> {{ $companie_name }} </strong></i>