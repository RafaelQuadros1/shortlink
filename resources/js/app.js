import './cookie-consent';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('shorten-form');

    if (form) {
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorEl = document.getElementById('ajax-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            errorEl.classList.add('hidden');
            errorEl.textContent = '';

            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Encurtando...';
            submitBtn.disabled = true;

            const formData = new FormData(form);

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    const errors = data.errors || {};
                    const messages = Object.values(errors).flat();
                    throw new Error(messages.length > 0 ? messages[0] : (data.message || 'Erro ao encurtar o link.'));
                }

                form.reset();

                await navigator.clipboard.writeText(data.short_url);

                submitBtn.textContent = 'Copiado!';
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                }, 2000);
            } catch (err) {
                errorEl.textContent = err.message;
                errorEl.classList.remove('hidden');
                submitBtn.textContent = originalText;
            } finally {
                submitBtn.disabled = false;
            }
        });
    }

    document.querySelectorAll('.example-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.dataset.target;
            const content = document.getElementById(targetId);
            const chevron = btn.querySelector('[data-chevron]');
            content.classList.toggle('hidden');
            chevron.style.transform = content.classList.contains('hidden') ? '' : 'rotate(180deg)';
        });
    });
});
