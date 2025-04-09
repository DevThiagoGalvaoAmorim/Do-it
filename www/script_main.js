// mensagem de erro
document.addEventListener('DOMContentLoaded', function() {
    const profileIcon = document.querySelector('.profile-icon');
    const infoTab = document.getElementById('infoTab');

    profileIcon.addEventListener('click', function() {
        infoTab.classList.toggle('active');
    });

    // fecha o menu ao clicar fora
    document.addEventListener('click', function(event) {
        if (!infoTab.contains(event.target) && !profileIcon.contains(event.target)) {
            infoTab.classList.remove('active');
        }
    });
});

const API_URL = 'functions/tarefas.php';

async function validateIfExistsNewTask(taskName) {
    try {
        const response = await fetch(API_URL);
        const tasks = await response.json();
        return tasks.some(task => task.titulo === taskName);
    } catch (error) {
        console.error('Erro na validação:', error);
        return false;
    }
}

async function newTask() {
    let input = document.getElementById('input-new-task');
    const taskName = input.value.trim();

    if (!taskName) {
        alert('Adicione uma tarefa primeiro');
        return;
    }

    if (await validateIfExistsNewTask(taskName)) {
        alert('Já existe uma task com essa descrição');
        return;
    }

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ nomeTarefa: taskName })
        });
        
        if (response.ok) {
            showValues();
            input.value = '';
        } else {
            alert('Erro ao criar tarefa');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Falha na comunicação com o servidor');
    }
}

async function showValues() {
    try {
        const response = await fetch(API_URL);
        const values = await response.json();
        let list = document.getElementById('to-do-list');
        list.innerHTML = '';
        
        values.forEach(item => {
            list.innerHTML += `
                <li>${item.titulo}
                    <button id='btn-ok' onclick='removeItem(${item.id})'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                        </svg>
                    </button>
                </li>`;
        });
    } catch (error) {
        console.error('Erro ao carregar tarefas:', error);
    }
}

async function removeItem(id) {    
    try {
        const response = await fetch(`${API_URL}?id=${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            showValues();
        } else {
            alert('Erro ao remover tarefa');
        }
    } catch (error) {
        console.error('Erro:', error);
        alert('Falha na comunicação com o servidor');
    }
}

showValues();