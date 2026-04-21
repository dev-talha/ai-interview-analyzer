<?php $s = $settings; ?>
<div class="row g-4">
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm"><div class="card-body p-4 p-lg-5">
      <h1 class="h4 mb-3">Ollama Configuration</h1>
      <p class="text-secondary">These settings are managed from the admin panel so deployment changes do not require code edits.</p>
      <form method="post" action="/admin/settings" class="row g-3">
        <input type="hidden" name="_token" value="<?= csrf_token() ?>">
        <div class="col-12 form-check form-switch ms-1">
          <input class="form-check-input" type="checkbox" name="ollama_enabled" <?= (($s['ollama_enabled'] ?? 'true') === 'true') ? 'checked' : '' ?>>
          <label class="form-check-label">Enable Ollama analysis</label>
        </div>
        <div class="col-12">
            <label class="form-label d-block">Connection Mode</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="ollama_mode" id="modeLocal" value="local" <?= ($s['ollama_mode'] ?? 'local') === 'local' ? 'checked' : '' ?> onchange="toggleMode()">
              <label class="form-check-label" for="modeLocal">Local Ollama</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="ollama_mode" id="modeCloud" value="cloud" <?= ($s['ollama_mode'] ?? 'local') === 'cloud' ? 'checked' : '' ?> onchange="toggleMode()">
              <label class="form-check-label" for="modeCloud">Ollama Cloud API</label>
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Base URL</label>
            <input name="ollama_base_url" id="base_url" class="form-control" value="<?= e($s['ollama_base_url'] ?? env('OLLAMA_BASE_URL', 'http://host.docker.internal:11434')) ?>">
            <div class="form-text">For cloud API, you might use <code>https://api.ollama.com</code> or your provider's URL.</div>
        </div>
        <div class="col-12" id="apiKeyContainer" style="<?= ($s['ollama_mode'] ?? 'local') === 'cloud' ? '' : 'display:none;' ?>">
            <label class="form-label">API Key</label>
            <input type="password" name="ollama_api_key" class="form-control" value="<?= e($s['ollama_api_key'] ?? '') ?>" placeholder="Bearer Token / API Key">
        </div>
        <div class="col-md-6">
            <label class="form-label">Model</label>
            <div class="input-group">
                <input type="text" name="ollama_model" id="ollama_model" class="form-control" value="<?= e($s['ollama_model'] ?? env('OLLAMA_MODEL')) ?>">
                <button type="button" class="btn btn-outline-secondary" id="fetchModelsBtn" onclick="fetchModels()">Fetch Models</button>
            </div>
            <div id="modelsHelp" class="form-text"></div>
        </div>
        <div class="col-md-3"><label class="form-label">Timeout (sec)</label><input name="ollama_timeout" class="form-control" value="<?= e($s['ollama_timeout'] ?? env('OLLAMA_TIMEOUT')) ?>"></div>
        <div class="col-md-3"><label class="form-label">Temperature</label><input name="ollama_temperature" class="form-control" value="<?= e($s['ollama_temperature'] ?? env('OLLAMA_TEMPERATURE')) ?>"></div>
        <div class="col-12">
            <label class="form-label">System Prompt</label>
            <textarea name="ollama_system_prompt" class="form-control" rows="4" placeholder="You are an interview evaluator..."><?= e($s['ollama_system_prompt'] ?? "You are an interview evaluator. Review the candidate answer and return only valid JSON with these keys: relevance_score, clarity_score, confidence_score, professionalism_score, feedback, suggestion. Scores must be numbers between 0 and 10. Keep feedback and suggestion concise.") ?></textarea>
            <div class="form-text">Instructions for the AI. It MUST include requirements to return valid JSON with specific keys if using the built-in parser.</div>
        </div>

        <div class="col-12 mt-5">
            <h4 class="h5 mb-3 border-bottom pb-2">Speech-To-Text (STT) Settings</h4>
        </div>
        <div class="col-12">
            <label class="form-label d-block">STT Approach</label>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="stt_approach" id="sttBrowser" value="browser" <?= ($s['stt_approach'] ?? 'browser') === 'browser' ? 'checked' : '' ?> onchange="toggleSTT()">
              <label class="form-check-label" for="sttBrowser">Browser Native (Approach A)</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="stt_approach" id="sttGroq" value="groq" <?= ($s['stt_approach'] ?? 'browser') === 'groq' ? 'checked' : '' ?> onchange="toggleSTT()">
              <label class="form-check-label" for="sttGroq">Groq Cloud API (Approach B)</label>
            </div>
        </div>
        
        <div class="col-12" id="groqSettings" style="<?= ($s['stt_approach'] ?? 'browser') === 'groq' ? '' : 'display:none;' ?>">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Groq API Key</label>
                    <input type="password" name="groq_api_key" class="form-control" value="<?= e($s['groq_api_key'] ?? '') ?>" placeholder="gsk_...">
                </div>
                <div class="col-12">
                    <label class="form-label">Groq Base URL</label>
                    <input type="text" name="groq_base_url" class="form-control" value="<?= e($s['groq_base_url'] ?? 'https://api.groq.com/openai/v1/audio/transcriptions') ?>">
                </div>
            </div>
        </div>

        <div class="col-12 d-flex gap-2 mt-4">
            <button class="btn btn-primary">Save Settings</button>
        </form>
        <form method="post" action="/admin/settings/test">
            <input type="hidden" name="_token" value="<?= csrf_token() ?>">
            <button class="btn btn-outline-dark">Test Connection</button>
        </form>
        </div>

<script>
function toggleMode() {
    const isCloud = document.getElementById('modeCloud').checked;
    document.getElementById('apiKeyContainer').style.display = isCloud ? 'block' : 'none';
}

function toggleSTT() {
    const isGroq = document.getElementById('sttGroq').checked;
    document.getElementById('groqSettings').style.display = isGroq ? 'block' : 'none';
}

function fetchModels() {
  const btn = document.getElementById('fetchModelsBtn');
  const help = document.getElementById('modelsHelp');
  btn.disabled = true;
  btn.innerText = 'Fetching...';
  
  fetch('/admin/settings/models', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: '_token=<?= csrf_token() ?>'
  })
  .then(res => res.json())
  .then(data => {
    btn.disabled = false;
    btn.innerText = 'Fetch Models';
    if(data && data.length > 0) {
      help.innerHTML = 'Available: <a href="javascript:void(0)" onclick="selectModel(this)" class="text-decoration-none border-bottom">' + data.join('</a>, <a href="javascript:void(0)" onclick="selectModel(this)" class="text-decoration-none border-bottom">') + '</a>';
    } else {
      help.innerText = 'No models found or connection error. Ensure you have saved the correct Base URL and Mode/API Key.';
    }
  })
  .catch(err => {
    btn.disabled = false;
    btn.innerText = 'Fetch Models';
    help.innerText = 'Error fetching models.';
  });
}

function selectModel(elem) {
  document.getElementById('ollama_model').value = elem.innerText;
}
</script>
    </div></div>
  </div>
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm"><div class="card-body p-4">
      <h2 class="h5">Deployment Notes</h2>
      <ul class="text-secondary mb-0">
        <li>Default Docker app URL: http://localhost:8080</li>
        <li>phpMyAdmin: http://localhost:8081</li>
        <li>For Dockerized app to reach host Ollama, host.docker.internal is already mapped.</li>
      </ul>
    </div></div>
  </div>
</div>
