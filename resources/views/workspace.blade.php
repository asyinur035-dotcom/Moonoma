<style>
.project-box {
    background: #0d0f0d;
    border: 1px solid #3E5641;
    padding: 20px;
    border-radius: 16px;
    margin-bottom: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

.project-box h4 {
    margin-top: 0;
    margin-bottom: 15px;
    font-size: 16px;
    color: #f5d679;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.workspace-input {
    width: 100%;
    background: #1a1f1a;
    border: 1px solid #3E5641;
    color: #fff;
    padding: 12px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 10px;
    transition: 0.3s;
}
.workspace-input:focus {
    border-color: #6f8a75;
    outline: none;
}

.workspace-btn {
    background: #3E5641;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    cursor: pointer;
    transition: 0.2s;
    font-weight: 600;
}
.workspace-btn:hover {
    background: #4a664d;
}

.task-item {
    display: flex;
    align-items: center;
    background: #1a1f1a;
    border: 1px solid #3E5641;
    padding: 10px 15px;
    border-radius: 10px;
    margin-bottom: 8px;
    font-size: 13px;
    transition: 0.2s;
}
.task-item:hover {
    border-color: #6f8a75;
}

.task-item.checked {
    opacity: 0.6;
}

.task-checkbox {
    margin-right: 15px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #f5d679;
}

.task-text {
    flex: 1;
}

.task-delete {
    background: transparent;
    border: none;
    color: #e87c7c;
    cursor: pointer;
    font-size: 16px;
}

.progress-container {
    background: #1a1f1a;
    border-radius: 10px;
    height: 8px;
    width: 100%;
    margin-top: 15px;
    overflow: hidden;
}
.progress-bar {
    height: 100%;
    background: #f5d679;
    width: 0%;
    transition: 0.4s ease;
}

.design-preview-btn {
    display: inline-block;
    background: linear-gradient(135deg, #f5d679, #c9a227);
    color: #1a1100;
    padding: 10px 20px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 700;
    font-size: 13px;
    transition: 0.2s;
}
.design-preview-btn:hover {
    box-shadow: 0 0 15px rgba(245, 214, 121, 0.4);
}

</style>

@php
    $isCreator = session('email') === ($roomData['created_by'] ?? '') || session('role') === 'admin';
    $designLink = $roomData['workspace_design_link'] ?? '';
    $description = $roomData['workspace_desc'] ?? '';
    $tasks = $roomData['workspace_tasks'] ?? [];
    $userEmail = session('email');
    $userProgress = $roomData['workspace_progress'][$userEmail] ?? [];
@endphp

<!-- DESIGN PREVIEW -->
<div class="project-box">
    <h4>Design Preview</h4>
    @if($isCreator)
        <textarea id="wsDesignLink" class="workspace-input" placeholder="Paste Figma, Canva, or any design links here..." style="height:60px; resize:vertical;">{{ $designLink }}</textarea>
    @else
        <div style="background:#1a1f1a; padding:15px; border-radius:10px; border:1px solid #3E5641; color:#f5d679; font-size:13px; white-space:pre-wrap; word-wrap:break-word;">{{ $designLink ?: 'No design link provided yet.' }}</div>
    @endif
</div>

<!-- PROJECT DESCRIPTION -->
<div class="project-box">
    <h4>Project Description</h4>
    @if($isCreator)
        <textarea id="wsDescription" class="workspace-input" placeholder="Describe the project goals, requirements..." style="height:100px; resize:none;">{{ $description }}</textarea>
    @else
        <div style="background:#1a1f1a; padding:15px; border-radius:10px; border:1px solid #3E5641; color:#ccc; font-size:13px; white-space:pre-wrap;">{{ $description ?: 'No description provided.' }}</div>
    @endif
</div>

<!-- TASK LIST -->
<div class="project-box">
    <h4>
        Task List
        <span id="progressText" style="font-size:12px; color:#ccc;">Progress: 0%</span>
    </h4>
    
    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>

    <div style="margin-top:20px;" id="wsTaskList">
        <!-- Tasks will be rendered here by JS -->
    </div>

    @if($isCreator)
        <div style="display:flex; gap:10px; margin-top:15px;">
            <input type="text" id="wsNewTask" class="workspace-input" style="margin:0;" placeholder="New task...">
            <button onclick="addTask()" class="workspace-btn" style="flex-shrink:0;">+ Add</button>
        </div>
    @endif
</div>

@if($isCreator)
    <div style="text-align:right; margin-bottom:20px;">
        <button onclick="saveWorkspaceConfig()" class="workspace-btn" style="background:#f5d679; color:#1a1100; font-size:14px; padding:10px 24px;">Save All Workspace Changes</button>
    </div>
@endif



<script>
let wsRoomSlug = "{{ $roomData['slug'] }}";
let isCreator = {{ $isCreator ? 'true' : 'false' }};
let wsTasks = @json($tasks);
let wsProgress = @json($userProgress); // array of checked task indices



// TASKS & PROGRESS
function renderTasks() {
    let box = document.getElementById('wsTaskList');
    box.innerHTML = '';

    if (wsTasks.length === 0) {
        box.innerHTML = '<div style="color:#6f8a75; font-size:12px; text-align:center; padding:10px;">No tasks available.</div>';
    }

    let activeTaskIndex = -1;
    if (!isCreator) {
        for (let i = 0; i < wsTasks.length; i++) {
            if (!wsProgress[i]) {
                activeTaskIndex = i;
                break;
            }
        }
    }

    wsTasks.forEach((task, index) => {
        let isCompleted = !!wsProgress[index];
        let isLocked = !isCreator && !isCompleted && index !== activeTaskIndex;
        let isActive = !isCreator && index === activeTaskIndex;

        let deleteBtn = isCreator ? `<button onclick="removeTask(${index})" class="task-delete">&times;</button>` : '';

        let statusHtml = '';
        if (isCompleted) {
            let filePath = wsProgress[index];
            let fileLink = filePath && filePath !== 'legacy_completed' && filePath !== 'uploaded_just_now' 
                ? `<a href="{{ asset('') }}${filePath}" target="_blank" style="color:#f5d679; text-decoration:underline; font-size:11px; margin-left:10px;">View File</a>` 
                : '';
            statusHtml = `<div style="color:#a8d5a2; font-size:12px; display:flex; align-items:center;">✅ Completed ${fileLink}</div>`;
        } else if (isCreator) {
            statusHtml = `<div style="color:#6f8a75; font-size:12px;">(Waiting for participants)</div>`;
        } else if (isLocked) {
            statusHtml = `<div style="color:#6f8a75; font-size:12px;">🔒 Locked</div>`;
        } else if (isActive) {
            statusHtml = `
                <div style="display:flex; gap:10px; align-items:center;">
                    <input type="file" id="taskFile_${index}" style="font-size:11px; color:#ccc; max-width:150px;" required>
                    <button onclick="submitTask(${index})" class="workspace-btn" style="padding:4px 10px; font-size:11px;">Upload</button>
                </div>
            `;
        }

        let opacity = isLocked ? 'opacity:0.5;' : '';

        box.innerHTML += `
            <div class="task-item" style="${opacity}">
                <div class="task-text" style="${isCompleted ? 'text-decoration:line-through; color:#6f8a75;' : 'color:#fff;'}">${task}</div>
                ${statusHtml}
                ${deleteBtn ? `<div style="margin-left:15px;">${deleteBtn}</div>` : ''}
            </div>
        `;
    });

    updateProgressUI();
}

function submitTask(index) {
    let fileInput = document.getElementById('taskFile_' + index);
    let file = fileInput.files[0];
    if (!file) {
        alert("Please select a file to upload.");
        return;
    }

    let formData = new FormData();
    formData.append('task_index', index);
    formData.append('file', file);

    let btn = event.target;
    let oldText = btn.innerText;
    btn.innerText = "Uploading...";
    btn.disabled = true;

    fetch(`/rooms/${wsRoomSlug}/workspace/submit-task`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            wsProgress[index] = "uploaded_just_now"; // Fake it until reload
            renderTasks();
            alert("File uploaded successfully! Next task unlocked.");
            // Optional: reload page to get actual file link
            window.location.reload();
        } else {
            alert(data.message || 'Failed to submit task');
            btn.innerText = oldText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Error uploading file.');
        btn.innerText = oldText;
        btn.disabled = false;
    });
}

function addTask() {
    if(!isCreator) return;
    let input = document.getElementById('wsNewTask');
    let val = input.value.trim();
    if(!val) return;

    wsTasks.push(val);
    input.value = '';
    renderTasks();
}

function removeTask(index) {
    if(!isCreator) return;
    wsTasks.splice(index, 1);
    
    // Simplistic shifting for creator's local progress (though creator rarely uploads)
    let newProgress = {};
    for (const [key, value] of Object.entries(wsProgress)) {
        let pInt = parseInt(key);
        if (pInt < index) newProgress[pInt] = value;
        if (pInt > index) newProgress[pInt - 1] = value;
    }
    wsProgress = newProgress;
    
    renderTasks();
}

function updateProgressUI() {
    let total = wsTasks.length;
    let completed = Object.keys(wsProgress).length;
    let percent = total === 0 ? 0 : Math.round((completed / total) * 100);
    
    document.getElementById('progressText').innerText = `Progress: ${percent}%`;
    document.getElementById('progressBar').style.width = `${percent}%`;
}

function saveWorkspaceConfig() {
    if(!isCreator) return;
    
    let link = document.getElementById('wsDesignLink').value;
    let desc = document.getElementById('wsDescription').value;
    let tasksStr = JSON.stringify(wsTasks);

    let btn = event.target;
    let originalText = btn.innerText;
    btn.innerText = "Saving...";
    btn.disabled = true;

    fetch(`/rooms/${wsRoomSlug}/workspace/save`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            design_link: link,
            description: desc,
            tasks: tasksStr
        })
    })
    .then(res => res.json())
    .then(data => {
        if(data.success) {
            btn.innerText = "Saved!";
            setTimeout(() => { btn.innerText = originalText; btn.disabled = false; }, 2000);
        } else {
            alert(data.message || 'Failed to save');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    })
    .catch(err => {
        alert('Error saving workspace.');
        btn.innerText = originalText;
        btn.disabled = false;
    });
}

// INIT
renderTasks();
</script>