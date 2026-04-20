<style>
.project-box{
    border:1px solid #3E5641;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
}

textarea{
    width:100%;
    background:#0d0f0d;
    border:1px solid #3E5641;
    color:#fff;
    padding:10px;
    border-radius:8px;
    margin-top:10px;
}

.project-box button{
    margin-top:10px;
    background:#3E5641;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    color:#fff;
    cursor:pointer;
}

.task-input{
    display:flex;
    gap:10px;
    margin-top:10px;
}

.task-input input{
    flex:1;
    padding:8px;
    border-radius:8px;
    border:1px solid #3E5641;
    background:#0d0f0d;
    color:#fff;
}

.task-item{
    display:flex;
    justify-content:space-between;
    border:1px solid #3E5641;
    padding:8px;
    border-radius:8px;
    margin-top:8px;
    font-size:12px;
}

.file-item{
    border:1px solid #3E5641;
    padding:8px;
    border-radius:8px;
    margin-top:8px;
    font-size:12px;
}
</style>

<div class="project-box">
    <h4>Project Description</h4>
    <textarea id="projectInput" placeholder="Describe project..."></textarea>
    <button onclick="saveProject()">Save</button>
    <p id="projectDesc">No project yet</p>
</div>

<div class="project-box">
    <h4>Task List</h4>

    <div class="task-input">
        <input type="text" id="taskInput" placeholder="Add task...">
        <button onclick="addTask()">Add</button>
    </div>

    <div id="taskList"></div>
    <div id="progressText">Progress: 0%</div>
</div>

<div class="project-box">
    <h4>Upload File</h4>
    <input type="file" onchange="uploadFile(event)">
    <div id="fileList"></div>
</div>

<script>
let room = currentRoom; // 🔥 FIX WAJIB

let projects = JSON.parse(localStorage.getItem('roomProjects')) || {};
let tasks = JSON.parse(localStorage.getItem('roomTasks')) || {};
let files = JSON.parse(localStorage.getItem('roomFiles')) || {};

/* PROJECT */
function saveProject(){
    let val = document.getElementById('projectInput').value;
    projects[room] = val;
    localStorage.setItem('roomProjects', JSON.stringify(projects));
    renderProject();
}

function renderProject(){
    let val = projects[room] || "";
    document.getElementById('projectDesc').innerText = val || "No project yet";
    document.getElementById('projectInput').value = val;
}

/* TASK */
function addTask(){
    let input = document.getElementById('taskInput');
    let val = input.value.trim();
    if(!val) return;

    if(!tasks[room]) tasks[room] = [];

    tasks[room].push({text: val, done:false});
    localStorage.setItem('roomTasks', JSON.stringify(tasks));

    input.value = '';
    renderTasks();
}

function toggleTask(i){
    tasks[room][i].done = !tasks[room][i].done;
    localStorage.setItem('roomTasks', JSON.stringify(tasks));
    renderTasks();
}

function renderTasks(){
    let box = document.getElementById('taskList');
    box.innerHTML = '';

    let list = tasks[room] || [];

    list.forEach((t,i)=>{
        box.innerHTML += `
            <div class="task-item">
                <span style="${t.done?'text-decoration:line-through':''}">
                    ${t.text}
                </span>
                <input type="checkbox" ${t.done?'checked':''}
                    onclick="toggleTask(${i})">
            </div>
        `;
    });

    updateProgress();
}

/* PROGRESS */
function updateProgress(){
    let list = tasks[room] || [];

    if(list.length === 0){
        document.getElementById('progressText').innerText = "Progress: 0%";
        return;
    }

    let done = list.filter(t => t.done).length;
    let percent = Math.round((done / list.length) * 100);

    document.getElementById('progressText').innerText = "Progress: " + percent + "%";
}

/* FILE */
function uploadFile(e){
    let file = e.target.files[0];
    if(!file) return;

    if(!files[room]) files[room] = [];

    files[room].push(file.name);
    localStorage.setItem('roomFiles', JSON.stringify(files));

    renderFiles();
}

function renderFiles(){
    let box = document.getElementById('fileList');
    box.innerHTML = '';

    (files[room] || []).forEach(f=>{
        box.innerHTML += `<div class="file-item">📄 ${f}</div>`;
    });
}

/* INIT */
renderProject();
renderTasks();
renderFiles();
</script>