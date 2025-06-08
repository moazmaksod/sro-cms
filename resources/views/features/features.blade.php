@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Linke Navigation -->
        <div class="col-md-3">
            <div class="list-group shadow-sm">
                <button class="list-group-item list-group-item-action active" onclick="showFeature('feature1')">⚔ Feature 1</button>
                <button class="list-group-item list-group-item-action" onclick="showFeature('feature2')">⏱ Feature 2</button>
                <button class="list-group-item list-group-item-action" onclick="showFeature('feature3')">🧭 Feature 3</button>
            </div>
        </div>

        <!-- Rechte Anzeige -->
        <div class="col-md-9">
            <div id="feature1" class="feature-box">
                <div class="card shadow-sm">
                    <img src="{{ asset('images/feature1.jpg') }}" class="card-img-top" alt="Feature 1">
                    <div class="card-body">
                        <h3 class="card-title">Auto-Potion System</h3>
                        <p class="card-text">Unser System erkennt automatisch den HP-/MP-Wert und nutzt Potions sofort – ideal für PvP und PvE!</p>
                    </div>
                </div>
            </div>

            <div id="feature2" class="feature-box d-none">
                <div class="card shadow-sm">
                    <img src="{{ asset('images/feature2.jpg') }}" class="card-img-top" alt="Feature 2">
                    <div class="card-body">
                        <h3 class="card-title">Unique Spawn-Timer</h3>
                        <p class="card-text">Alle Uniques haben einen öffentlichen Timer – verfolge Spawn-Zeiten live auf unserer Map.</p>
                    </div>
                </div>
            </div>

            <div id="feature3" class="feature-box d-none">
                <div class="card shadow-sm">
                    <img src="{{ asset('images/feature3.jpg') }}" class="card-img-top" alt="Feature 3">
                    <div class="card-body">
                        <h3 class="card-title">Ingame Web Panel</h3>
                        <p class="card-text">Verwalte Charaktere, Teleports und Shops direkt über unser integriertes Web-Panel im Spiel.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script zum Umschalten -->
<script>
    function showFeature(id) {
        // Alle ausblenden
        document.querySelectorAll('.feature-box').forEach(el => {
            el.classList.add('d-none');
            el.classList.remove('fade-in');
        });

        // Angezeigte Box sichtbar & animieren
        const box = document.getElementById(id);
        box.classList.remove('d-none');
        box.classList.add('fade-in');

        // Aktives Menü markieren
        document.querySelectorAll('.list-group-item').forEach(el => el.classList.remove('active'));
        event.target.classList.add('active');
    }
</script>
<style>
    .fade-in {
        animation: fadeIn 0.4s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection
