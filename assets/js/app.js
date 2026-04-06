/**
 * Application JavaScript
 * Funções e validações globais
 */

// ========================================================
// UTILITIES
// ========================================================

/**
 * Confirma ação antes de executar (delete, etc)
 */
function confirmAction(message = 'Tem certeza que deseja continuar?', callback = null) {
    if (confirm(message)) {
        if (callback && typeof callback === 'function') {
            callback();
        }
        return true;
    }
    return false;
}

/**
 * Deleta item via GET request (com confirmação)
 */
function deleteItem(itemId, itemName = 'item') {
    if (confirmAction(`Tem certeza que deseja deletar este ${itemName}?`)) {
        const urlParams = new URLSearchParams(window.location.search);
        const currentUrl = window.location.href.split('?')[0] + '?' + urlParams.toString();
        const deleteUrl = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'action=delete&id=' + itemId;
        window.location.href = deleteUrl;
    }
}

/**
 * Valida email
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Valida CPF (formato básico)
 */
function isValidCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    return cpf.length === 11;
}

/**
 * Formata CPF enquanto digita
 */
function formatCPFInput(input) {
    let cpf = input.value.replace(/\D/g, '');
    if (cpf.length > 11) cpf = cpf.substring(0, 11);
    
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
    cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    
    input.value = cpf;
}

/**
 * Formata telefone enquanto digita
 */
function formatPhoneInput(input) {
    let phone = input.value.replace(/\D/g, '');
    if (phone.length > 11) phone = phone.substring(0, 11);
    
    if (phone.length > 5) {
        phone = '(' + phone.substring(0, 2) + ') ' + phone.substring(2, 7) + '-' + phone.substring(7);
    } else if (phone.length > 2) {
        phone = '(' + phone.substring(0, 2) + ') ' + phone.substring(2);
    }
    
    input.value = phone;
}

/**
 * Formata data DD/MM/YYYY
 */
function formatDateInput(input) {
    let date = input.value.replace(/\D/g, '');
    
    if (date.length >= 2) {
        date = date.substring(0, 2) + '/' + date.substring(2);
    }
    if (date.length >= 5) {
        date = date.substring(0, 5) + '/' + date.substring(5, 9);
    }
    
    input.value = date;
}

/**
 * Valida data DD/MM/YYYY
 */
function isValidDate(dateStr) {
    const regex = /^(\d{2})\/(\d{2})\/(\d{4})$/;
    const match = dateStr.match(regex);
    
    if (!match) return false;
    
    const day = parseInt(match[1], 10);
    const month = parseInt(match[2], 10);
    const year = parseInt(match[3], 10);
    
    // Verificar mês
    if (month < 1 || month > 12) return false;
    
    // Verificar dia
    const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    
    // Verificar ano bissexto
    if (year % 4 === 0 && (year % 100 !== 0 || year % 400 === 0)) {
        daysInMonth[1] = 29;
    }
    
    if (day < 1 || day > daysInMonth[month - 1]) return false;
    
    return true;
}

/**
 * Converte DD/MM/YYYY para YYYY-MM-DD
 */
function convertDate(dateStr) {
    if (!isValidDate(dateStr)) return null;
    
    const [day, month, year] = dateStr.split('/');
    return `${year}-${month}-${day}`;
}

/**
 * Converte YYYY-MM-DD para DD/MM/YYYY
 */
function convertDateToDisplay(dateStr) {
    const [year, month, day] = dateStr.split('-');
    return `${day}/${month}/${year}`;
}

// ========================================================
// FORMS
// ========================================================

/**
 * Valida e submete formulário
 */
function submitForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.submit();
    }
}

/**
 * Limpa formulário
 */
function resetForm(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
    }
}

/**
 * Desabilita botão quando formulário é submetido (evita duplos cliques)
 */
function disableSubmitButton(formId, buttonId) {
    const form = document.getElementById(formId);
    const button = document.getElementById(buttonId);
    
    if (form && button) {
        form.addEventListener('submit', function() {
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';
        });
    }
}

// ========================================================
// TABLE UTILITIES
// ========================================================

/**
 * Seleciona/deseleciona todos os checkboxes da tabela
 */
function toggleSelectAll(selectAllCheckbox) {
    const checkboxes = document.querySelectorAll('input.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

/**
 * Obtém IDs dos itens selecionados
 */
function getSelectedItems() {
    const checkboxes = document.querySelectorAll('input.item-checkbox:checked');
    const ids = [];
    checkboxes.forEach(checkbox => {
        ids.push(checkbox.value);
    });
    return ids;
}

// ========================================================
// DOM READY
// ========================================================

document.addEventListener('DOMContentLoaded', function() {
    // Auto-validação de campos de data
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (!isValidDate(convertDateToDisplay(this.value))) {
                alert('Data inválida!');
                this.value = '';
            }
        });
    });

    // Auto-formatação de CPF
    const cpfInputs = document.querySelectorAll('input.cpf-input');
    cpfInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatCPFInput(this);
        });
        
        input.addEventListener('blur', function() {
            if (this.value && !isValidCPF(this.value)) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    // Auto-formatação de telefone
    const phoneInputs = document.querySelectorAll('input.phone-input');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatPhoneInput(this);
        });
    });

    // Auto-formatação de data
    const dateFormatInputs = document.querySelectorAll('input.date-format-input');
    dateFormatInputs.forEach(input => {
        input.addEventListener('input', function() {
            formatDateInput(this);
        });
    });

    // Disable submit button
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        const form = button.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                button.disabled = true;
                button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processando...';
            });
        }
    });
});

// ========================================================
// CHARTS.JS HELPERS
// ========================================================

/**
 * Cria chart (linha, bar, pie, etc)
 */
function createChart(canvasId, type, data, options = {}) {
    const ctx = document.getElementById(canvasId);
    if (ctx) {
        return new Chart(ctx, {
            type: type,
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                ...options
            }
        });
    }
}

/**
 * Destroi chart existente
 */
function destroyChart(chartInstance) {
    if (chartInstance) {
        chartInstance.destroy();
    }
}
