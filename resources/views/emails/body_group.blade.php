Bonjour Mr/Me/Mme <i style="text-transform: uppercase;"><strong> {{ $receiver->first_name }} {{ $receiver->last_name }} </strong></i>
<p>Cool vous venez de recevoir une invitation de <strong> {{ $sender->first_name }} {{ $sender->last_name }} </strong> à rejoindre le groupe <strong> {{ $group->name }} </strong></p>

<div>
    <p>&nbsp;vous devez clicker sur ce lien pour accepter l'invitation <strong> <a href="{{ $link }}">Lien de confirmation</a> </strong>
</div>

Merci,

<br/>
<i>L'équipe de {{ $companie_name }}</i>