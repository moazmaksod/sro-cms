@if ($config['enabled'])
    <div class="mb-4">
        <iframe src="https://discordapp.com/widget?id={{ $config['server_id'] }}&theme={{ $config['theme'] }}" width="100%" height="500" allowtransparency="true" frameborder="0"></iframe>
    </div>
@endif
