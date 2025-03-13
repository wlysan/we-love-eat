/**
 * Main JavaScript for NutriMenu Application
 */

// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Scroll to bottom of chat container
    const chatContainer = document.getElementById('chatContainer');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
    
    // Format date inputs
    formatDateInputs();
    
    // Dynamic ingredient fields in meal forms
    setupDynamicIngredientFields();
    
    // Meal selection functionality
    setupMealSelection();
    
    // BMI calculator
    setupBMICalculator();
});

/**
 * Format date inputs to Brazilian format (DD/MM/YYYY)
 */
function formatDateInputs() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('focus', function() {
            if (this.type === 'text') {
                this.type = 'date';
            }
        });
        
        input.addEventListener('blur', function() {
            if (this.value === '') {
                this.type = 'text';
            }
        });
    });
}

/**
 * Setup dynamic ingredient fields for meal creation/editing
 */
function setupDynamicIngredientFields() {
    const addIngredientBtn = document.getElementById('addIngredientBtn');
    if (addIngredientBtn) {
        addIngredientBtn.addEventListener('click', function() {
            const ingredientsContainer = document.getElementById('ingredientsContainer');
            const ingredientIndex = document.querySelectorAll('.ingredient-row').length;
            
            const row = document.createElement('div');
            row.className = 'row ingredient-row mb-3';
            row.innerHTML = `
                <div class="col-md-6">
                    <select class="form-select" name="ingredient_id[]" required>
                        <option value="">Selecione um ingrediente</option>
                        ${document.querySelector('select[name="ingredient_id[]"]').innerHTML.split('<option value="">Selecione um ingrediente</option>')[1]}
                    </select>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="number" class="form-control" name="amount[]" placeholder="Quantidade" min="1" required>
                        <span class="input-group-text">g</span>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger remove-ingredient-btn w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;
            
            ingredientsContainer.appendChild(row);
            
            // Add event listener to the remove button
            row.querySelector('.remove-ingredient-btn').addEventListener('click', function() {
                ingredientsContainer.removeChild(row);
            });
        });
    }
    
    // Add event listeners to existing remove buttons
    document.querySelectorAll('.remove-ingredient-btn').forEach(button => {
        button.addEventListener('click', function() {
            const row = this.closest('.ingredient-row');
            row.parentNode.removeChild(row);
        });
    });
}

/**
 * Setup meal selection functionality
 */
function setupMealSelection() {
    const mealSelects = document.querySelectorAll('.meal-select');
    if (mealSelects.length > 0) {
        mealSelects.forEach(select => {
            select.addEventListener('change', function() {
                const mealId = this.value;
                const dietMealId = this.dataset.dietMealId;
                const date = this.dataset.date;
                const nutritionContainer = document.getElementById(`nutrition-${dietMealId}`);
                
                if (mealId) {
                    // Fetch meal nutrition data
                    fetch(`/api/meals/nutrition?id=${mealId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Update nutrition information
                            nutritionContainer.innerHTML = `
                                <div class="mt-2 small">
                                    <span class="badge bg-secondary me-1">
                                        <i class="fas fa-fire me-1"></i> ${data.calories} kcal
                                    </span>
                                    <span class="badge bg-secondary me-1">
                                        <i class="fas fa-drumstick-bite me-1"></i> ${data.protein}g
                                    </span>
                                    <span class="badge bg-secondary me-1">
                                        <i class="fas fa-bread-slice me-1"></i> ${data.carbs}g
                                    </span>
                                    <span class="badge bg-secondary">
                                        <i class="fas fa-cheese me-1"></i> ${data.fat}g
                                    </span>
                                </div>
                            `;
                            
                            // Auto-submit the form
                            setTimeout(() => {
                                this.closest('form').submit();
                            }, 500);
                        })
                        .catch(error => {
                            console.error('Error fetching nutrition data:', error);
                        });
                } else {
                    nutritionContainer.innerHTML = '';
                }
            });
        });
    }
}

/**
 * Setup BMI calculator
 */
function setupBMICalculator() {
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('current_weight');
    const bmiResult = document.getElementById('bmiResult');
    
    if (heightInput && weightInput && bmiResult) {
        const calculateBMI = () => {
            const height = parseFloat(heightInput.value) / 100; // Convert cm to m
            const weight = parseFloat(weightInput.value);
            
            if (height > 0 && weight > 0) {
                const bmi = weight / (height * height);
                let category = '';
                let color = '';
                
                if (bmi < 18.5) {
                    category = 'Abaixo do peso';
                    color = 'text-warning';
                } else if (bmi < 25) {
                    category = 'Peso normal';
                    color = 'text-success';
                } else if (bmi < 30) {
                    category = 'Sobrepeso';
                    color = 'text-warning';
                } else {
                    category = 'Obesidade';
                    color = 'text-danger';
                }
                
                bmiResult.innerHTML = `
                    <div class="mt-2">
                        <h6>Seu IMC: <span class="${color}">${bmi.toFixed(1)}</span></h6>
                        <p class="mb-0 small">Classificação: <span class="${color}">${category}</span></p>
                    </div>
                `;
            } else {
                bmiResult.innerHTML = '';
            }
        };
        
        heightInput.addEventListener('input', calculateBMI);
        weightInput.addEventListener('input', calculateBMI);
        
        // Calculate on page load if values are present
        if (heightInput.value && weightInput.value) {
            calculateBMI();
        }
    }
}