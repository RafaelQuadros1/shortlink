import './cookie-consent';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('shorten-form');

    if (form) {
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

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
                    throw new Error(data.message || 'Erro ao encurtar o link.');
                }

                form.reset();

                await navigator.clipboard.writeText(data.short_url);

                submitBtn.textContent = 'Copiado!';
                setTimeout(() => {
                    submitBtn.textContent = originalText;
                }, 2000);
            } catch (err) {
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
