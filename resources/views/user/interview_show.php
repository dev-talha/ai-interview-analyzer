<?php $sttApproach = config('stt_approach', 'browser'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
  <div>
    <h1 class="h3 mb-1">Interview Session #<?= $session['id'] ?></h1>
    <p class="text-secondary mb-0">Answer each question thoughtfully. Submit once all answers are complete.</p>
  </div>
</div>
<form method="post" action="/interview/submit" onsubmit="showLoading()">
  <input type="hidden" name="_token" value="<?= csrf_token() ?>">
  <input type="hidden" name="session_id" value="<?= $session['id'] ?>">
  <div class="row g-4">
    <?php foreach ($questions as $index => $question): ?>
    <div class="col-12">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <label class="form-label fw-semibold">Question <?= $index + 1 ?>: <?= e($question['question_text']) ?></label>
          <div class="position-relative">
             <textarea id="answer_<?= $question['id'] ?>" name="answers[<?= $question['id'] ?>]" class="form-control" rows="5" required placeholder="Write your answer here or click record..."></textarea>
             <button type="button" class="btn btn-sm btn-outline-danger position-absolute bottom-0 end-0 m-2" onclick="toggleRecording(this, <?= $question['id'] ?>)">
               🎤 Record
             </button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <div class="mt-4 d-flex justify-content-end">
    <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
      <span id="btnText">Submit for AI Analysis</span>
      <span id="loadingSpinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
    </button>
  </div>
</form>

<script>
const sttApproach = "<?= $sttApproach ?>";
let activeRecorder = null;
let activeBtn = null;
let isRecording = false;
let audioChunks = [];

function showLoading() {
  document.getElementById('btnText').innerText = 'Analyzing answers...';
  document.getElementById('loadingSpinner').classList.remove('d-none');
  setTimeout(() => {
    document.getElementById('submitBtn').disabled = true;
  }, 10);
}

function toggleRecording(btn, qId) {
    const textarea = document.getElementById('answer_' + qId);

    if (isRecording) {
        if (activeBtn !== btn) {
            alert("Please stop the current recording first.");
            return;
        }
        stopRecording();
        return;
    }

    activeBtn = btn;
    if (sttApproach === 'browser') {
        startBrowserSTT(btn, textarea);
    } else {
        startGroqSTT(btn, textarea);
    }
}

// Approach A: Browser Native STT
function startBrowserSTT(btn, textarea) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    if (!SpeechRecognition) {
        alert("Your browser does not support Speech Recognition. Please try Google Chrome.");
        return;
    }
    const recognition = new SpeechRecognition();
    recognition.continuous = true;
    recognition.interimResults = true;
    
    recognition.onstart = function() {
        isRecording = true;
        btn.innerHTML = '⏹ Stop';
        btn.classList.replace('btn-outline-danger', 'btn-danger');
    };
    
    recognition.onresult = function(event) {
        let finalTranscripts = '';
        for (let i = event.resultIndex; i < event.results.length; i++) {
            if (event.results[i].isFinal) finalTranscripts += event.results[i][0].transcript + ' ';
        }
        if(finalTranscripts) Object.getOwnPropertyDescriptor(window.HTMLTextAreaElement.prototype, 'value').set.call(textarea, textarea.value + (textarea.value ? ' ' : '') + finalTranscripts);
    };
    
    recognition.onerror = function(event) {
        console.error(event.error);
        stopRecording();
    };

    activeRecorder = recognition;
    recognition.start();
}

// Approach B: Groq API Backend STT
async function startGroqSTT(btn, textarea) {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        const mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];
        
        mediaRecorder.ondataavailable = event => {
            audioChunks.push(event.data);
        };
        
        mediaRecorder.onstop = async () => {
            btn.innerHTML = '⏳ Processing...';
            btn.disabled = true;
            
            const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
            const formData = new FormData();
            formData.append('audio', audioBlob, 'recording.webm');
            
            try {
                const res = await fetch('/interview/transcribe', {
                    method: 'POST',
                    body: formData
                });
                const data = await res.json();
                if(res.ok && data.text) {
                    textarea.value += (textarea.value ? ' ' : '') + data.text;
                } else {
                    alert('Error: ' + (data.error || 'Failed to transcribe via Groq'));
                }
            } catch (e) {
                alert('Transcription failed: ' + e.message);
            }
            
            btn.innerHTML = '🎤 Record';
            btn.classList.replace('btn-danger', 'btn-outline-danger');
            btn.disabled = false;
        };
        
        mediaRecorder.start();
        activeRecorder = mediaRecorder;
        isRecording = true;
        btn.innerHTML = '⏹ Stop';
        btn.classList.replace('btn-outline-danger', 'btn-danger');
    } catch (err) {
        alert("Microphone access denied or error occurred.");
    }
}

function stopRecording() {
    isRecording = false;
    if (sttApproach === 'browser' && activeRecorder) {
        activeRecorder.stop();
        activeBtn.innerHTML = '🎤 Record';
        activeBtn.classList.replace('btn-danger', 'btn-outline-danger');
    } else if (activeRecorder && activeRecorder.state !== 'inactive') {
        activeRecorder.stop();
    }
    activeRecorder = null;
    activeBtn = null;
}
</script>
