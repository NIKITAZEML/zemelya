if (document.getElementById('adding-form')) {
    document.getElementById('adding-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const messageContainer = document.getElementById('message-container');

        const form = e.target;
        const addingFilename = form.getAttribute('data-form');

        // Показываем индикатор загрузки
        const submitButton = this.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.textContent = 'Добавление...';
        submitButton.disabled = true;

        // Отправляем AJAX запрос
        const addingFilePath = '/creates/' + addingFilename;
        fetch(addingFilePath, {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                // Очищаем предыдущие сообщения
                messageContainer.innerHTML = '';

                if (data.success) {
                    // Показываем успешное сообщение
                    const successDiv = document.createElement('div');
                    successDiv.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg';
                    successDiv.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <strong>Успешно!</strong> ${data.message} (ID: ${data.contract_id})
                </div>
            `;
                    messageContainer.appendChild(successDiv);

                    // Очищаем форму
                    document.getElementById('adding-form').reset();
                    window.location.reload()
                    // Автоматически скрываем сообщение через 5 секунд
                    setTimeout(() => {
                        successDiv.remove();
                    }, 5000);

                } else {
                    // Показываем ошибки
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg';

                    let errorHtml = `
                <div class="flex">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Ошибка!</strong> ${data.message}
            `;

                    if (data.errors) {
                        errorHtml += '<ul class="mt-1 ml-4 list-disc">';
                        data.errors.forEach(error => {
                            errorHtml += `<li>${error}</li>`;
                        });
                        errorHtml += '</ul>';
                    }

                    errorHtml += '</div></div>';
                    errorDiv.innerHTML = errorHtml;
                    messageContainer.appendChild(errorDiv);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                messageContainer.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                <strong>Ошибка!</strong> Произошла ошибка при отправке формы.
            </div>
        `;
            })
            .finally(() => {
                // Восстанавливаем кнопку
                submitButton.textContent = originalText;
                submitButton.disabled = false;
            });
    });
}