@isset ($discord['enabled'])
    <div class="mb-4">
        <iframe src="https://discordapp.com/widget?id={{ $discord['server_id'] }}&theme={{ $discord['theme'] }}" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe>
    </div>
@endisset
