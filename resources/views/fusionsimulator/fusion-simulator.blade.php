<!-- resources/views/fusionsimulator/fusion-simulator.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Fusion-Simulator</h1>

    <div>
        <label><input type="checkbox" id="lucky"> Lucky Powder</label>
        <label><input type="checkbox" id="dress"> Dress (+4%)</label>
        <label><input type="checkbox" id="stone"> Luck Stone (+5%)</label>
        <label><input type="checkbox" id="premium"> Premium (+5%)</label>
    </div>

    <div>
        <p id="level">Aktuelles Level: +0</p>
        <p id="elixirs">Elixiere benutzt: 0</p>
        <button onclick="fuse()">Fuse</button>
        <button onclick="reset()">Reset</button>
        <button onclick="openSettings()">⚙️ Einstellungen</button>
    </div>

    <hr>

    <div>
        <label>Anzahl Läufe:</label>
        <input type="number" id="runs" value="100">
        <button onclick="simulate()">Simulieren</button>
        <div id="results"></div>
        <button onclick="toggleDetails()">Details anzeigen</button>

        <div id="detailsPanel" style="display:none; margin-top: 1rem;">
            <h4>Gesamte Elixieranzahl je Stufe (über alle Runs)</h4>
            <p style="font-size: 0.9em; margin-top: -0.5em;">→ Wie oft insgesamt jeder Pluswert in allen Durchläufen erreicht wurde</p>
            <table border="1" cellpadding="6" style="width:100%; text-align:center;">
                <thead>
                    <tr>
                        <th>+1</th><th>+2</th><th>+3</th><th>+4</th><th>+5</th><th>+6</th><th>+7</th><th>+8</th><th>+9</th><th>+10</th><th>+11</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="detailRow"></tr>
                </tbody>
            </table>
            <br>
            <h4>Durchschnittliche Elixieranzahl um Stufe zu erreichen</h4>
            <p style="font-size: 0.9em; margin-top: -0.5em;">→ Schätzung: Wie viele Elixiere man durchschnittlich braucht, um bis zu dieser Stufe zu kommen</p>
            <table border="1" cellpadding="6" style="width:100%; text-align:center;">
                <thead>
                    <tr>
                        <th>+1</th><th>+2</th><th>+3</th><th>+4</th><th>+5</th><th>+6</th><th>+7</th><th>+8</th><th>+9</th><th>+10</th><th>+11</th>
                    </tr>
                </thead>
                <tbody>
                    <tr id="avgCostRow"></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- SETTINGS MODAL -->
<div id="settingsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.6); z-index:1000; align-items:center; justify-content:center;">
    <div style="background:white; padding:20px; border-radius:8px; width:700px; max-width:95%; margin:auto; color: black; display: flex; gap: 20px; flex-wrap: wrap;">

        <form id="settingsForm" style="flex: 1; min-width: 280px;">
            <h3>Elixier-Chancen +0 bis +11:</h3>
            @for ($i = 0; $i <= 10; $i++)
                <label>+{{ $i }} → +{{ $i+1 }} (%):</label>
                <input type="number" step="1" min="0" max="100" name="elixier[]" value="{{ round($defaultRates[$i] * 100, 2) }}" required><br>
            @endfor

            <h3>Lucky Powder-Chancen +0 bis +11:</h3>
            @for ($i = 0; $i <= 10; $i++)
                <label>+{{ $i }} → +{{ $i+1 }} (%):</label>
                <input type="number" step="1" min="0" max="100" name="powder[]" value="{{ isset($defaultPowders[$i]) ? round($defaultPowders[$i], 2) : 0 }}" required><br>
            @endfor

            <button type="submit">Speichern</button>
            <button type="button" onclick="closeSettings()">Schließen</button>
        </form>

        <div style="flex: 1; min-width: 300px;">
            <h3>Import/Export Elixier</h3>
            <label>Param2:</label><input type="number" id="param2" placeholder="z. B. 438270386"><br>
            <label>Param3:</label><input type="number" id="param3"><br>
            <label>Param4:</label><input type="number" id="param4"><br>
            <button type="button" onclick="importParams('elixier', param2, param3, param4)">Import</button>
            <button type="button" onclick="exportParams('elixier', 'exportOutput')">Export anzeigen</button>
            <pre id="exportOutput"></pre>

            <h3>Import/Export Lucky Powder</h3>
            <label>Param2:</label><input type="number" id="powder2"><br>
            <label>Param3:</label><input type="number" id="powder3"><br>
            <label>Param4:</label><input type="number" id="powder4"><br>
            <button type="button" onclick="importParams('powder', powder2, powder3, powder4)">Import</button>
            <button type="button" onclick="exportParams('powder', 'powderOutput')">Export anzeigen</button>
            <pre id="powderOutput"></pre>
        </div>
    </div>
</div>

<script>
    let elixirRates = @json(array_map(fn($v) => round($v * 100, 2), $defaultRates));
    let powderRates = [50, 60, 20, 15, 15, 8, 8, 8, 8, 8, 5];

    let level = 0;
    let elixirs = 0;

    function fuse() {
        if (level >= 11) {
            alert("Maximales Plus (+11) erreicht!");
            return;
        }

        let rate = elixirRates[level] / 100;
        if (document.getElementById('lucky').checked) rate += powderRates[level] / 100;
        if (document.getElementById('dress').checked) rate += 0.04;
        if (document.getElementById('stone').checked) rate += 0.05;
        if (document.getElementById('premium').checked) rate += 0.05;
        rate = Math.min(rate, 1.0);

        if (Math.random() <= rate) {
            level = Math.min(level + 1, 11);
        } else {
            level = 0;
        }

        elixirs += 1;
        document.getElementById('level').innerText = "Aktuelles Level: +" + level;
        document.getElementById('elixirs').innerText = "Elixiere benutzt: " + elixirs;
    }

    function reset() {
        level = 0;
        elixirs = 0;
        document.getElementById('level').innerText = "Aktuelles Level: +0";
        document.getElementById('elixirs').innerText = "Elixiere benutzt: 0";
    }

    function simulate() {
        const runs = parseInt(document.getElementById('runs').value);
        const lucky = document.getElementById('lucky').checked;
        const dress = document.getElementById('dress').checked;
        const stone = document.getElementById('stone').checked;
        const premium = document.getElementById('premium').checked;

        const results = [];
        const levelTracker = Array(11).fill(0);

        for (let i = 0; i < runs; i++) {
            let lvl = 0;
            let attempts = 0;
            let progressTracker = Array(11).fill(0);

            while (lvl < 11) {
                let rate = elixirRates[lvl] / 100;
                if (lucky) rate += powderRates[lvl] / 100;
                if (dress) rate += 0.04;
                if (stone) rate += 0.05;
                if (premium) rate += 0.05;
                rate = Math.min(rate, 1.0);

                if (Math.random() <= rate) {
                    lvl++;
                } else {
                    lvl = 0;
                }

                if (lvl <= 11) progressTracker[lvl - 1] += 1;
                attempts++;
            }

            for (let j = 0; j < 11; j++) {
                levelTracker[j] += progressTracker[j];
            }

            results.push(attempts);
        }

        results.sort((a, b) => a - b);
        const avg = (results.reduce((a, b) => a + b) / results.length).toFixed(2);
        const median = results[Math.floor(results.length / 2)];
        const min = Math.min(...results);
        const max = Math.max(...results);

        document.getElementById('results').innerHTML =
            `<p>Durchschnittlich: ${avg}</p>
             <p>Min: ${min}</p>
             <p>Max: ${max}</p>
             <p>Median: ${median}</p>`;

        const detailHTML = levelTracker.map(sum => Math.round(sum / runs)).map(num => `<td>${num}</td>`).join('');
        document.getElementById('detailRow').innerHTML = detailHTML;

        // Analytische Berechnung der durchschnittlichen Elixierzahl pro Stufe
        const p = [];
        for (let k = 0; k < 11; k++) {
            let rate = elixirRates[k] / 100;
            if (lucky) rate += powderRates[k] / 100;
            if (dress) rate += 0.04;
            if (stone) rate += 0.05;
            if (premium) rate += 0.05;
            p.push(Math.min(rate, 1.0));
        }
        const expected = [];
        let cumulative = 0;
        let product = 1;
        for (let k = 0; k < 11; k++) {
            cumulative += 1 / p[k] / (k === 0 ? 1 : product);
            product *= p[k];
            expected.push(Math.round(cumulative * 10) / 10);
        }
        const avgCostHTML = expected.map(v => `<td>${v}</td>`).join('');
        document.getElementById('avgCostRow').innerHTML = avgCostHTML;
    }

    function toggleDetails() {
        const panel = document.getElementById('detailsPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }

    function openSettings() {
        document.getElementById('settingsModal').style.display = 'flex';
    }

    function closeSettings() {
        document.getElementById('settingsModal').style.display = 'none';
    }

    function toDWORD(a, b, c, d) {
        return (a & 0xFF) + ((b & 0xFF) << 8) + ((c & 0xFF) << 16) + ((d & 0xFF) << 24);
    }

    function importParams(type, p2, p3, p4) {
        const decode = (val) => [val & 0xFF, (val >> 8) & 0xFF, (val >> 16) & 0xFF, (val >> 24) & 0xFF];
        const arr = [...decode(parseInt(p2.value)).reverse(), ...decode(parseInt(p3.value)).reverse(), ...decode(parseInt(p4.value)).reverse()];

        document.querySelectorAll(`[name="${type}[]"]`).forEach((el, i) => {
            el.value = arr[i] || 0;
        });
    }

    function exportParams(type, outputId) {
        const values = Array.from(document.querySelectorAll(`[name="${type}[]"]`))
            .map(e => parseInt(e.value)).slice(0, 12);

        while (values.length < 12) values.push(0);

        const toLEBytes = (list) => list.slice().reverse();

        const p2 = toLEBytes(values.slice(0, 4));
        const p3 = toLEBytes(values.slice(4, 8));
        const p4 = toLEBytes(values.slice(8, 12));

        const param2 = toDWORD(...p2);
        const param3 = toDWORD(...p3);
        const param4 = toDWORD(...p4);

        document.getElementById(outputId).innerText =
            `Param2: ${param2}\nParam3: ${param3}\nParam4: ${param4}`;
    }

    document.getElementById('settingsForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const newElix = Array.from(document.querySelectorAll('[name="elixier[]"]'))
            .map(input => Math.min(100, Math.max(0, parseFloat(input.value))) / 100);
        const newPowder = Array.from(document.querySelectorAll('[name="powder[]"]'))
            .map(input => Math.min(100, Math.max(0, parseFloat(input.value))) / 100);

        if (newElix.length !== 11 || newPowder.length !== 11) {
            alert("Bitte alle 11 Erfolgsraten korrekt eingeben.");
            return;
        }
        elixirRates = newElix.map(v => v * 100);
        powderRates = newPowder.map(v => v * 100);
        closeSettings();
    });
</script>
@endsection

