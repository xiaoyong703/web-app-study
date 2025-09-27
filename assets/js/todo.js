// Todo List functionality
class TodoManager {
    constructor() {
        this.todoForm = document.getElementById('todo-form');
        this.todoInput = document.getElementById('todo-input');
        this.todoList = document.getElementById('todo-list');
        
        this.init();
    }
    
    init() {
        this.bindEvents();
    }
    
    bindEvents() {
        if (this.todoForm) {
            this.todoForm.addEventListener('submit', (e) => this.addTodo(e));
        }
        
        if (this.todoList) {
            this.todoList.addEventListener('click', (e) => this.handleTodoClick(e));
            this.todoList.addEventListener('change', (e) => this.handleTodoChange(e));
        }
    }
    
    async addTodo(e) {
        e.preventDefault();
        
        const task = this.todoInput.value.trim();
        if (!task) return;
        
        try {
            const response = await fetch('api/add-todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ task })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.addTodoToDOM(result.todo);
                this.todoInput.value = '';
                this.showNotification('Task added successfully!', 'success');
            } else {
                this.showNotification('Failed to add task', 'error');
            }
        } catch (error) {
            console.error('Error adding todo:', error);
            this.showNotification('Error adding task', 'error');
        }
    }
    
    async handleTodoClick(e) {
        if (e.target.classList.contains('delete-btn') || e.target.parentElement.classList.contains('delete-btn')) {
            const todoItem = e.target.closest('.todo-item');
            const todoId = todoItem.dataset.id;
            
            if (confirm('Are you sure you want to delete this task?')) {
                await this.deleteTodo(todoId, todoItem);
            }
        }
    }
    
    async handleTodoChange(e) {
        if (e.target.classList.contains('todo-checkbox')) {
            const todoItem = e.target.closest('.todo-item');
            const todoId = todoItem.dataset.id;
            const completed = e.target.checked;
            
            await this.toggleTodo(todoId, completed, todoItem);
        }
    }
    
    async toggleTodo(todoId, completed, todoItem) {
        try {
            const response = await fetch('api/toggle-todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: todoId, completed })
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (completed) {
                    todoItem.classList.add('completed');
                    this.showNotification('Task completed! 🎉', 'success');
                } else {
                    todoItem.classList.remove('completed');
                }
            } else {
                // Revert checkbox if update failed
                const checkbox = todoItem.querySelector('.todo-checkbox');
                checkbox.checked = !completed;
                this.showNotification('Failed to update task', 'error');
            }
        } catch (error) {
            console.error('Error toggling todo:', error);
            // Revert checkbox if error occurred
            const checkbox = todoItem.querySelector('.todo-checkbox');
            checkbox.checked = !completed;
            this.showNotification('Error updating task', 'error');
        }
    }
    
    async deleteTodo(todoId, todoItem) {
        try {
            const response = await fetch('api/delete-todo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: todoId })
            });
            
            const result = await response.json();
            
            if (result.success) {
                todoItem.style.opacity = '0';
                todoItem.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    todoItem.remove();
                }, 300);
                
                this.showNotification('Task deleted', 'info');
            } else {
                this.showNotification('Failed to delete task', 'error');
            }
        } catch (error) {
            console.error('Error deleting todo:', error);
            this.showNotification('Error deleting task', 'error');
        }
    }
    
    addTodoToDOM(todo) {
        const todoItem = document.createElement('li');
        todoItem.className = `todo-item ${todo.completed ? 'completed' : ''}`;
        todoItem.dataset.id = todo.id;
        
        todoItem.innerHTML = `
            <input type="checkbox" class="todo-checkbox" ${todo.completed ? 'checked' : ''}>
            <span class="todo-text">${this.escapeHtml(todo.task)}</span>
            <button class="delete-btn" title="Delete task">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        // Add with animation
        todoItem.style.opacity = '0';
        todoItem.style.transform = 'translateY(-20px)';
        
        this.todoList.insertBefore(todoItem, this.todoList.firstChild);
        
        setTimeout(() => {
            todoItem.style.opacity = '1';
            todoItem.style.transform = 'translateY(0)';
        }, 100);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Style the notification based on type
        let backgroundColor;
        switch (type) {
            case 'success':
                backgroundColor = 'var(--success-color)';
                break;
            case 'error':
                backgroundColor = 'var(--danger-color)';
                break;
            case 'info':
            default:
                backgroundColor = 'var(--primary-color)';
                break;
        }
        
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${backgroundColor};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
}

// Initialize todo manager when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.todoManager = new TodoManager();
});